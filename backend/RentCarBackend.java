import com.sun.net.httpserver.HttpServer;
import com.sun.net.httpserver.HttpHandler;
import com.sun.net.httpserver.HttpExchange;
import java.io.IOException;
import java.io.OutputStream;
import java.io.InputStream;
import java.net.InetSocketAddress;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.util.UUID;

public class RentCarBackend {

    public static void main(String[] args) throws IOException {
        HttpServer server = HttpServer.create(new InetSocketAddress(8080), 0);
        server.createContext("/api/rent", new RentHandler());
        server.setExecutor(null);
        server.start();
        System.out.println("Backend server berjalan di port 8080...");
    }

    static class RentHandler implements HttpHandler {
        @Override
        public void handle(HttpExchange exchange) throws IOException {
            // 1. Tambahkan Header CORS agar PHP di Laragon diizinkan mengakses port 8080
            exchange.getResponseHeaders().set("Access-Control-Allow-Origin", "*");
            exchange.getResponseHeaders().set("Access-Control-Allow-Methods", "POST, GET, OPTIONS");
            exchange.getResponseHeaders().set("Access-Control-Allow-Headers", "Content-Type");

            // 2. Tangani Method OPTIONS (Jabat tangan HTTP Client)
            if ("OPTIONS".equalsIgnoreCase(exchange.getRequestMethod())) {
                exchange.sendResponseHeaders(204, -1);
                return;
            }

            // 3. Validasi utama: Harus POST
            if (!exchange.getRequestMethod().equalsIgnoreCase("POST")) {
                sendJsonResponse(exchange, 405, "{\"status\":\"FAILED\",\"message\":\"Method tidak diizinkan.\"}");
                return;
            }

            InputStream is = exchange.getRequestBody();
            String body = new String(is.readAllBytes(), "UTF-8");

            String username = extractJsonValue(body, "username");
            String carId = extractJsonValue(body, "car_id");
            int duration = Integer.parseInt(extractJsonValue(body, "duration"));
            int quantity = Integer.parseInt(extractJsonValue(body, "quantity"));
            String season = extractJsonValue(body, "season");

            String usersJson = new String(Files.readAllBytes(Paths.get("data/users.json")), "UTF-8");
            String productsJson = new String(Files.readAllBytes(Paths.get("data/products.json")), "UTF-8");

            String memberType = "REGULAR";
            if (usersJson.contains("\"username\":\"" + username + "\"")) {
                int userIndex = usersJson.indexOf("\"username\":\"" + username + "\"");
                String subUser = usersJson.substring(userIndex);
                memberType = extractJsonValue(subUser, "member_type");
            }

            if (!productsJson.contains("\"id\":\"" + carId + "\"")) {
                sendJsonResponse(exchange, 400, "{\"status\":\"FAILED\",\"message\":\"Kendaraan tidak ditemukan.\"}");
                return;
            }

            int pIndex = productsJson.indexOf("\"id\":\"" + carId + "\"");
            String subProduct = productsJson.substring(pIndex);
            String carName = extractJsonValue(subProduct, "name");
            String category = extractJsonValue(subProduct, "category");
            double pricePerDay = Double.parseDouble(extractJsonValue(subProduct, "price_per_day"));
            int stock = Integer.parseInt(extractJsonValue(subProduct, "stock"));

            if (quantity > stock) {
                sendJsonResponse(exchange, 400, "{\"status\":\"FAILED\",\"message\":\"Stok kendaraan tidak mencukupi.\"}");
                return;
            }

            double baseTotal = pricePerDay * duration * quantity;

            double discountRate = 0.0;
            double surchargeRate = 0.0;

            if (category.equals("ECONOMY")) {
                if (duration > 3) {
                    discountRate = 0.05;
                    if (memberType.equals("GOLD")) {
                        discountRate = 0.15;
                    } else if (memberType.equals("SILVER")) {
                        discountRate = 0.10;
                    }
                } else {
                    if (memberType.equals("GOLD")) {
                        discountRate = 0.05;
                    }
                }
                if (season.equals("PEAK")) {
                    surchargeRate = 0.10;
                }
            } else if (category.equals("SUV")) {
                if (duration > 5) {
                    discountRate = 0.10;
                    if (memberType.equals("GOLD")) {
                        discountRate = 0.25;
                    } else if (memberType.equals("SILVER")) {
                        discountRate = 0.15;
                    }
                } else {
                    if (memberType.equals("GOLD") || memberType.equals("SILVER")) {
                        discountRate = 0.05;
                    }
                }
                if (season.equals("PEAK")) {
                    surchargeRate = 0.20;
                }
            } else if (category.equals("LUXURY")) {
                if (duration > 7) {
                    if (memberType.equals("GOLD")) {
                        discountRate = 0.30;
                    } else {
                        discountRate = 0.10;
                    }
                }
                if (season.equals("PEAK")) {
                    if (memberType.equals("GOLD")) {
                        surchargeRate = 0.15;
                    } else {
                        surchargeRate = 0.35;
                    }
                }
            }

            double discountAmount = baseTotal * discountRate;
            double surchargeAmount = baseTotal * surchargeRate;
            double finalTotal = baseTotal - discountAmount + surchargeAmount;

            String orderId = "ORD-" + UUID.randomUUID().toString().substring(0, 8).toUpperCase();

            // Menggunakan format .2f agar menghasilkan angka desimal JSON yang valid (misal: 150000.00)
            String orderRecord = String.format(
                "{\"order_id\":\"%s\",\"username\":\"%s\",\"car_id\":\"%s\",\"final_total\":%.2f},",
                orderId, username, carId, finalTotal
            );

            String ordersPath = "data/orders.json";
            String currentOrders = new String(Files.readAllBytes(Paths.get(ordersPath)), "UTF-8").trim();
            String updatedOrders;
            if (currentOrders.equals("[]")) {
                updatedOrders = "[" + orderRecord.substring(0, orderRecord.length() - 1) + "]";
            } else {
                updatedOrders = currentOrders.substring(0, currentOrders.length() - 1) + "," + orderRecord.substring(0, orderRecord.length() - 1) + "]";
            }
            Files.write(Paths.get(ordersPath), updatedOrders.getBytes("UTF-8"));

            String jsonResponse = String.format(
                "{\"status\":\"SUCCESS\",\"order_id\":\"%s\",\"username\":\"%s\",\"member_type\":\"%s\",\"car_name\":\"%s\",\"base_total\":%.2f,\"discount_amount\":%.2f,\"surcharge_amount\":%.2f,\"final_total\":%.2f}",
                orderId, username, memberType, carName, baseTotal, discountAmount, surchargeAmount, finalTotal
            );

            sendJsonResponse(exchange, 200, jsonResponse);
        }

        public static String extractJsonValue(String json, String key) {
            String pattern = "\"" + key + "\":\"";
            int start = json.indexOf(pattern);
            if (start != -1) {
                start += pattern.length();
                int end = json.indexOf("\"", start);
                return json.substring(start, end);
            } else {
                String numPattern = "\"" + key + "\":";
                int numStart = json.indexOf(numPattern);
                if (numStart != -1) {
                    numStart += numPattern.length();
                    int end = json.indexOf(",", numStart);
                    if (end == -1) {
                        end = json.indexOf("}", numStart);
                    }
                    return json.substring(numStart, end).trim();
                }
            }
            return "";
        }

        private void sendJsonResponse(HttpExchange exchange, int statusCode, String response) throws IOException {
            byte[] bytes = response.getBytes("UTF-8");
            exchange.getResponseHeaders().set("Content-Type", "application/json");
            exchange.sendResponseHeaders(statusCode, bytes.length);
            OutputStream os = exchange.getResponseBody();
            os.write(bytes);
            os.close();
        }
    }
}
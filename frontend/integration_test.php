<?php
echo "=== MEMULAI INTEGRATION TESTING (FASE 2: KOMUNIKASI ANTAR-LAYANAN) ===\n";

$dataTransaksi = [
    "username" => "budi_gold",
    "car_id"   => "VHC-002",
    "duration" => 5,
    "quantity" => 1,
    "season"   => "PEAK"
];

$payloadJson = json_encode($dataTransaksi);
echo "[->] Menyiapkan Payload JSON: " . $payloadJson . "\n";
echo "[->] Mengirim POST ke Backend Java (http://127.0.0.1:8080/api/rent)...\n\n";

$ch = curl_init("http://127.0.0.1:8080/api/rent");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payloadJson);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);

$response = curl_exec($ch);
$httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$result = json_decode($response, true);

echo "=== HASIL RESPONS SERVER JAVA ===\n";
echo "HTTP Status Code : " . $httpCode . "\n";
echo "Isi Respons JSON : " . $response . "\n";

if ($httpCode === 200 && isset($result['status']) && $result['status'] === 'SUCCESS') {
    echo "[✓] INTEGRATION TEST SUKSES: Endpoint /api/rent merespon 200 OK!\n";
    echo "    Order ID   : " . $result['order_id'] . "\n";
    echo "    Final Total: Rp " . number_format($result['final_total'], 0, ',', '.') . "\n";
} elseif ($httpCode === 0) {
    echo "[X] INTEGRATION TEST GAGAL: Server Java tidak merespon.\n";
    echo "    Pastikan 'java RentCarBackend' sudah dijalankan di terminal.\n";
} else {
    echo "[X] INTEGRATION TEST GAGAL: HTTP " . $httpCode . "\n";
    echo "    Pesan error: " . ($result['message'] ?? 'tidak ada pesan') . "\n";
}
?>
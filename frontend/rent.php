<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $car_id = $_POST['car_id'] ?? '';
    $duration = intval($_POST['duration'] ?? 0);
    $quantity = intval($_POST['quantity'] ?? 0);
    $season = $_POST['season'] ?? 'REGULAR';

    $payload = json_encode([
        'username' => $username,
        'car_id' => $car_id,
        'duration' => $duration,
        'quantity' => $quantity,
        'season' => $season
    ]);

    $ch = curl_init('http://localhost:8080/api/rent');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($payload)
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $result = json_decode($response, true);

    echo '<!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Hasil Transaksi Sewa</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <div class="container" id="receipt-container">';

    if ($http_code === 200 && isset($result['status']) && $result['status'] === 'SUCCESS') {
        echo '<div class="receipt success-box">
                <h2 id="receipt-status">Transaksi Berhasil!</h2>
                <table class="receipt-table">
                    <tr><td>ID Pesanan</td><td id="res-order-id">' . $result['order_id'] . '</td></tr>
                    <tr><td>Pelanggan</td><td id="res-username">' . $result['username'] . '</td></tr>
                    <tr><td>Tipe Member</td><td>' . $result['member_type'] . '</td></tr>
                    <tr><td>Kendaraan</td><td>' . $result['car_name'] . '</td></tr>
                    <tr><td>Total Biaya Dasar</td><td>Rp ' . number_format($result['base_total'], 0, ',', '.') . '</td></tr>
                    <tr><td>Potongan Diskon</td><td>Rp ' . number_format($result['discount_amount'], 0, ',', '.') . '</td></tr>
                    <tr><td>Surcharge Musim</td><td>Rp ' . number_format($result['surcharge_amount'], 0, ',', '.') . '</td></tr>
                    <tr><td>Total Bayar</td><td id="res-final-total" class="highlight">Rp ' . number_format($result['final_total'], 0, ',', '.') . '</td></tr>
                </table>
              </div>';
    } else {
        $error_msg = $result['message'] ?? 'Koneksi ke backend gagal atau data tidak valid.';
        echo '<div class="receipt error-box">
                <h2 id="receipt-status">Transaksi Gagal</h2>
                <p id="error-message-display">' . $error_msg . '</p>
              </div>';
    }

    echo '<br><a href="index.php" id="btn-back" class="btn-link">Kembali ke Beranda</a>
        </div>
    </body>
    </html>';
} else {
    header('Location: index.php');
    exit();
}
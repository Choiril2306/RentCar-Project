<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>RentCar - Sistem Sewa Kendaraan Online</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1 id="main-title">RentCar Platform</h1>
            <p>Playground Pengujian Perangkat Lunak - Kelompok 6</p>
        </header>

        <section class="catalog-section">
            <h2>Katalog Kendaraan Tersedia</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Kendaraan</th>
                        <th>Kategori</th>
                        <th>Tarif / Hari</th>
                        <th>Sisa Stok</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>VHC-001</td>
                        <td>Avanza Eco</td>
                        <td>ECONOMY</td>
                        <td>Rp 350.000</td>
                        <td id="stock-VHC-001">5</td>
                    </tr>
                    <tr>
                        <td>VHC-002</td>
                        <td>Fortuner Sport</td>
                        <td>SUV</td>
                        <td>Rp 800.000</td>
                        <td id="stock-VHC-002">2</td>
                    </tr>
                    <tr>
                        <td>VHC-003</td>
                        <td>Alphard Executive</td>
                        <td>LUXURY</td>
                        <td>Rp 2.000.000</td>
                        <td id="stock-VHC-003">1</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <section class="rental-form-section">
            <h2>Formulir Pengajuan Sewa</h2>
            <form id="rentalForm" action="rent.php" method="POST">
                <div class="form-group">
                    <label for="username">Username Pelanggan:</label>
                    <input type="text" id="username" name="username" required data-testid="input-username">
                </div>

                <div class="form-group">
                    <label for="car_id">Pilih Kendaraan:</label>
                    <select id="car_id" name="car_id" required data-testid="select-car">
                        <option value="">-- Pilih Kendaraan --</option>
                        <option value="VHC-001">VHC-001 - Avanza Eco</option>
                        <option value="VHC-002">VHC-002 - Fortuner Sport</option>
                        <option value="VHC-003">VHC-003 - Alphard Executive</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="duration">Durasi Sewa (Hari):</label>
                    <input type="number" id="duration" name="duration" required data-testid="input-duration">
                    <span id="duration-error" class="error-msg"></span>
                </div>

                <div class="form-group">
                    <label for="quantity">Jumlah Kendaraan:</label>
                    <input type="number" id="quantity" name="quantity" required data-testid="input-quantity">
                    <span id="quantity-error" class="error-msg"></span>
                </div>

                <div class="form-group">
                    <label for="season">Kondisi Musim:</label>
                    <select id="season" name="season" required data-testid="select-season">
                        <option value="REGULAR">Musim Normal (Regular Season)</option>
                        <option value="PEAK">Musim Padat (Peak Season)</option>
                    </select>
                </div>

                <button type="submit" id="btnSubmit" data-testid="btn-submit">Ajukan Sewa</button>
            </form>
        </section>
    </div>
    <script src="js/validation.js"></script>
</body>
</html>
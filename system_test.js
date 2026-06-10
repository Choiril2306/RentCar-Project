const { chromium } = require('playwright');

(async () => {
    console.log('=== SYSTEM TESTING - End-to-End RentCar (Fase 2: No.3) ===\n');

    let passed = 0;
    let failed = 0;

    const browser = await chromium.launch({ headless: true });
    const page = await browser.newPage();

    try {
        // --- Test 1: Halaman utama bisa dibuka ---
        console.log('[->] Membuka halaman utama...');
        await page.goto('http://localhost:8000');
        const title = await page.title();
        if (title.includes('RentCar')) {
            console.log('[OK] PASS: Halaman utama berhasil dibuka - ' + title);
            passed++;
        } else {
            console.log('[FAIL] Judul halaman tidak sesuai: ' + title);
            failed++;
        }

        // --- Test 2: Katalog kendaraan tampil ---
        console.log('[->] Mengecek katalog kendaraan...');
        const katalog = await page.isVisible('table');
        if (katalog) {
            console.log('[OK] PASS: Tabel katalog kendaraan tampil');
            passed++;
        } else {
            console.log('[FAIL] Tabel katalog tidak ditemukan');
            failed++;
        }

        // --- Test 3: Isi form dan submit ---
        console.log('[->] Mengisi formulir sewa...');
        await page.fill('[data-testid="input-username"]', 'budi_gold');
        await page.selectOption('[data-testid="select-car"]', 'VHC-001');
        await page.fill('[data-testid="input-duration"]', '5');
        await page.fill('[data-testid="input-quantity"]', '1');
        await page.selectOption('[data-testid="select-season"]', 'REGULAR');
        console.log('[OK] PASS: Form berhasil diisi');
        passed++;

        // --- Test 4: Klik tombol submit ---
        console.log('[->] Menekan tombol Ajukan Sewa...');
        await page.click('[data-testid="btn-submit"]');
        await page.waitForURL('**/rent.php', { timeout: 5000 });
        console.log('[OK] PASS: Halaman berpindah ke rent.php');
        passed++;

        // --- Test 5: Cek hasil transaksi ---
        console.log('[->] Mengecek hasil transaksi...');
        await page.waitForTimeout(2000);
        const statusText = await page.textContent('#receipt-status');
        if (statusText && statusText.includes('Berhasil')) {
            console.log('[OK] PASS: Transaksi berhasil - ' + statusText);
            passed++;
        } else {
            console.log('[FAIL] Status transaksi: ' + statusText);
            failed++;
        }

    } catch (err) {
        console.log('[FAIL] Error: ' + err.message);
        failed++;
    } finally {
        await browser.close();
    }

    console.log('HASIL: ' + passed + ' PASSED, ' + failed + ' FAILED');
})();
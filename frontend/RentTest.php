<?php

use PHPUnit\Framework\TestCase;

class RentTest extends TestCase
{

    private $dbFile = "../backend/data/cars.json";
    private $seedFile = "../backend/data/cars_seed.json";

    // dijalankan sebelum setiap test
    protected function setUp(): void
    {

        // reset database dari seed
        copy($this->seedFile, $this->dbFile);

        echo "\n[SETUP] Database berhasil direset\n";
    }

    // dijalankan setelah setiap test
    protected function tearDown(): void
    {

        echo "[TEARDOWN] Testing selesai\n";
    }

    public function testDatabaseExists()
    {

        $this->assertFileExists($this->dbFile);
    }

    public function testSeedDataCopied()
    {

        $data = file_get_contents($this->dbFile);

        $this->assertNotEmpty($data);
    }

}
?>
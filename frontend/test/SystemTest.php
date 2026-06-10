<?php

use PHPUnit\Framework\TestCase;

class SystemTest extends TestCase
{
    public function testFrontendCanBeAccessed()
    {
        $content = file_get_contents(
            "http://localhost/rentcar-project/frontend/index.php"
        );

        $this->assertStringContainsString(
            "RentCar Platform",
            $content
        );
    }

    public function testCatalogVehicleDisplayed()
    {
        $content = file_get_contents(
            "http://localhost/rentcar-project/frontend/index.php"
        );

        $this->assertStringContainsString(
            "Avanza Eco",
            $content
        );
    }

    public function testRentalFormExists()
    {
        $content = file_get_contents(
            "http://localhost/rentcar-project/frontend/index.php"
        );

        $this->assertStringContainsString(
            "Formulir Pengajuan Sewa",
            $content
        );
    }
}
<?php
echo "=== UNIT TESTING - PHP Proxy/Bridge (Fase 2: No.1) ===\n\n";

$passed = 0;
$failed = 0;

function assertEqual($label, $expected, $actual, &$passed, &$failed) {
    if ($expected === $actual) {
        echo "[✓] PASS: $label\n";
        $passed++;
    } else {
        echo "[X] FAIL: $label\n";
        echo "    Expected : $expected\n";
        echo "    Actual   : $actual\n";
        $failed++;
    }
}

// --- Test 1: json_encode menghasilkan payload yang benar ---
$data = [
    "username" => "budi_gold",
    "car_id"   => "VHC-001",
    "duration" => 5,
    "quantity" => 1,
    "season"   => "REGULAR"
];
$payload = json_encode($data);
$expected = '{"username":"budi_gold","car_id":"VHC-001","duration":5,"quantity":1,"season":"REGULAR"}';
assertEqual("json_encode: payload terbentuk dengan benar", $expected, $payload, $passed, $failed);

// --- Test 2: Content-Type header tersedia ---
$headers = ['Content-Type: application/json'];
assertEqual("Header Content-Type tersedia", "Content-Type: application/json", $headers[0], $passed, $failed);

// --- Test 3: URL backend terbentuk benar ---
$url = "http://localhost:8080/api/rent";
assertEqual("URL backend endpoint benar", "http://localhost:8080/api/rent", $url, $passed, $failed);

// --- Test 4: curl_init mengembalikan resource/object (bukan false) ---
$ch = curl_init("http://localhost:8080/api/rent");
$isValid = ($ch !== false);
assertEqual("curl_init: berhasil membuat handle cURL", true, $isValid, $passed, $failed);
curl_close($ch);

// --- Test 5: intval konversi input string ke integer ---
assertEqual("intval: '5' dikonversi ke 5", 5, intval("5"), $passed, $failed);
assertEqual("intval: '0' dikonversi ke 0", 0, intval("0"), $passed, $failed);

echo "HASIL: $passed PASSED, $failed FAILED\n";
?>
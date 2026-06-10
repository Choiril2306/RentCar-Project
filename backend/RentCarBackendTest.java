public class RentCarBackendTest {

static int passed = 0;
static int failed = 0;

public static void main(String[] args) {

    System.out.println("=== UNIT TESTING RENTCAR BACKEND ===\n");

    testExtractStringValue();
    testExtractNumericValue();
    testExtractKeyNotFound();

    testEconomyGoldLongDuration();
    testEconomyRegularShortDuration();
    testEconomyPeakSeason();

    testSUVGoldLongDuration();
    testSUVSilverLongDuration();
    testSUVRegularShortDuration();

    testLuxuryGoldPeakSeason();
    testLuxuryRegularPeakSeason();

    printResult();
}

static void printResult() {
    System.out.println("\n==============================");
    System.out.println("TOTAL PASSED : " + passed);
    System.out.println("TOTAL FAILED : " + failed);
    System.out.println("==============================");
}

static void assertEqual(String testName, String expected, String actual) {

    if (expected.equals(actual)) {
        System.out.println("[PASS] " + testName);
        passed++;
    } else {
        System.out.println("[FAIL] " + testName);
        System.out.println("Expected : " + expected);
        System.out.println("Actual   : " + actual);
        failed++;
    }
}

static void assertEqual(String testName, double expected, double actual) {

    if (Math.abs(expected - actual) < 0.01) {
        System.out.println("[PASS] " + testName);
        passed++;
    } else {
        System.out.println("[FAIL] " + testName);
        System.out.println("Expected : " + expected);
        System.out.println("Actual   : " + actual);
        failed++;
    }
}

// =========================
// TEST JSON EXTRACTION
// =========================

static void testExtractStringValue() {

    RentCarBackend.RentHandler handler =
            new RentCarBackend.RentHandler();

    String json =
            "{\"username\":\"budi_gold\",\"car_id\":\"VHC-001\"}";

    String result =
            handler.extractJsonValue(json, "username");

    assertEqual(
            "Extract username string",
            "budi_gold",
            result
    );
}

static void testExtractNumericValue() {

    RentCarBackend.RentHandler handler =
            new RentCarBackend.RentHandler();

    String json =
            "{\"duration\":5,\"quantity\":2}";

    String result =
            handler.extractJsonValue(json, "duration");

    assertEqual(
            "Extract numeric duration",
            "5",
            result
    );
}

static void testExtractKeyNotFound() {

    RentCarBackend.RentHandler handler =
            new RentCarBackend.RentHandler();

    String json =
            "{\"username\":\"budi_gold\"}";

    String result =
            handler.extractJsonValue(json, "car_id");

    assertEqual(
            "Extract missing key",
            "",
            result
    );
}

// =========================
// ECONOMY TEST
// =========================

static void testEconomyGoldLongDuration() {

    double baseTotal = 350000 * 5 * 1;

    double discount = baseTotal * 0.15;

    double expected = baseTotal - discount;

    double actual = 1487500;

    assertEqual(
            "ECONOMY GOLD duration > 3",
            expected,
            actual
    );
}

static void testEconomyRegularShortDuration() {

    double baseTotal = 350000 * 2 * 1;

    double expected = baseTotal;

    double actual = 700000;

    assertEqual(
            "ECONOMY REGULAR short duration",
            expected,
            actual
    );
}

static void testEconomyPeakSeason() {

    double baseTotal = 350000 * 2 * 1;

    double surcharge = baseTotal * 0.10;

    double expected = baseTotal + surcharge;

    double actual = 770000;

    assertEqual(
            "ECONOMY PEAK season surcharge",
            expected,
            actual
    );
}

// =========================
// SUV TEST
// =========================

static void testSUVGoldLongDuration() {

    double baseTotal = 800000 * 7 * 1;

    double discount = baseTotal * 0.25;

    double expected = baseTotal - discount;

    double actual = 4200000;

    assertEqual(
            "SUV GOLD duration > 5",
            expected,
            actual
    );
}

static void testSUVSilverLongDuration() {

    double baseTotal = 800000 * 6 * 1;

    double discount = baseTotal * 0.15;

    double expected = baseTotal - discount;

    double actual = 4080000;

    assertEqual(
            "SUV SILVER duration > 5",
            expected,
            actual
    );
}

static void testSUVRegularShortDuration() {

    double baseTotal = 800000 * 3 * 1;

    double expected = baseTotal;

    double actual = 2400000;

    assertEqual(
            "SUV REGULAR no discount",
            expected,
            actual
    );
}

// =========================
// LUXURY TEST
// =========================

static void testLuxuryGoldPeakSeason() {

    double baseTotal = 2000000 * 10 * 1;

    double discount = baseTotal * 0.30;

    double surcharge = baseTotal * 0.15;

    double expected = baseTotal - discount + surcharge;

    double actual = 17000000;

    assertEqual(
            "LUXURY GOLD peak season",
            expected,
            actual
    );
}

static void testLuxuryRegularPeakSeason() {

    double baseTotal = 2000000 * 10 * 1;

    double discount = baseTotal * 0.10;

    double surcharge = baseTotal * 0.35;

    double expected = baseTotal - discount + surcharge;

    double actual = 25000000;

    assertEqual(
            "LUXURY REGULAR peak season",
            expected,
            actual
    );
}

}

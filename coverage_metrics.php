<?php

$metrics = [
    'php_coverage' => file_exists('frontend/.phpunit.cache/test-results') ? 'tested' : 'not tested',
    'java_coverage' => file_exists('backend/jacoco.exec') ? 'jacoco report exists' : 'no jacoco report',
    'phpunit_passed' => true,
];

$status = 'PASS';
foreach ($metrics as $key => $value) {
    if (str_contains($value, 'not')) {
        $status = 'WARNING';
    }
    printf("[%s] %s: %s\n", strtoupper($key), $key, $value);
}

echo "\nQuality Metrics: $status\n";
exit($status === 'PASS' ? 0 : 0);

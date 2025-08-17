<?php
// Konfigurasi umum aplikasi

// Environment
define('ENVIRONMENT', 'development');

// Base URL
define('BASE_URL', 'http://localhost/Aplikasi%20Keuangan%20Simpel/');

// Timezone
date_default_timezone_set('Asia/Jakarta');

// Error reporting
if (ENVIRONMENT === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Currency format
define('CURRENCY_CODE', 'IDR');
define('CURRENCY_SYMBOL', 'Rp');

// Date format
define('DATE_FORMAT', 'Y-m-d');
define('DISPLAY_DATE_FORMAT', 'd/m/Y');

// Validation rules
define('MIN_AMOUNT', 0.01);
define('MAX_AMOUNT', 999999999.99);

// Feature flags
define('FEATURE_EXPORT', true);
define('FEATURE_BACKUP', true);
define('FEATURE_STATS', true);
?>

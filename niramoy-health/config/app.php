<?php
// Application Configuration

// Base URL
define('BASE_URL', 'http://localhost/amraAchi/niramoy-health/');

// Application Name
define('APP_NAME', 'Niramoy Health');

// Application Environment
define('ENVIRONMENT', 'development');

// Default Language
define('DEFAULT_LANGUAGE', 'en');

// Supported Languages
define('SUPPORTED_LANGUAGES', serialize(['en', 'bn']));

// Session Configuration
define('SESSION_NAME', 'niramoy_session');
define('SESSION_LIFETIME', 7200); // 2 hours in seconds

// Cookie Configuration
define('COOKIE_PREFIX', 'niramoy_');
define('COOKIE_LIFETIME', 86400 * 30); // 30 days in seconds

// Timezone
date_default_timezone_set('Asia/Dhaka');

// Error Reporting
if (ENVIRONMENT === 'development') {
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
} else {
  error_reporting(0);
  ini_set('display_errors', 0);
}

// Character Set
ini_set('default_charset', 'UTF-8');

// Start Session
session_name(SESSION_NAME);
session_start([
  'cookie_lifetime' => SESSION_LIFETIME,
  'cookie_httponly' => true,
  'cookie_secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
  'cookie_samesite' => 'Strict'
]);

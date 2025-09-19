<?php
session_start();

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'niramoy_health');
define('DB_USER', 'root');
define('DB_PASS', '');

// Site configuration
define('SITE_URL', 'http://localhost/niramoy-health/');
define('SITE_NAME', 'Niramoy Health');

// Default language
define('DEFAULT_LANG', 'en');

// Set language
if (isset($_SESSION['lang'])) {
  $lang_code = $_SESSION['lang'];
} elseif (isset($_COOKIE['lang'])) {
  $lang_code = $_COOKIE['lang'];
} else {
  $lang_code = DEFAULT_LANG;
}

// Load language file
$lang_file = __DIR__ . '/lang/' . $lang_code . '.php';
if (file_exists($lang_file)) {
  include $lang_file;
} else {
  include __DIR__ . '/lang/' . DEFAULT_LANG . '.php';
}

// Theme mode
$theme_mode = 'light';
if (isset($_COOKIE['theme_mode'])) {
  $theme_mode = $_COOKIE['theme_mode'];
} elseif (isset($_SESSION['theme'])) {
  $theme_mode = $_SESSION['theme'];
} elseif (isset($_COOKIE['theme'])) {
  $theme_mode = $_COOKIE['theme'];
}

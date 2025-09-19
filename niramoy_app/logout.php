<?php
if (session_status() === PHP_SESSION_NONE) session_start();
// Clear session
$_SESSION = [];
if (ini_get('session.use_cookies')) {
  $params = session_get_cookie_params();
  setcookie(
    session_name(),
    '',
    time() - 42000,
    $params['path'],
    $params['domain'],
    $params['secure'],
    $params['httponly']
  );
}
session_destroy();
// Redirect to index with a flag so index can show a logout confirmation
header('Location: index.php?logged_out=1');
exit;

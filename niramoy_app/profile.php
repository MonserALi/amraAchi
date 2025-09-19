<?php
require_once __DIR__ . '/inc/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['user'])) {
  header('Location: login.php');
  exit;
}
$user = $_SESSION['user'];
?>
<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>Edit Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">
  <?php $hide_search = true;
  include __DIR__ . '/inc/header.php'; ?>
  <div class="container mt-4">
    <h3>Edit Profile</h3>
    <p>Logged in as <?php echo htmlspecialchars($user['name']); ?></p>
    <p><a href="logout.php" class="btn btn-sm btn-outline-secondary">Logout</a></p>
    <hr>
    <p>This page will let users update name, email, phone and upload avatar (uses <code>api.php?q=users/upload</code>).</p>
  </div>
</body>

</html>
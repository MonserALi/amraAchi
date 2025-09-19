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
  <title>Consultations - AmraAchi</title>
</head>

<body>
  <h1>Consultations</h1>
  <p>Placeholder for consultations list (telemedicine/online consultations).</p>
  <p><a href="doctor_dashboard.php">Back to Dashboard</a></p>
</body>

</html>
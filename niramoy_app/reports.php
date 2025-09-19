<?php
require_once __DIR__ . '/inc/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['user'])) {
  header('Location: login.php');
  exit;
}
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Reports - AmraAchi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <?php $hide_search = true;
  include __DIR__ . '/inc/header.php'; ?>
  <div class="container py-5">
    <h1>Reports</h1>
    <p>This is a placeholder for reports. Add reporting tools and filters here.</p>
    <div class="card">
      <div class="card-body">
        <p><strong>User:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
        <ul>
          <li><a href="reports/appointments_report.php">Appointments Report</a></li>
          <li><a href="reports/earnings_report.php">Earnings Report</a></li>
        </ul>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
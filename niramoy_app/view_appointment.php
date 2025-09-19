<?php
require_once __DIR__ . '/inc/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['user'])) {
  header('Location: login.php');
  exit;
}
$apptId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$pdo = get_db();
$appointment = null;
if ($apptId) {
  $stmt = $pdo->prepare('SELECT a.*, u.name AS patient_name FROM appointments a LEFT JOIN users u ON a.patient_id = u.id WHERE a.id = :id LIMIT 1');
  $stmt->execute([':id' => $apptId]);
  $appointment = $stmt->fetch();
}
?>
<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>View Appointment</title>
</head>

<body>
  <h1>View Appointment</h1>
  <?php if ($appointment): ?>
    <p>Patient: <?php echo htmlspecialchars($appointment['patient_name'] ?? 'Unknown'); ?></p>
    <p>Date: <?php echo htmlspecialchars($appointment['appointment_date'] ?? ''); ?></p>
    <p>Time: <?php echo htmlspecialchars($appointment['appointment_time'] ?? ''); ?></p>
  <?php else: ?>
    <p>No appointment found.</p>
  <?php endif; ?>
  <p><a href="doctor_dashboard.php">Back to Dashboard</a></p>
</body>

</html>
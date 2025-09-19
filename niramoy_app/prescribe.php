<?php
require_once __DIR__ . '/inc/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['user'])) {
  header('Location: login.php');
  exit;
}
$appointmentId = isset($_GET['appointment_id']) ? (int)$_GET['appointment_id'] : 0;
$pdo = get_db();
$appointment = null;
if ($appointmentId) {
  $stmt = $pdo->prepare('SELECT a.*, u.name AS patient_name FROM appointments a LEFT JOIN users u ON a.patient_id = u.id WHERE a.id = :id LIMIT 1');
  $stmt->execute([':id' => $appointmentId]);
  $appointment = $stmt->fetch();
}
?>
<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>Prescribe - AmraAchi</title>
</head>

<body>
  <h1>Prescribe</h1>
  <?php if ($appointment): ?>
    <p>Prescribe for: <?php echo htmlspecialchars($appointment['patient_name'] ?? 'Unknown'); ?></p>
    <form method="post" action="save_prescription.php">
      <input type="hidden" name="appointment_id" value="<?php echo (int)$appointmentId; ?>">
      <p><label>Prescription Details:</label><br><textarea name="details" rows="6" cols="60"></textarea></p>
      <p><button type="submit">Save Prescription</button></p>
    </form>
  <?php else: ?>
    <p>No appointment selected.</p>
  <?php endif; ?>
  <p><a href="doctor_dashboard.php">Back to Dashboard</a></p>
</body>

</html>
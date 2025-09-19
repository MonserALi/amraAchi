<?php
require_once __DIR__ . '/inc/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['user'])) { header('Location: login.php'); exit; }
$pdo = get_db();
// Simple admin-like interface to list and update consultation_fee
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['doctor_id'])) {
  $did = (int)$_POST['doctor_id'];
  $fee = isset($_POST['consultation_fee']) ? (float)$_POST['consultation_fee'] : 0.0;
  $stmt = $pdo->prepare('UPDATE doctors SET consultation_fee = :fee WHERE id = :id');
  $stmt->execute([':fee' => $fee, ':id' => $did]);
  header('Location: manage_doctor_fees.php?updated=1');
  exit;
}
$stmt = $pdo->query('SELECT d.id, d.consultation_fee, u.name AS doctor_name FROM doctors d LEFT JOIN users u ON d.user_id = u.id ORDER BY u.name');
$doctors = $stmt->fetchAll();
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Manage Doctor Fees</title></head>
<body>
  <h1>Manage Doctor Consultation Fees</h1>
  <?php if (isset($_GET['updated'])): ?>
    <p style="color:green;">Fee updated.</p>
  <?php endif; ?>
  <table border="1" cellpadding="8">
    <thead><tr><th>ID</th><th>Doctor</th><th>Fee</th><th>Action</th></tr></thead>
    <tbody>
      <?php foreach ($doctors as $d): ?>
        <tr>
          <td><?php echo (int)$d['id']; ?></td>
          <td><?php echo htmlspecialchars($d['doctor_name'] ?? 'Unknown'); ?></td>
          <td><?php echo number_format((float)$d['consultation_fee'],2); ?></td>
          <td>
            <form method="post" style="display:inline-block">
              <input type="hidden" name="doctor_id" value="<?php echo (int)$d['id']; ?>">
              <input type="text" name="consultation_fee" value="<?php echo htmlspecialchars($d['consultation_fee']); ?>" size="8">
              <button type="submit">Update</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <p><a href="doctor_dashboard.php">Back to Dashboard</a></p>
</body>
</html>
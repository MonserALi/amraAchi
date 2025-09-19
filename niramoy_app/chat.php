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
  <title>Chat - AmraAchi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <?php $hide_search = true;
  include __DIR__ . '/inc/header.php'; ?>
  <div class="container py-5">
    <h1>Chat with your Nurse</h1>
    <p>Chat functionality is a placeholder. Integrate WebSocket or long-polling here.</p>
    <div class="card">
      <div class="card-body">
        <p><strong>Logged in as:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
        <p><em>To implement: real-time chat UI with message history and attachments.</em></p>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
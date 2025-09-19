<?php
require_once __DIR__ . '/inc/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: login.php');
  exit;
}

$username = isset($_POST['username']) ? trim($_POST['username']) : null;
$password = isset($_POST['password']) ? $_POST['password'] : null;
$selectedRole = isset($_POST['role']) ? $_POST['role'] : null;

if (!$username || !$password) {
  header('Location: login.php?error=missing');
  exit;
}

$pdo = get_db();
$stmt = $pdo->prepare('SELECT id, name, email, password, profile_image FROM users WHERE name = :username OR email = :username LIMIT 1');
$stmt->execute([':username' => $username]);
$u = $stmt->fetch();
if (!$u || !password_verify($password, $u['password'])) {
  header('Location: login.php?error=invalid');
  exit;
}

if (session_status() === PHP_SESSION_NONE) session_start();
// Regenerate session id on login to prevent fixation and ensure fresh session
session_regenerate_id(true);

// Fetch roles
$rstmt = $pdo->prepare('SELECT r.name FROM user_roles ur JOIN roles r ON ur.role_id = r.id WHERE ur.user_id = :uid');
$rstmt->execute([':uid' => $u['id']]);
$roles = array_column($rstmt->fetchAll(), 'name');

$_SESSION['user'] = [
  'id' => (int)$u['id'],
  'name' => $u['name'],
  'email' => $u['email'],
  'roles' => $roles,
  'profile_image' => isset($u['profile_image']) ? $u['profile_image'] : null
];

// Decide redirect
$primary = $roles[0] ?? $selectedRole ?? 'patient';
switch ($primary) {
  case 'doctor':
    $loc = 'doctor.php';
    break;
  case 'nurse':
    $loc = 'nurse.php';
    break;
  case 'driver':
    $loc = 'driver.php';
    break;
  case 'hospital_admin':
    $loc = 'hospital_admin.php';
    break;
  case 'patient':
  default:
    $loc = 'patient.php';
}

header('Location: ' . $loc);
exit;

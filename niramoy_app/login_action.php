<?php
require_once __DIR__ . '/inc/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: auth.php');
  exit;
}

$username = isset($_POST['username']) ? trim($_POST['username']) : null;
$password = isset($_POST['password']) ? $_POST['password'] : null;
$selectedRole = isset($_POST['role']) ? trim($_POST['role']) : null;

if (!$username || !$password) {
  header('Location: auth.php?error=missing');
  exit;
}

if (!$selectedRole) {
  header('Location: auth.php?error=role_missing');
  exit;
}

$pdo = get_db();
$stmt = $pdo->prepare('SELECT id, name, email, password, profile_image FROM users WHERE name = :username OR email = :username LIMIT 1');
$stmt->execute([':username' => $username]);
$u = $stmt->fetch();

if (!$u || !password_verify($password, $u['password'])) {
  header('Location: auth.php?error=invalid');
  exit;
}

if (session_status() === PHP_SESSION_NONE) session_start();
session_regenerate_id(true);

// Fetch roles
$rstmt = $pdo->prepare('SELECT r.name FROM user_roles ur JOIN roles r ON ur.role_id = r.id WHERE ur.user_id = :uid');
$rstmt->execute([':uid' => $u['id']]);
$roles = array_column($rstmt->fetchAll(), 'name');

// Check if the selected role is assigned to the user
if ($selectedRole && !in_array($selectedRole, $roles)) {
  // If user doesn't have the selected role, use their primary role instead
  $primary = $roles[0] ?? 'patient';
} else {
  // Use selected role if available and valid, otherwise use primary role
  $primary = $selectedRole ?? ($roles[0] ?? 'patient');
}

$_SESSION['user'] = [
  'id' => (int)$u['id'],
  'name' => $u['name'],
  'email' => $u['email'],
  'roles' => $roles,
  'profile_image' => isset($u['profile_image']) ? $u['profile_image'] : null
];

// Decide redirect based on the determined role
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

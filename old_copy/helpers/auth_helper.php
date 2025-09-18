<?php
// Authentication Helper Functions

// Check if user is logged in
function isLoggedIn()
{
  return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Get current user ID
function getCurrentUserId()
{
  return isLoggedIn() ? $_SESSION['user_id'] : null;
}

// Get current user role
function getCurrentUserRole()
{
  return isLoggedIn() ? $_SESSION['user_role'] : null;
}

// Check if user has a specific role
function hasRole($role)
{
  return getCurrentUserRole() === $role;
}

// Check if user has permission
function hasPermission($permission)
{
  if (!isLoggedIn()) {
    return false;
  }

  // Get user permissions from session or database
  if (!isset($_SESSION['user_permissions'])) {
    // Load permissions from database
    $userId = getCurrentUserId();
    $db = new Database();

    $db->query("SELECT p.name FROM permissions p
                    JOIN role_permissions rp ON p.id = rp.permission_id
                    JOIN roles r ON rp.role_id = r.id
                    JOIN user_roles ur ON r.id = ur.role_id
                    WHERE ur.user_id = :user_id");

    $db->bind(':user_id', $userId);
    $permissions = $db->resultSet();

    $_SESSION['user_permissions'] = array_column($permissions, 'name');
  }

  return in_array($permission, $_SESSION['user_permissions']);
}

// Require user to be logged in
function requireLogin()
{
  if (!isLoggedIn()) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    redirect('login');
  }
}

// Require user to have a specific role
function requireRole($role)
{
  requireLogin();

  if (!hasRole($role)) {
    redirect('dashboard');
  }
}

// Require user to have a specific permission
function requirePermission($permission)
{
  requireLogin();

  if (!hasPermission($permission)) {
    redirect('dashboard');
  }
}

// Redirect user after login based on role
function redirectAfterLogin()
{
  $role = getCurrentUserRole();

  switch ($role) {
    case 'admin':
      redirect('admin/dashboard');
      break;
    case 'doctor':
      redirect('doctor/dashboard');
      break;
    case 'nurse':
    case 'compounder':
      redirect('nurse/dashboard');
      break;
    case 'patient':
      redirect('patient/dashboard');
      break;
    default:
      redirect('dashboard');
  }
}

// Get user display name
function getUserDisplayName($userId = null)
{
  if ($userId === null) {
    $userId = getCurrentUserId();
  }

  if (!$userId) {
    return 'Guest';
  }

  $db = new Database();
  $db->query("SELECT name FROM users WHERE id = :user_id");
  $db->bind(':user_id', $userId);
  $user = $db->single();

  return $user ? $user->name : 'Unknown User';
}

// Get user profile image
function getUserProfileImage($userId = null)
{
  if ($userId === null) {
    $userId = getCurrentUserId();
  }

  if (!$userId) {
    return BASE_URL . 'public/images/default-avatar.png';
  }

  $db = new Database();
  $db->query("SELECT profile_image FROM users WHERE id = :user_id");
  $db->bind(':user_id', $userId);
  $user = $db->single();

  if ($user && $user->profile_image) {
    return BASE_URL . 'public/uploads/profiles/' . $user->profile_image;
  }

  return BASE_URL . 'public/images/default-avatar.png';
}

// Check if user is verified (for doctors and nurses)
function isUserVerified($userId = null)
{
  if ($userId === null) {
    $userId = getCurrentUserId();
  }

  if (!$userId) {
    return false;
  }

  $role = getCurrentUserRole();
  $db = new Database();

  if ($role === 'doctor') {
    $db->query("SELECT is_verified FROM doctors WHERE user_id = :user_id");
    $db->bind(':user_id', $userId);
    $result = $db->single();
    return $result && $result->is_verified;
  } elseif ($role === 'nurse' || $role === 'compounder') {
    $db->query("SELECT verification_status FROM nurses WHERE user_id = :user_id");
    $db->bind(':user_id', $userId);
    $result = $db->single();
    return $result && $result->verification_status === 'verified';
  }

  return true; // Patients and admins don't need verification
}

// Get user hospital
function getUserHospital($userId = null)
{
  if ($userId === null) {
    $userId = getCurrentUserId();
  }

  if (!$userId) {
    return null;
  }

  $role = getCurrentUserRole();
  $db = new Database();

  if ($role === 'doctor') {
    $db->query("SELECT h.* FROM hospitals h
                    JOIN doctor_hospitals dh ON h.id = dh.hospital_id
                    WHERE dh.doctor_id = (SELECT id FROM doctors WHERE user_id = :user_id)
                    AND dh.status = 'accepted'
                    LIMIT 1");
    $db->bind(':user_id', $userId);
    return $db->single();
  } elseif ($role === 'nurse' || $role === 'compounder') {
    $db->query("SELECT h.* FROM hospitals h
                    WHERE h.id = (SELECT verified_hospital_id FROM nurses WHERE user_id = :user_id)
                    LIMIT 1");
    $db->bind(':user_id', $userId);
    return $db->single();
  } elseif ($role === 'admin') {
    $db->query("SELECT h.* FROM hospitals h
                    JOIN hospital_admins ha ON h.id = ha.hospital_id
                    WHERE ha.admin_id = :user_id
                    LIMIT 1");
    $db->bind(':user_id', $userId);
    return $db->single();
  }

  return null;
}

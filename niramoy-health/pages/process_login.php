<?php
require_once '../includes/db.php';
require_once '../config.php';

// Initialize variables
$email = '';
$password = '';
$remember_me = '';
$errors = [];

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get form data
  $email = trim($_POST['email'] ?? '');
  $password = trim($_POST['password'] ?? '');
  $remember_me = isset($_POST['rememberMe']) ? true : false;

  // Validate email
  if (empty($email)) {
    $errors['email'] = $lang['email_required'];
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = $lang['invalid_email'];
  }

  // Validate password
  if (empty($password)) {
    $errors['password'] = $lang['password_required'];
  }

  // If no errors, attempt login
  if (empty($errors)) {
    // Create database connection
    $database = new Database();
    $db = $database->getConnection();

    // Prepare query to get user by email
    $query = "SELECT u.id, u.name, u.email, u.password, u.phone, u.address, 
                         u.date_of_birth, u.gender, u.blood_group, u.profile_image,
                         r.name as role_name
                  FROM users u
                  JOIN user_roles ur ON u.id = ur.user_id
                  JOIN roles r ON ur.role_id = r.id
                  WHERE u.email = :email";

    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if user exists and password is correct
    if ($user && password_verify($password, $user['password'])) {
      // Password is correct, start session
      session_regenerate_id(true);

      // Set session variables
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['name'] = $user['name'];
      $_SESSION['email'] = $user['email'];
      $_SESSION['phone'] = $user['phone'];
      $_SESSION['role'] = $user['role_name'];

      // If remember me is checked, set cookie
      if ($remember_me) {
        $token = bin2hex(random_bytes(32));
        $expiry = time() + (30 * 24 * 60 * 60); // 30 days

        // Store token in database
        $query = "INSERT INTO user_tokens (user_id, token, expiry) VALUES (:user_id, :token, :expiry)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $user['id']);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':expiry', date('Y-m-d H:i:s', $expiry));
        $stmt->execute();

        // Set cookie
        setcookie('remember_token', $token, $expiry, '/', '', true, true);
      }

      // Redirect based on user role
      switch ($user['role_name']) {
        case 'admin':
          header('Location: ../admin/dashboard.php');
          break;
        case 'doctor':
          header('Location: ../doctor/dashboard.php');
          break;
        case 'nurse':
          header('Location: ../nurse/dashboard.php');
          break;
        case 'patient':
          header('Location: ../patient/dashboard.php');
          break;
        case 'driver':
          header('Location: ../driver/dashboard.php');
          break;
        default:
          header('Location: ../index.php');
      }
      exit;
    } else {
      // Invalid credentials
      $errors['login'] = $lang['invalid_credentials'];
    }
  }
}

// If there are errors, redirect back to login page with errors
if (!empty($errors)) {
  $_SESSION['login_errors'] = $errors;
  $_SESSION['login_data'] = [
    'email' => $email,
    'remember_me' => $remember_me
  ];
  header('Location: login.php');
  exit;
}

<?php
require_once '../includes/db.php';
require_once '../config.php';

// Initialize variables
$name = '';
$email = '';
$phone = '';
$user_type = '';
$bmdc_code = '';
$password = '';
$confirm_password = '';
$date_of_birth = '';
$gender = '';
$blood_group = '';
$address = '';
$agree_terms = '';
$errors = [];

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get form data
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $phone = trim($_POST['phone'] ?? '');
  $user_type = trim($_POST['userType'] ?? '');
  $bmdc_code = trim($_POST['bmdcCode'] ?? '');
  $password = trim($_POST['password'] ?? '');
  $confirm_password = trim($_POST['confirmPassword'] ?? '');
  $date_of_birth = trim($_POST['dateOfBirth'] ?? '');
  $gender = trim($_POST['gender'] ?? '');
  $blood_group = trim($_POST['bloodGroup'] ?? '');
  $address = trim($_POST['address'] ?? '');
  $agree_terms = isset($_POST['agreeTerms']) ? true : false;

  // Validate name
  if (empty($name)) {
    $errors['name'] = $lang['name_required'];
  } elseif (strlen($name) < 3) {
    $errors['name'] = $lang['name_too_short'];
  }

  // Validate email
  if (empty($email)) {
    $errors['email'] = $lang['email_required'];
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = $lang['invalid_email'];
  }

  // Validate phone
  if (empty($phone)) {
    $errors['phone'] = $lang['phone_required'];
  }

  // Validate user type
  if (empty($user_type)) {
    $errors['userType'] = $lang['user_type_required'];
  } elseif (!in_array($user_type, ['patient', 'doctor', 'nurse', 'driver'])) {
    $errors['userType'] = $lang['invalid_user_type'];
  }

  // Validate BM&DC code for doctors
  if ($user_type === 'doctor' && empty($bmdc_code)) {
    $errors['bmdcCode'] = $lang['bmdc_code_required'];
  }

  // Validate password
  if (empty($password)) {
    $errors['password'] = $lang['password_required'];
  } elseif (strlen($password) < 8) {
    $errors['password'] = $lang['password_too_short'];
  } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
    $errors['password'] = $lang['password_weak'];
  }

  // Validate confirm password
  if (empty($confirm_password)) {
    $errors['confirmPassword'] = $lang['confirm_password_required'];
  } elseif ($password !== $confirm_password) {
    $errors['confirmPassword'] = $lang['password_mismatch'];
  }

  // Validate terms agreement
  if (!$agree_terms) {
    $errors['agreeTerms'] = $lang['terms_required'];
  }

  // If no errors, proceed with registration
  if (empty($errors)) {
    // Create database connection
    $database = new Database();
    $db = $database->getConnection();

    // Check if email already exists
    $query = "SELECT id FROM users WHERE email = :email";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      $errors['email'] = $lang['email_exists'];
    } else {
      // Begin transaction
      $db->beginTransaction();

      try {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user data
        $query = "INSERT INTO users (name, email, password, phone, address, date_of_birth, gender, blood_group) 
                          VALUES (:name, :email, :password, :phone, :address, :date_of_birth, :gender, :blood_group)";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':date_of_birth', $date_of_birth);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':blood_group', $blood_group);
        $stmt->execute();

        // Get user ID
        $user_id = $db->lastInsertId();

        // Get role ID based on user type
        $role_id = 0;
        switch ($user_type) {
          case 'patient':
            $role_id = 1;
            break;
          case 'doctor':
            $role_id = 2;
            break;
          case 'nurse':
            $role_id = 3;
            break;
          case 'driver':
            $role_id = 5;
            break;
        }

        // Assign role to user
        $query = "INSERT INTO user_roles (user_id, role_id) VALUES (:user_id, :role_id)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':role_id', $role_id);
        $stmt->execute();

        // If user is a doctor, insert doctor record
        if ($user_type === 'doctor') {
          // Check if BM&DC code is valid (this would typically involve an API call)
          $is_verified = 0; // Default to not verified
          $verification_document = null;

          // For demo purposes, we'll assume any BM&DC code starting with 'A-' is valid
          if (strpos($bmdc_code, 'A-') === 0) {
            $is_verified = 1;
          }

          $query = "INSERT INTO doctors (user_id, bmdc_code, is_verified, verification_document) 
                              VALUES (:user_id, :bmdc_code, :is_verified, :verification_document)";

          $stmt = $db->prepare($query);
          $stmt->bindParam(':user_id', $user_id);
          $stmt->bindParam(':bmdc_code', $bmdc_code);
          $stmt->bindParam(':is_verified', $is_verified);
          $stmt->bindParam(':verification_document', $verification_document);
          $stmt->execute();
        }

        // If user is a nurse, insert nurse record
        if ($user_type === 'nurse') {
          $query = "INSERT INTO nurses (user_id) VALUES (:user_id)";
          $stmt = $db->prepare($query);
          $stmt->bindParam(':user_id', $user_id);
          $stmt->execute();
        }

        // If user is a driver, insert driver record
        if ($user_type === 'driver') {
          $query = "INSERT INTO drivers (user_id) VALUES (:user_id)";
          $stmt = $db->prepare($query);
          $stmt->bindParam(':user_id', $user_id);
          $stmt->execute();
        }

        // Commit transaction
        $db->commit();

        // Set success message and redirect to login
        $_SESSION['registration_success'] = $lang['registration_success'];
        header('Location: login.php');
        exit;
      } catch (PDOException $e) {
        // Rollback transaction on error
        $db->rollBack();
        $errors['database'] = $lang['registration_error'];
      }
    }
  }
}

// If there are errors, redirect back to register page with errors
if (!empty($errors)) {
  $_SESSION['register_errors'] = $errors;
  $_SESSION['register_data'] = [
    'name' => $name,
    'email' => $email,
    'phone' => $phone,
    'user_type' => $user_type,
    'bmdc_code' => $bmdc_code,
    'date_of_birth' => $date_of_birth,
    'gender' => $gender,
    'blood_group' => $blood_group,
    'address' => $address
  ];
  header('Location: register.php');
  exit;
}

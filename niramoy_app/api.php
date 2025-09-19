<?php
require_once __DIR__ . '/inc/db.php';

$method = $_SERVER['REQUEST_METHOD'];
$path = isset($_GET['q']) ? rtrim($_GET['q'], '/') : '';
$parts = $path === '' ? [] : explode('/', $path);

// Simple routing
if (count($parts) === 0) {
  json_response(['message' => 'Niramoy API', 'routes' => ['/hospitals', '/doctors', '/users', '/appointments']]);
}

switch ($parts[0]) {
  case 'hospitals':
    if ($method === 'GET') {
      handle_get_hospitals();
    }
    break;
  case 'nearby_hospitals':
    if ($method === 'GET') {
      handle_get_nearby_hospitals();
    }
    break;
  case 'epidemic_alert':
    if ($method === 'GET') {
      handle_epidemic_alert();
    }
    break;
  case 'doctors':
    if ($method === 'GET') {
      handle_get_doctors();
    } elseif ($method === 'POST' && isset($parts[1]) && $parts[1] === 'rate') {
      handle_rate_doctor();
    } elseif ($method === 'POST' && isset($parts[1]) && $parts[1] === 'register') {
      handle_register_doctor();
    }
    break;
  case 'departments':
    if ($method === 'GET') {
      // /departments -> paginated derived departments
      if (!isset($parts[1])) handle_get_departments();
      // /departments/list -> full departments table (for registration dropdown)
      else if ($parts[1] === 'list') handle_get_departments_list();
    }
    break;
  case 'users':
    if ($method === 'GET') {
      handle_get_users();
    }
    // POST /users/upload -> profile image upload (multipart/form-data)
    elseif ($method === 'POST' && isset($parts[1]) && $parts[1] === 'upload') {
      handle_user_upload();
    }
    break;
  case 'appointments':
    if ($method === 'GET') {
      handle_get_appointments();
    } elseif ($method === 'POST') {
      handle_create_appointment();
    }
    break;
  case 'auth':
    if ($method === 'POST' && isset($parts[1]) && $parts[1] === 'register') {
      handle_auth_register();
    } elseif ($method === 'POST' && isset($parts[1]) && $parts[1] === 'login') {
      handle_auth_login();
    }
    break;
  default:
    json_response(['error' => 'Not found'], 404);
}

function handle_get_hospitals()
{
  $pdo = get_db();
  $stmt = $pdo->query('SELECT id, name, address, district, division, latitude, longitude, phone, hospital_type FROM hospitals WHERE is_active = 1');
  $data = $stmt->fetchAll();
  json_response(['hospitals' => $data]);
}

function handle_get_nearby_hospitals()
{
  $pdo = get_db();
  $lat = isset($_GET['lat']) ? (float)$_GET['lat'] : null;
  $lng = isset($_GET['lng']) ? (float)$_GET['lng'] : null;
  $radiusKm = isset($_GET['radius']) ? (float)$_GET['radius'] : 10.0;

  if ($lat === null || $lng === null) {
    json_response(['error' => 'lat and lng query params are required'], 400);
  }

  // Haversine formula in SQL to compute distances
  $sql = "SELECT id, name, address, district, division, latitude, longitude, phone, hospital_type,
        (3959 * acos(cos(radians(:lat)) * cos(radians(latitude)) * cos(radians(longitude) - radians(:lng)) + sin(radians(:lat)) * sin(radians(latitude)))) AS distance_miles
        FROM hospitals
        WHERE is_active = 1
        HAVING distance_miles <= :radius_miles
        ORDER BY distance_miles ASC";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':lat' => $lat,
    ':lng' => $lng,
    ':radius_miles' => $radiusKm * 0.621371
  ]);
  $rows = $stmt->fetchAll();
  json_response(['hospitals' => $rows]);
}

function handle_epidemic_alert()
{
  $pdo = get_db();
  $lat = isset($_GET['lat']) ? (float)$_GET['lat'] : null;
  $lng = isset($_GET['lng']) ? (float)$_GET['lng'] : null;
  $radiusKm = isset($_GET['radius']) ? (float)$_GET['radius'] : 10.0;
  $days = isset($_GET['days']) ? (int)$_GET['days'] : 14;

  if ($lat === null || $lng === null) {
    json_response(['error' => 'lat and lng query params are required'], 400);
  }

  // Find hospitals within radius
  $sqlHosp = "SELECT id, name, latitude, longitude FROM hospitals WHERE is_active = 1";
  $stmtHosp = $pdo->query($sqlHosp);
  $hospitals = $stmtHosp->fetchAll();

  $nearbyHospIds = [];
  foreach ($hospitals as $h) {
    if ($h['latitude'] === null || $h['longitude'] === null) continue;
    $d = haversine_distance($lat, $lng, (float)$h['latitude'], (float)$h['longitude']);
    if ($d <= $radiusKm) $nearbyHospIds[] = $h['id'];
  }

  if (count($nearbyHospIds) === 0) {
    json_response(['alert' => 'no_nearby_hospitals', 'cases' => 0, 'details' => []]);
  }

  // Count patient_diseases diagnosed in last $days linked to nearby hospitals via appointments
  $placeholders = implode(',', array_fill(0, count($nearbyHospIds), '?'));
  $sql = "SELECT pd.disease_id, d.name as disease_name, COUNT(*) as cases
            FROM patient_diseases pd
            JOIN diseases d ON pd.disease_id = d.id
            JOIN appointments a ON a.patient_id = pd.patient_id
            WHERE a.hospital_id IN ($placeholders)
              AND pd.diagnosis_date >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
            GROUP BY pd.disease_id
            ORDER BY cases DESC";

  $params = $nearbyHospIds;
  $params[] = $days;
  $stmt = $pdo->prepare($sql);
  $stmt->execute($params);
  $rows = $stmt->fetchAll();

  $totalCases = array_sum(array_column($rows, 'cases'));

  $level = 'low';
  if ($totalCases >= 50) $level = 'high';
  elseif ($totalCases >= 10) $level = 'medium';

  json_response(['alert' => $level, 'cases' => (int)$totalCases, 'details' => $rows]);
}

function haversine_distance($lat1, $lon1, $lat2, $lon2)
{
  $earthRadius = 6371; // km
  $dLat = deg2rad($lat2 - $lat1);
  $dLon = deg2rad($lon2 - $lon1);
  $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
  $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
  return $earthRadius * $c;
}

function handle_get_doctors()
{
  $pdo = get_db();
  $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
  $per_page = isset($_GET['per_page']) ? max(1, (int)$_GET['per_page']) : 10;
  $offset = ($page - 1) * $per_page;

  $where = ['d.is_verified = 1'];
  $params = [];

  // Free-text search across name, specialization, bmdc_code, and department
  if (!empty($_GET['q'])) {
    $where[] = '(u.name LIKE :q OR d.specialization LIKE :q OR d.bmdc_code LIKE :q OR dep.name LIKE :q)';
    $params[':q'] = '%' . $_GET['q'] . '%';
  }

  if (!empty($_GET['specialization'])) {
    $where[] = 'd.specialization LIKE :spec';
    $params[':spec'] = '%' . $_GET['specialization'] . '%';
  }

  if (!empty($_GET['hospital_id'])) {
    $where[] = 'dh.hospital_id = :hospital_id AND dh.status = "accepted"';
    $params[':hospital_id'] = (int)$_GET['hospital_id'];
  }

  $where_sql = '';
  if (count($where) > 0) $where_sql = 'WHERE ' . implode(' AND ', $where);

  // Count total (include department joins so search by department works)
  $countSql = "SELECT COUNT(DISTINCT d.id) as total FROM doctors d
    JOIN users u ON d.user_id = u.id
    LEFT JOIN doctor_hospitals dh ON dh.doctor_id = d.id
    LEFT JOIN doctor_departments dd ON dd.doctor_id = d.id
    LEFT JOIN departments dep ON dep.id = dd.department_id
    $where_sql";
  $countStmt = $pdo->prepare($countSql);
  $countStmt->execute($params);
  $total = (int)$countStmt->fetchColumn();

  $sql = "SELECT d.id, u.name AS name, d.specialization, d.bmdc_code, u.profile_image, d.consultation_fee, GROUP_CONCAT(DISTINCT dh.hospital_id) AS hospital_ids,
    COALESCE(ROUND(AVG(dr.rating),2), 0) AS avg_rating,
    GROUP_CONCAT(DISTINCT dep.name) AS departments
      FROM doctors d
      JOIN users u ON d.user_id = u.id
      LEFT JOIN doctor_hospitals dh ON dh.doctor_id = d.id
    LEFT JOIN doctor_ratings dr ON dr.doctor_id = d.id
    LEFT JOIN doctor_departments dd ON dd.doctor_id = d.id
    LEFT JOIN departments dep ON dep.id = dd.department_id
      $where_sql
      GROUP BY d.id
      ORDER BY d.id DESC
      LIMIT :limit OFFSET :offset";

  $stmt = $pdo->prepare($sql);
  foreach ($params as $k => $v) {
    // bind text params as strings
    if ($k === ':spec' || $k === ':q') $stmt->bindValue($k, $v, PDO::PARAM_STR);
    else $stmt->bindValue($k, $v, PDO::PARAM_INT);
  }
  $stmt->bindValue(':limit', (int)$per_page, PDO::PARAM_INT);
  $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
  $stmt->execute();
  $data = $stmt->fetchAll();

  $total_pages = $per_page > 0 ? (int)ceil($total / $per_page) : 1;

  json_response([
    'doctors' => $data,
    'total' => $total,
    'page' => $page,
    'per_page' => $per_page,
    'total_pages' => $total_pages
  ]);
}

function handle_rate_doctor()
{
  $pdo = get_db();
  $input = json_decode(file_get_contents('php://input'), true);
  if (empty($input['doctor_id']) || !isset($input['rating'])) {
    json_response(['error' => 'doctor_id and rating are required'], 400);
  }
  $doctor_id = (int)$input['doctor_id'];
  $rating = (int)$input['rating'];
  if ($rating < 1 || $rating > 5) json_response(['error' => 'rating must be 1-5'], 400);

  $sql = 'INSERT INTO doctor_ratings (doctor_id, user_id, rating, comment, created_at) VALUES (:doctor_id, :user_id, :rating, :comment, NOW())';
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':doctor_id' => $doctor_id,
    ':user_id' => isset($input['user_id']) ? (int)$input['user_id'] : null,
    ':rating' => $rating,
    ':comment' => isset($input['comment']) ? $input['comment'] : null
  ]);

  json_response(['message' => 'Rating submitted', 'id' => $pdo->lastInsertId()], 201);
}

function handle_get_departments()
{
  $pdo = get_db();
  $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
  $per_page = isset($_GET['per_page']) ? max(1, (int)$_GET['per_page']) : 10;
  $offset = ($page - 1) * $per_page;

  // Departments derived from doctor specializations
  $where = 'WHERE d.specialization IS NOT NULL AND d.specialization != ""';

  // Count total distinct departments
  $countSql = "SELECT COUNT(DISTINCT d.specialization) as total FROM doctors d $where";
  $countStmt = $pdo->query($countSql);
  $total = (int)$countStmt->fetchColumn();

  $sql = "SELECT d.specialization AS name, COUNT(*) AS doctor_count
          FROM doctors d
          $where
          GROUP BY d.specialization
          ORDER BY doctor_count DESC
          LIMIT :limit OFFSET :offset";

  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':limit', (int)$per_page, PDO::PARAM_INT);
  $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
  $stmt->execute();
  $rows = $stmt->fetchAll();

  $total_pages = $per_page > 0 ? (int)ceil($total / $per_page) : 1;

  json_response([
    'departments' => $rows,
    'total' => $total,
    'page' => $page,
    'per_page' => $per_page,
    'total_pages' => $total_pages
  ]);
}

function handle_get_users()
{
  $pdo = get_db();
  $stmt = $pdo->query('SELECT id, name, email, phone, date_of_birth, gender, blood_group FROM users');
  json_response(['users' => $stmt->fetchAll()]);
}

function handle_get_appointments()
{
  $pdo = get_db();
  $params = [];
  $sql = 'SELECT a.*, u.name as patient_name, du.name as doctor_name FROM appointments a JOIN users u ON a.patient_id = u.id JOIN doctors d ON a.doctor_id = d.id JOIN users du ON d.user_id = du.id';
  $stmt = $pdo->query($sql);
  json_response(['appointments' => $stmt->fetchAll()]);
}

function handle_create_appointment()
{
  $pdo = get_db();
  $input = json_decode(file_get_contents('php://input'), true);
  $required = ['patient_id', 'doctor_id', 'hospital_id', 'slot_id', 'appointment_date', 'appointment_time'];
  foreach ($required as $r) {
    if (empty($input[$r])) json_response(['error' => "$r is required"], 400);
  }
  $sql = 'INSERT INTO appointments (patient_id, doctor_id, hospital_id, slot_id, appointment_date, appointment_time, status, created_at, updated_at) VALUES (:patient_id,:doctor_id,:hospital_id,:slot_id,:appointment_date,:appointment_time, "pending", NOW(), NOW())';
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':patient_id' => $input['patient_id'],
    ':doctor_id' => $input['doctor_id'],
    ':hospital_id' => $input['hospital_id'],
    ':slot_id' => $input['slot_id'],
    ':appointment_date' => $input['appointment_date'],
    ':appointment_time' => $input['appointment_time']
  ]);
  json_response(['message' => 'Appointment created', 'id' => $pdo->lastInsertId()], 201);
}

function handle_get_departments_list()
{
  $pdo = get_db();
  $stmt = $pdo->query('SELECT id, name FROM departments ORDER BY name ASC');
  json_response(['departments' => $stmt->fetchAll()]);
}

function ensure_role_exists($roleName)
{
  $pdo = get_db();
  $stmt = $pdo->prepare('SELECT id FROM roles WHERE name = :name LIMIT 1');
  $stmt->execute([':name' => $roleName]);
  $r = $stmt->fetch();
  if ($r) return (int)$r['id'];
  $ins = $pdo->prepare('INSERT INTO roles (name, description, created_at) VALUES (:name, :desc, NOW())');
  $ins->execute([':name' => $roleName, ':desc' => $roleName . ' role']);
  return (int)$pdo->lastInsertId();
}

function handle_auth_register()
{
  $pdo = get_db();
  $input = json_decode(file_get_contents('php://input'), true);
  if (!$input) json_response(['error' => 'Invalid JSON'], 400);

  $role = isset($input['role']) ? $input['role'] : 'patient';
  $name = isset($input['name']) ? trim($input['name']) : null;
  $email = isset($input['email']) ? trim($input['email']) : null;
  $username = isset($input['username']) ? trim($input['username']) : null;
  $password = isset($input['password']) ? $input['password'] : null;
  $phone = isset($input['phone']) ? $input['phone'] : null;

  if (empty($username) || empty($password) || empty($name)) {
    json_response(['error' => 'name, username and password are required'], 400);
  }

  // Check username/email uniqueness
  $stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email OR name = :username LIMIT 1');
  $stmt->execute([':email' => $email, ':username' => $username]);
  if ($stmt->fetch()) json_response(['error' => 'User already exists with same username/email'], 400);

  // Create user
  $hashed = password_hash($password, PASSWORD_DEFAULT);
  $ins = $pdo->prepare('INSERT INTO users (name, email, password, phone, created_at) VALUES (:name, :email, :password, :phone, NOW())');
  $ins->execute([':name' => $username, ':email' => $email, ':password' => $hashed, ':phone' => $phone]);
  $user_id = (int)$pdo->lastInsertId();

  // Attach role
  $roleId = ensure_role_exists($role);
  $rins = $pdo->prepare('INSERT INTO user_roles (user_id, role_id, created_at) VALUES (:user_id, :role_id, NOW())');
  $rins->execute([':user_id' => $user_id, ':role_id' => $roleId]);

  // If registering as doctor, create doctors record and accept BM&DC + department mapping
  if ($role === 'doctor') {
    $bmdc = isset($input['bmdc_code']) ? trim($input['bmdc_code']) : null;
    $department_id = isset($input['department_id']) ? (int)$input['department_id'] : null;
    $specialization = isset($input['specialization']) ? trim($input['specialization']) : null;

    $dins = $pdo->prepare('INSERT INTO doctors (user_id, bmdc_code, specialization, created_at, updated_at) VALUES (:user_id, :bmdc, :spec, NOW(), NOW())');
    $dins->execute([':user_id' => $user_id, ':bmdc' => $bmdc, ':spec' => $specialization]);
    $doctor_id = (int)$pdo->lastInsertId();

    if ($department_id) {
      $m = $pdo->prepare('INSERT INTO doctor_departments (doctor_id, department_id, created_at) VALUES (:doctor_id, :department_id) ON DUPLICATE KEY UPDATE department_id = VALUES(department_id)');
      $m->execute([':doctor_id' => $doctor_id, ':department_id' => $department_id]);
    }
  }

  json_response(['message' => 'registered', 'user_id' => $user_id], 201);
}

function handle_auth_login()
{
  $pdo = get_db();
  $input = json_decode(file_get_contents('php://input'), true);
  if (!$input) json_response(['error' => 'Invalid JSON'], 400);

  $username = isset($input['username']) ? trim($input['username']) : null;
  $password = isset($input['password']) ? $input['password'] : null;

  if (!$username || !$password) json_response(['error' => 'username and password required'], 400);

  $stmt = $pdo->prepare('SELECT id, name, email, password, profile_image FROM users WHERE name = :username OR email = :username LIMIT 1');
  $stmt->execute([':username' => $username]);
  $u = $stmt->fetch();
  if (!$u) json_response(['error' => 'invalid credentials'], 401);

  if (!password_verify($password, $u['password'])) json_response(['error' => 'invalid credentials'], 401);

  // Fetch role(s)
  $rstmt = $pdo->prepare('SELECT r.name FROM user_roles ur JOIN roles r ON ur.role_id = r.id WHERE ur.user_id = :uid');
  $rstmt->execute([':uid' => $u['id']]);
  $roles = array_column($rstmt->fetchAll(), 'name');

  // Start session and store minimal user info for header visibility
  if (session_status() === PHP_SESSION_NONE) session_start();
  $_SESSION['user'] = [
    'id' => (int)$u['id'],
    'name' => $u['name'],
    'email' => $u['email'],
    'roles' => $roles,
    'profile_image' => isset($u['profile_image']) ? $u['profile_image'] : null
  ];

  json_response(['message' => 'ok', 'user' => $_SESSION['user']]);
}

function handle_user_upload()
{
  $pdo = get_db();

  // Expect multipart form-data with user_id, password, and profile_image file
  if (empty($_POST['user_id']) || empty($_POST['password']) || empty($_FILES['profile_image'])) {
    json_response(['error' => 'user_id, password and profile_image are required'], 400);
  }

  $user_id = (int)$_POST['user_id'];
  $password = $_POST['password'];

  // Fetch user
  $stmt = $pdo->prepare('SELECT id, password FROM users WHERE id = :id LIMIT 1');
  $stmt->execute([':id' => $user_id]);
  $u = $stmt->fetch();
  if (!$u) json_response(['error' => 'user not found'], 404);

  if (!password_verify($password, $u['password'])) json_response(['error' => 'invalid credentials'], 401);

  $file = $_FILES['profile_image'];
  if ($file['error'] !== UPLOAD_ERR_OK) json_response(['error' => 'upload error'], 400);

  // Validate file type (allow jpg, png, webp)
  $finfo = new finfo(FILEINFO_MIME_TYPE);
  $mime = $finfo->file($file['tmp_name']);
  $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
  if (!isset($allowed[$mime])) json_response(['error' => 'invalid file type'], 400);

  // Limit size to 5MB
  if ($file['size'] > 5 * 1024 * 1024) json_response(['error' => 'file too large'], 400);

  $ext = $allowed[$mime];
  $uploadsDir = __DIR__ . '/uploads/profiles';
  if (!is_dir($uploadsDir)) mkdir($uploadsDir, 0755, true);

  $filename = sprintf('%s_%s.%s', $user_id, time(), $ext);
  $dest = $uploadsDir . '/' . $filename;

  if (!move_uploaded_file($file['tmp_name'], $dest)) json_response(['error' => 'could not save file'], 500);

  // Save relative path to DB
  $relative = 'uploads/profiles/' . $filename;
  $ustmt = $pdo->prepare('UPDATE users SET profile_image = :p, updated_at = NOW() WHERE id = :id');
  $ustmt->execute([':p' => $relative, ':id' => $user_id]);

  json_response(['message' => 'uploaded', 'path' => $relative]);
}

function handle_register_doctor()
{
  $pdo = get_db();
  $input = json_decode(file_get_contents('php://input'), true);
  if (!$input) json_response(['error' => 'Invalid JSON'], 400);

  // Expect either existing user_id, or user info (name,email,phone)
  $user_id = isset($input['user_id']) ? (int)$input['user_id'] : null;
  $bmdc_code = isset($input['bmdc_code']) ? trim($input['bmdc_code']) : null;
  $department_id = isset($input['department_id']) ? (int)$input['department_id'] : null;
  $specialization = isset($input['specialization']) ? trim($input['specialization']) : null;

  if (empty($user_id) && empty($input['name'])) json_response(['error' => 'user_id or name is required'], 400);

  // If user_id not provided, create user
  if (empty($user_id)) {
    $sql = 'INSERT INTO users (name, email, phone, created_at) VALUES (:name, :email, :phone, NOW())';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ':name' => $input['name'],
      ':email' => isset($input['email']) ? $input['email'] : null,
      ':phone' => isset($input['phone']) ? $input['phone'] : null
    ]);
    $user_id = (int)$pdo->lastInsertId();
  }

  // Create or update doctor record
  // If a doctor row for this user exists, update; otherwise insert
  $stmt = $pdo->prepare('SELECT id FROM doctors WHERE user_id = :user_id LIMIT 1');
  $stmt->execute([':user_id' => $user_id]);
  $doc = $stmt->fetch();

  if ($doc) {
    $doctor_id = (int)$doc['id'];
    $updateSql = 'UPDATE doctors SET bmdc_code = :bmdc, specialization = :spec, updated_at = NOW() WHERE id = :id';
    $uStmt = $pdo->prepare($updateSql);
    $uStmt->execute([':bmdc' => $bmdc_code, ':spec' => $specialization, ':id' => $doctor_id]);
  } else {
    $insSql = 'INSERT INTO doctors (user_id, bmdc_code, specialization, created_at, updated_at) VALUES (:user_id, :bmdc, :spec, NOW(), NOW())';
    $iStmt = $pdo->prepare($insSql);
    $iStmt->execute([':user_id' => $user_id, ':bmdc' => $bmdc_code, ':spec' => $specialization]);
    $doctor_id = (int)$pdo->lastInsertId();
  }

  // Map doctor -> department. Enforce unique doctor_id mapping in doctor_departments.
  if (!empty($department_id)) {
    // Upsert style: try insert, on duplicate update
    $sql = 'INSERT INTO doctor_departments (doctor_id, department_id, created_at) VALUES (:doctor_id, :department_id, NOW()) ON DUPLICATE KEY UPDATE department_id = VALUES(department_id)';
    $stmt = $pdo->prepare($sql);
    try {
      $stmt->execute([':doctor_id' => $doctor_id, ':department_id' => $department_id]);
    } catch (PDOException $e) {
      json_response(['error' => 'Could not assign department: ' . $e->getMessage()], 500);
    }
  }

  json_response(['message' => 'Doctor registered', 'doctor_id' => $doctor_id, 'user_id' => $user_id], 201);
}

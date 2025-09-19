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
  case 'driver':
    if (!isset($parts[1])) {
      json_response(['error' => 'Driver endpoint not specified'], 400);
    }
    $driverEndpoint = $parts[1];

    switch ($driverEndpoint) {
      case 'trips':
        if (isset($parts[2]) && $parts[2] === 'active') {
          handle_driver_active_trips();
        } elseif (isset($parts[2]) && $parts[2] === 'completed') {
          handle_driver_completed_trips();
        } elseif (isset($parts[2]) && $parts[2] === 'history') {
          handle_driver_trip_history();
        }
        break;
      case 'emergency_requests':
        if (isset($parts[2]) && $parts[2] === 'accept') {
          handle_accept_emergency_request();
        } else {
          handle_driver_emergency_requests();
        }
        break;
      case 'vehicle_status':
        handle_driver_vehicle_status();
        break;
      case 'earnings':
        handle_driver_earnings();
        break;
      case 'status':
        if ($method === 'POST') {
          handle_driver_status_update();
        }
        break;
      default:
        json_response(['error' => 'Driver endpoint not found'], 404);
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
  case 'patients':
    if ($method === 'GET') {
      // /patients -> list
      if (!isset($parts[1])) handle_get_patients();
      // /patients/get?id= -> single patient
      else if ($parts[1] === 'get') handle_get_patient();
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
      $userType = $parts[2] ?? null;

      if (!$userType) {
        json_response(['error' => 'User type not specified. Please provide a valid user type in the URL (e.g., /auth/login/driver).'], 400);
      }

      $validUserTypes = ['patient', 'doctor', 'nurse', 'driver', 'hospital_admin', 'compounder'];
      if (!in_array($userType, $validUserTypes)) {
        json_response(['error' => 'Invalid user type. Supported types: ' . implode(', ', $validUserTypes) . '.'], 400);
      }

      switch ($userType) {
        case 'patient':
          handle_patient_login();
          break;
        case 'doctor':
          handle_doctor_login();
          break;
        case 'nurse':
        case 'compounder': // Treat nurse and compounder as the same role
          handle_nurse_login();
          break;
        case 'driver':
          handle_driver_login();
          break;
        case 'hospital_admin':
          handle_hospital_admin_login();
          break;
      }
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

// List patients (with optional pagination and search)
function handle_get_patients()
{
  $pdo = get_db();
  $q = isset($_GET['q']) ? trim($_GET['q']) : '';
  $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
  $perPage = isset($_GET['per_page']) ? max(1, (int)$_GET['per_page']) : 20;
  $offset = ($page - 1) * $perPage;

  if ($q !== '') {
    $stmt = $pdo->prepare('SELECT id, name, email, phone, gender, date_of_birth, created_at FROM users WHERE (name LIKE :q OR email LIKE :q) AND id IN (SELECT user_id FROM user_roles WHERE role_id = (SELECT id FROM roles WHERE name = "patient")) LIMIT :limit OFFSET :offset');
    $stmt->bindValue(':q', "%$q%", PDO::PARAM_STR);
  } else {
    $stmt = $pdo->prepare('SELECT id, name, email, phone, gender, date_of_birth, created_at FROM users WHERE id IN (SELECT user_id FROM user_roles WHERE role_id = (SELECT id FROM roles WHERE name = "patient")) LIMIT :limit OFFSET :offset');
  }
  $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
  $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
  $stmt->execute();
  $rows = $stmt->fetchAll();

  json_response(['patients' => $rows, 'page' => $page, 'per_page' => $perPage]);
}

// Get single patient details by id
function handle_get_patient()
{
  $pdo = get_db();
  $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
  if ($id <= 0) json_response(['error' => 'id required'], 400);

  $stmt = $pdo->prepare('SELECT id, name, email, phone, gender, date_of_birth, created_at FROM users WHERE id = :id LIMIT 1');
  $stmt->execute([':id' => $id]);
  $user = $stmt->fetch();
  if (!$user) json_response(['error' => 'not found'], 404);

  // Fetch basic health records (example)
  $rstmt = $pdo->prepare('SELECT id, type, summary, created_at FROM health_records WHERE patient_id = :pid ORDER BY created_at DESC LIMIT 20');
  $rstmt->execute([':pid' => $id]);
  $records = $rstmt->fetchAll();

  json_response(['patient' => $user, 'records' => $records]);
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

function handle_auth_register()
{
  try {
    $pdo = get_db();
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) json_response(['error' => 'Invalid JSON'], 400);

    $role = isset($input['role']) ? $input['role'] : 'patient';
    $name = isset($input['name']) ? trim($input['name']) : null; // full name (optional)
    $email = isset($input['email']) ? trim($input['email']) : null;
    $username = isset($input['username']) ? trim($input['username']) : null; // the actual login username
    $password = isset($input['password']) ? $input['password'] : null;
    $phone = isset($input['phone']) ? $input['phone'] : null;

    // Require username and password. Full name is optional.
    if (empty($username) || empty($password)) {
      json_response(['error' => 'username and password are required'], 400);
    }

    // Check username uniqueness
    $stmt = $pdo->prepare('SELECT id FROM users WHERE name = :username LIMIT 1');
    $stmt->execute([':username' => $username]);
    if ($stmt->fetch()) json_response(['error' => 'Username already exists'], 400);

    // Check email uniqueness if provided
    if (!empty($email)) {
      $stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
      $stmt->execute([':email' => $email]);
      if ($stmt->fetch()) json_response(['error' => 'Email already exists'], 400);
    }

    // Create user. Store login 'username' into users.name so login works with username.
    $dbName = $username; // use username as the users.name column
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $ins = $pdo->prepare('INSERT INTO users (name, email, password, phone, created_at) VALUES (:name, :email, :password, :phone, NOW())');
    $ins->execute([':name' => $dbName, ':email' => $email, ':password' => $hashed, ':phone' => $phone]);
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
        $m = $pdo->prepare('INSERT INTO doctor_departments (doctor_id, department_id, created_at) VALUES (:doctor_id, :department_id, NOW()) ON DUPLICATE KEY UPDATE department_id = VALUES(department_id)');
        $m->execute([':doctor_id' => $doctor_id, ':department_id' => $department_id]);
      }
    }

    // If registering as driver, create driver record
    if ($role === 'driver') {
      $license_number = isset($input['license_number']) ? trim($input['license_number']) : null;
      $vehicle_id = isset($input['vehicle_id']) ? (int)$input['vehicle_id'] : null;

      $dins = $pdo->prepare('INSERT INTO drivers (user_id, license_number, vehicle_id, created_at, updated_at) VALUES (:user_id, :license_number, :vehicle_id, NOW(), NOW())');
      $dins->execute([':user_id' => $user_id, ':license_number' => $license_number, ':vehicle_id' => $vehicle_id]);
      $driver_id = (int)$pdo->lastInsertId();
    }

    // If registering as nurse, create nurse record
    if ($role === 'nurse') {
      $license_number = isset($input['license_number']) ? trim($input['license_number']) : null;
      $is_daycare = isset($input['is_daycare']) ? (int)$input['is_daycare'] : 0;
      $is_compounder = isset($input['is_compounder']) ? (int)$input['is_compounder'] : 0;
      $specialization = isset($input['specialization']) ? trim($input['specialization']) : null;

      // NOTE: `nurses` table in this schema does not have a `department_id` column.
      $nins = $pdo->prepare('INSERT INTO nurses (user_id, license_number, is_daycare, is_compounder, specialization, created_at, updated_at) VALUES (:user_id, :license_number, :is_daycare, :is_compounder, :specialization, NOW(), NOW())');
      $nins->execute([
        ':user_id' => $user_id,
        ':license_number' => $license_number,
        ':is_daycare' => $is_daycare,
        ':is_compounder' => $is_compounder,
        ':specialization' => $specialization
      ]);
      $nurse_id = (int)$pdo->lastInsertId();
    }

    // If registering as compounder, create nurse record with is_compounder=1
    if ($role === 'compounder') {
      $license_number = isset($input['license_number']) ? trim($input['license_number']) : null;
      $specialization = isset($input['specialization']) ? trim($input['specialization']) : null;

      $nins = $pdo->prepare('INSERT INTO nurses (user_id, license_number, is_compounder, specialization, created_at, updated_at) VALUES (:user_id, :license_number, 1, :specialization, NOW(), NOW())');
      $nins->execute([
        ':user_id' => $user_id,
        ':license_number' => $license_number,
        ':specialization' => $specialization
      ]);
      $nurse_id = (int)$pdo->lastInsertId();
    }

    // If registering as hospital_admin, create hospital_admin record
    if ($role === 'hospital_admin') {
      $hospital_id = isset($input['hospital_id']) ? (int)$input['hospital_id'] : null;

      // The `hospital_admins` table uses `admin_id` (not `user_id`) for the user reference in this schema.
      // Schema: (id, hospital_id, admin_id, created_at)
      $hins = $pdo->prepare('INSERT INTO hospital_admins (hospital_id, admin_id, created_at) VALUES (:hospital_id, :admin_id, NOW()) ON DUPLICATE KEY UPDATE created_at = created_at');
      $hins->execute([':hospital_id' => $hospital_id, ':admin_id' => $user_id]);
      $admin_id = (int)$pdo->lastInsertId();
    }

    json_response(['message' => 'registered', 'user_id' => $user_id], 201);
  } catch (PDOException $e) {
    error_log("Registration error: " . $e->getMessage());
    json_response(['error' => 'Registration failed: ' . $e->getMessage()], 500);
  }
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

// Patient Login Handler
function handle_patient_login()
{
  $pdo = get_db();
  $input = json_decode(file_get_contents('php://input'), true);
  if (!$input) json_response(['error' => 'Invalid JSON'], 400);

  $username = isset($input['username']) ? trim($input['username']) : null;
  $password = isset($input['password']) ? $input['password'] : null;

  if (!$username || !$password) {
    json_response(['error' => 'username and password required'], 400);
  }

  $stmt = $pdo->prepare('SELECT id, name, email, password, profile_image FROM users WHERE name = :username OR email = :username LIMIT 1');
  $stmt->execute([':username' => $username]);
  $u = $stmt->fetch();
  if (!$u) json_response(['error' => 'invalid credentials'], 401);

  if (!password_verify($password, $u['password'])) json_response(['error' => 'invalid credentials'], 401);

  // Check if user has patient role
  $rstmt = $pdo->prepare('SELECT r.name FROM user_roles ur JOIN roles r ON ur.role_id = r.id WHERE ur.user_id = :uid AND r.name = "patient"');
  $rstmt->execute([':uid' => $u['id']]);
  $role = $rstmt->fetchColumn();
  if (!$role) json_response(['error' => 'You are not registered as a patient'], 403);

  // Fetch all roles
  $rstmt = $pdo->prepare('SELECT r.name FROM user_roles ur JOIN roles r ON ur.role_id = r.id WHERE ur.user_id = :uid');
  $rstmt->execute([':uid' => $u['id']]);
  $roles = array_column($rstmt->fetchAll(), 'name');

  // Start session
  if (session_status() === PHP_SESSION_NONE) session_start();
  session_regenerate_id(true);

  $_SESSION['user'] = [
    'id' => (int)$u['id'],
    'name' => $u['name'],
    'email' => $u['email'],
    'roles' => $roles,
    'primary_role' => 'patient',
    'profile_image' => isset($u['profile_image']) ? $u['profile_image'] : null
  ];

  json_response(['message' => 'ok', 'user' => $_SESSION['user'], 'redirect' => 'patient_dashboard.php']);
}

// Doctor Login Handler
function handle_doctor_login()
{
  $pdo = get_db();
  $input = json_decode(file_get_contents('php://input'), true);
  if (!$input) json_response(['error' => 'Invalid JSON'], 400);

  $username = isset($input['username']) ? trim($input['username']) : null;
  $password = isset($input['password']) ? $input['password'] : null;

  if (!$username || !$password) {
    json_response(['error' => 'username and password required'], 400);
  }

  $stmt = $pdo->prepare('SELECT id, name, email, password, profile_image FROM users WHERE name = :username OR email = :username LIMIT 1');
  $stmt->execute([':username' => $username]);
  $u = $stmt->fetch();
  if (!$u) json_response(['error' => 'invalid credentials'], 401);

  if (!password_verify($password, $u['password'])) json_response(['error' => 'invalid credentials'], 401);

  // Check if user has doctor role
  $rstmt = $pdo->prepare('SELECT r.name FROM user_roles ur JOIN roles r ON ur.role_id = r.id WHERE ur.user_id = :uid AND r.name = "doctor"');
  $rstmt->execute([':uid' => $u['id']]);
  $role = $rstmt->fetchColumn();
  if (!$role) json_response(['error' => 'You are not registered as a doctor'], 403);

  // Fetch doctor verification status (allow login even if not verified)
  $dstmt = $pdo->prepare('SELECT verification_status FROM doctors WHERE user_id = :uid');
  $dstmt->execute([':uid' => $u['id']]);
  $doctor = $dstmt->fetch();
  if (!$doctor) {
    json_response(['error' => 'Doctor record not found'], 403);
  }
  $is_verified = isset($doctor['verification_status']) && $doctor['verification_status'] === 'verified';

  // Fetch all roles
  $rstmt = $pdo->prepare('SELECT r.name FROM user_roles ur JOIN roles r ON ur.role_id = r.id WHERE ur.user_id = :uid');
  $rstmt->execute([':uid' => $u['id']]);
  $roles = array_column($rstmt->fetchAll(), 'name');

  // Start session
  if (session_status() === PHP_SESSION_NONE) session_start();
  session_regenerate_id(true);

  $_SESSION['user'] = [
    'id' => (int)$u['id'],
    'name' => $u['name'],
    'email' => $u['email'],
    'roles' => $roles,
    'primary_role' => 'doctor',
    'is_verified' => $is_verified,
    'verification_status' => $doctor['verification_status'] ?? null,
    'profile_image' => isset($u['profile_image']) ? $u['profile_image'] : null
  ];

  json_response(['message' => 'ok', 'user' => $_SESSION['user'], 'redirect' => 'doctor_dashboard.php']);
}

// Nurse Login Handler
function handle_nurse_login()
{
  $pdo = get_db();
  $input = json_decode(file_get_contents('php://input'), true);
  if (!$input) json_response(['error' => 'Invalid JSON'], 400);

  $username = isset($input['username']) ? trim($input['username']) : null;
  $password = isset($input['password']) ? $input['password'] : null;

  if (!$username || !$password) {
    json_response(['error' => 'username and password required'], 400);
  }

  $stmt = $pdo->prepare('SELECT id, name, email, password, profile_image FROM users WHERE name = :username OR email = :username LIMIT 1');
  $stmt->execute([':username' => $username]);
  $u = $stmt->fetch();
  if (!$u) json_response(['error' => 'invalid credentials'], 401);

  if (!password_verify($password, $u['password'])) json_response(['error' => 'invalid credentials'], 401);

  // Check if user has nurse role
  $rstmt = $pdo->prepare('SELECT r.name FROM user_roles ur JOIN roles r ON ur.role_id = r.id WHERE ur.user_id = :uid AND r.name = "nurse"');
  $rstmt->execute([':uid' => $u['id']]);
  $role = $rstmt->fetchColumn();
  if (!$role) json_response(['error' => 'You are not registered as a nurse'], 403);

  // Fetch nurse verification status (allow login even if not verified)
  $nstmt = $pdo->prepare('SELECT verification_status FROM nurses WHERE user_id = :uid');
  $nstmt->execute([':uid' => $u['id']]);
  $nurse = $nstmt->fetch();
  if (!$nurse) {
    json_response(['error' => 'Nurse record not found'], 403);
  }
  $is_verified = isset($nurse['verification_status']) && $nurse['verification_status'] === 'verified';

  // Fetch all roles
  $rstmt = $pdo->prepare('SELECT r.name FROM user_roles ur JOIN roles r ON ur.role_id = r.id WHERE ur.user_id = :uid');
  $rstmt->execute([':uid' => $u['id']]);
  $roles = array_column($rstmt->fetchAll(), 'name');

  // Start session
  if (session_status() === PHP_SESSION_NONE) session_start();
  session_regenerate_id(true);

  $_SESSION['user'] = [
    'id' => (int)$u['id'],
    'name' => $u['name'],
    'email' => $u['email'],
    'roles' => $roles,
    'primary_role' => 'nurse',
    'is_verified' => $is_verified,
    'verification_status' => $nurse['verification_status'] ?? null,
    'profile_image' => isset($u['profile_image']) ? $u['profile_image'] : null
  ];

  json_response(['message' => 'ok', 'user' => $_SESSION['user'], 'redirect' => 'nurse_dashboard.php']);
}

// Driver Login Handler
function handle_driver_login()
{
  $pdo = get_db();
  $input = json_decode(file_get_contents('php://input'), true);
  if (!$input) json_response(['error' => 'Invalid JSON'], 400);

  $username = isset($input['username']) ? trim($input['username']) : null;
  $password = isset($input['password']) ? $input['password'] : null;

  if (!$username || !$password) {
    json_response(['error' => 'username and password required'], 400);
  }

  $stmt = $pdo->prepare('SELECT id, name, email, password, profile_image FROM users WHERE name = :username OR email = :username LIMIT 1');
  $stmt->execute([':username' => $username]);
  $u = $stmt->fetch();
  if (!$u) json_response(['error' => 'invalid credentials'], 401);

  if (!password_verify($password, $u['password'])) json_response(['error' => 'invalid credentials'], 401);

  // Check if user has driver role
  $rstmt = $pdo->prepare('SELECT r.name FROM user_roles ur JOIN roles r ON ur.role_id = r.id WHERE ur.user_id = :uid AND r.name = "driver"');
  $rstmt->execute([':uid' => $u['id']]);
  $role = $rstmt->fetchColumn();
  if (!$role) json_response(['error' => 'You are not registered as a driver'], 403);

  // Fetch all roles
  $rstmt = $pdo->prepare('SELECT r.name FROM user_roles ur JOIN roles r ON ur.role_id = r.id WHERE ur.user_id = :uid');
  $rstmt->execute([':uid' => $u['id']]);
  $roles = array_column($rstmt->fetchAll(), 'name');

  // Start session
  if (session_status() === PHP_SESSION_NONE) session_start();
  session_regenerate_id(true);

  $_SESSION['user'] = [
    'id' => (int)$u['id'],
    'name' => $u['name'],
    'email' => $u['email'],
    'roles' => $roles,
    'primary_role' => 'driver',
    'profile_image' => isset($u['profile_image']) ? $u['profile_image'] : null
  ];

  json_response(['message' => 'ok', 'user' => $_SESSION['user'], 'redirect' => 'driver_dashboard.php']);
}

// Hospital Admin Login Handler
function handle_hospital_admin_login()
{
  $pdo = get_db();
  $input = json_decode(file_get_contents('php://input'), true);
  if (!$input) json_response(['error' => 'Invalid JSON'], 400);

  $username = isset($input['username']) ? trim($input['username']) : null;
  $password = isset($input['password']) ? $input['password'] : null;

  if (!$username || !$password) {
    json_response(['error' => 'username and password required'], 400);
  }

  $stmt = $pdo->prepare('SELECT id, name, email, password, profile_image FROM users WHERE name = :username OR email = :username LIMIT 1');
  $stmt->execute([':username' => $username]);
  $u = $stmt->fetch();
  if (!$u) json_response(['error' => 'invalid credentials'], 401);

  if (!password_verify($password, $u['password'])) json_response(['error' => 'invalid credentials'], 401);

  // Check if user has hospital_admin role
  $rstmt = $pdo->prepare('SELECT r.name FROM user_roles ur JOIN roles r ON ur.role_id = r.id WHERE ur.user_id = :uid AND r.name = "hospital_admin"');
  $rstmt->execute([':uid' => $u['id']]);
  $role = $rstmt->fetchColumn();
  if (!$role) json_response(['error' => 'You are not registered as a hospital admin'], 403);

  // Fetch all roles
  $rstmt = $pdo->prepare('SELECT r.name FROM user_roles ur JOIN roles r ON ur.role_id = r.id WHERE ur.user_id = :uid');
  $rstmt->execute([':uid' => $u['id']]);
  $roles = array_column($rstmt->fetchAll(), 'name');

  // Start session
  if (session_status() === PHP_SESSION_NONE) session_start();
  session_regenerate_id(true);

  $_SESSION['user'] = [
    'id' => (int)$u['id'],
    'name' => $u['name'],
    'email' => $u['email'],
    'roles' => $roles,
    'primary_role' => 'hospital_admin',
    'profile_image' => isset($u['profile_image']) ? $u['profile_image'] : null
  ];

  json_response(['message' => 'ok', 'user' => $_SESSION['user'], 'redirect' => 'hospital_admin.php']);
}

// Compounder Login Handler
function handle_compounder_login()
{
  $pdo = get_db();
  $input = json_decode(file_get_contents('php://input'), true);
  if (!$input) json_response(['error' => 'Invalid JSON'], 400);

  $username = isset($input['username']) ? trim($input['username']) : null;
  $password = isset($input['password']) ? $input['password'] : null;

  if (!$username || !$password) {
    json_response(['error' => 'username and password required'], 400);
  }

  $stmt = $pdo->prepare('SELECT id, name, email, password, profile_image FROM users WHERE name = :username OR email = :username LIMIT 1');
  $stmt->execute([':username' => $username]);
  $u = $stmt->fetch();
  if (!$u) json_response(['error' => 'invalid credentials'], 401);

  if (!password_verify($password, $u['password'])) json_response(['error' => 'invalid credentials'], 401);

  // Fetch all roles
  $rstmt = $pdo->prepare('SELECT r.name FROM user_roles ur JOIN roles r ON ur.role_id = r.id WHERE ur.user_id = :uid');
  $rstmt->execute([':uid' => $u['id']]);
  $roles = array_column($rstmt->fetchAll(), 'name');

  // Check if user is a nurse or compounder
  $is_compounder_or_nurse = in_array('compounder', $roles) || in_array('nurse', $roles);

  if ($is_compounder_or_nurse) {
    $nstmt = $pdo->prepare('SELECT verification_status, is_compounder FROM nurses WHERE user_id = :uid');
    $nstmt->execute([':uid' => $u['id']]);
    $nurse = $nstmt->fetch();

    if (!$nurse || !$nurse['is_compounder']) {
      json_response(['error' => 'Your compounder/nurse account is not properly set up'], 403);
    }

    // Allow compounder to login even if not verified; expose verification status
    $is_verified = isset($nurse['verification_status']) && $nurse['verification_status'] === 'verified';
    $primary_role = 'compounder';
  } else {
    $primary_role = $roles[0] ?? 'user';
  }

  // Start session
  if (session_status() === PHP_SESSION_NONE) session_start();
  session_regenerate_id(true);

  $_SESSION['user'] = [
    'id' => (int)$u['id'],
    'name' => $u['name'],
    'email' => $u['email'],
    'roles' => $roles,
    'primary_role' => $primary_role,
    'profile_image' => isset($u['profile_image']) ? $u['profile_image'] : null
  ];

  json_response(['message' => 'ok', 'user' => $_SESSION['user'], 'redirect' => $primary_role . '.php']);
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

  // If user_id not provided, create user. Require username and password for login.
  if (empty($user_id)) {
    $username = isset($input['username']) ? trim($input['username']) : null;
    $password = isset($input['password']) ? $input['password'] : null;
    $email = isset($input['email']) ? trim($input['email']) : null;
    $phone = isset($input['phone']) ? $input['phone'] : null;

    if (empty($username) || empty($password)) {
      json_response(['error' => 'username and password are required when creating a new user'], 400);
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $sql = 'INSERT INTO users (name, email, password, phone, created_at) VALUES (:name, :email, :password, :phone, NOW())';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ':name' => $username,
      ':email' => $email,
      ':password' => $hashed,
      ':phone' => $phone
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

// Driver trips functions
function handle_driver_active_trips()
{
  $pdo = get_db();

  // In a real application, you would get the driver ID from the session
  // For now, we'll use a hardcoded ID
  $driver_id = 1; // This should come from the session

  $stmt = $pdo->prepare('SELECT COUNT(*) as count FROM driver_trips WHERE driver_id = ? AND status IN ("accepted", "in_progress")');
  $stmt->execute([$driver_id]);
  $result = $stmt->fetch();

  json_response(['count' => (int)$result['count']]);
}

function handle_driver_completed_trips()
{
  $pdo = get_db();

  // In a real application, you would get the driver ID from the session
  // For now, we'll use a hardcoded ID
  $driver_id = 1; // This should come from the session

  $stmt = $pdo->prepare('SELECT COUNT(*) as count FROM driver_trips WHERE driver_id = ? AND status = "completed"');
  $stmt->execute([$driver_id]);
  $result = $stmt->fetch();

  json_response(['count' => (int)$result['count']]);
}

function handle_driver_trip_history()
{
  $pdo = get_db();

  // In a real application, you would get the driver ID from the session
  // For now, we'll use a hardcoded ID
  $driver_id = 1; // This should come from the session

  $stmt = $pdo->prepare('SELECT pickup, destination, distance, duration FROM driver_trips WHERE driver_id = ? AND status = "completed" ORDER BY completed_at DESC LIMIT 4');
  $stmt->execute([$driver_id]);
  $trips = $stmt->fetchAll();

  json_response(['trips' => $trips]);
}

function handle_driver_emergency_requests()
{
  $pdo = get_db();

  $stmt = $pdo->query('SELECT er.id, u.name as patient_name, u.gender, er.location, h.name as hospital_name, er.priority 
                         FROM emergency_requests er
                         JOIN users u ON er.patient_id = u.id
                         JOIN hospitals h ON er.hospital_id = h.id
                         WHERE er.status = "pending"
                         ORDER BY er.priority DESC, er.created_at ASC');
  $requests = $stmt->fetchAll();

  json_response([
    'count' => count($requests),
    'requests' => $requests
  ]);
}

function handle_accept_emergency_request()
{
  $pdo = get_db();
  $input = json_decode(file_get_contents('php://input'), true);

  if (empty($input['request_id'])) {
    json_response(['error' => 'Request ID is required'], 400);
  }

  $request_id = (int)$input['request_id'];
  $driver_id = 1; // This should come from the session

  // Update emergency request status
  $stmt = $pdo->prepare('UPDATE emergency_requests SET status = "accepted", driver_id = ? WHERE id = ?');
  $stmt->execute([$driver_id, $request_id]);

  // Create a new trip for this emergency request
  $stmt = $pdo->prepare('INSERT INTO driver_trips (driver_id, patient_id, hospital_id, pickup_location, status, created_at) 
                           VALUES (?, (SELECT patient_id FROM emergency_requests WHERE id = ?), (SELECT hospital_id FROM emergency_requests WHERE id = ?), (SELECT location FROM emergency_requests WHERE id = ?), "accepted", NOW())');
  $stmt->execute([$driver_id, $request_id, $request_id, $request_id]);

  json_response(['message' => 'Emergency request accepted']);
}

function handle_driver_vehicle_status()
{
  $pdo = get_db();

  // In a real application, you would get the driver ID from the session
  // For now, we'll use a hardcoded ID
  $driver_id = 1; // This should come from the session

  $stmt = $pdo->prepare('SELECT fuel_level, odometer, condition, DATEDIFF(next_service_date, CURDATE()) as days_until_service 
                           FROM vehicles WHERE driver_id = ?');
  $stmt->execute([$driver_id]);
  $vehicle = $stmt->fetch();

  if (!$vehicle) {
    json_response(['error' => 'Vehicle not found'], 404);
  }

  json_response($vehicle);
}

function handle_driver_earnings()
{
  $pdo = get_db();

  // In a real application, you would get the driver ID from the session
  // For now, we'll use a hardcoded ID
  $driver_id = 1; // This should come from the session

  // This week's earnings
  $stmt = $pdo->prepare('SELECT SUM(fare) as total FROM driver_trips 
                           WHERE driver_id = ? AND status = "completed" 
                           AND completed_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)');
  $stmt->execute([$driver_id]);
  $this_week = $stmt->fetchColumn();

  // Today's earnings
  $stmt = $pdo->prepare('SELECT SUM(fare) as total FROM driver_trips 
                           WHERE driver_id = ? AND status = "completed" 
                           AND DATE(completed_at) = CURDATE()');
  $stmt->execute([$driver_id]);
  $today = $stmt->fetchColumn();

  // Current trip earnings
  $stmt = $pdo->prepare('SELECT fare FROM driver_trips 
                           WHERE driver_id = ? AND status = "in_progress"');
  $stmt->execute([$driver_id]);
  $current_trip = $stmt->fetchColumn();

  json_response([
    'this_week' => (float)$this_week,
    'today' => (float)$today,
    'current_trip' => (float)$current_trip
  ]);
}

function handle_driver_status_update()
{
  $pdo = get_db();
  $input = json_decode(file_get_contents('php://input'), true);

  if (empty($input['status']) || !in_array($input['status'], ['online', 'offline'])) {
    json_response(['error' => 'Valid status (online/offline) is required'], 400);
  }

  $driver_id = 1; // This should come from the session
  $status = $input['status'];

  // Update driver status
  $stmt = $pdo->prepare('INSERT INTO driver_status (driver_id, status, updated_at) 
                           VALUES (?, ?, NOW()) 
                           ON DUPLICATE KEY UPDATE status = VALUES(status), updated_at = NOW()');
  $stmt->execute([$driver_id, $status]);

  json_response(['message' => 'Status updated successfully']);
}

<?php
header('Content-Type: application/json');

// Include database connection
require_once '../includes/db.php';

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

// Get parameters
$lat = isset($data['lat']) ? $data['lat'] : null;
$lng = isset($data['lng']) ? $data['lng'] : null;
$timestamp = isset($data['timestamp']) ? $data['timestamp'] : date('Y-m-d H:i:s');

// Create database connection
$database = new Database();
$db = $database->getConnection();

// Get nearest available ambulance
$query = "
    SELECT 
        a.id,
        a.vehicle_number,
        a.driver_name,
        a.driver_phone,
        h.name as hospital_name,
        h.phone as hospital_phone,
        (6371 * acos(cos(radians(:lat)) * cos(radians(h.latitude)) * 
        cos(radians(h.longitude) - radians(:lng)) + 
        sin(radians(:lat)) * sin(radians(h.latitude)))) AS distance
    FROM 
        ambulances a
    JOIN 
        hospitals h ON a.hospital_id = h.id
    WHERE 
        a.is_available = 1
    ORDER BY 
        distance ASC
    LIMIT 1
";

$stmt = $db->prepare($query);
$stmt->bindParam(':lat', $lat);
$stmt->bindParam(':lng', $lng);
$stmt->execute();

$ambulance = $stmt->fetch(PDO::FETCH_ASSOC);

// Create SOS request
$query = "
    INSERT INTO sos_requests 
    (user_id, phone, location, status, created_at) 
    VALUES 
    (:user_id, :phone, :location, 'pending', :created_at)
";

$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $_SESSION['user_id'] ?? null);
$stmt->bindParam(':phone', $_SESSION['user_phone'] ?? null);
$stmt->bindParam(':location', json_encode(['lat' => $lat, 'lng' => $lng]));
$stmt->bindParam(':created_at', $timestamp);
$stmt->execute();

// Get the ID of the inserted SOS request
$sosRequestId = $db->lastInsertId();

// Create ambulance trip if ambulance is available
if ($ambulance) {
  $query = "
        INSERT INTO ambulance_trips 
        (ambulance_id, patient_id, pickup_latitude, pickup_longitude, status, requested_at) 
        VALUES 
        (:ambulance_id, :patient_id, :pickup_latitude, :pickup_longitude, 'pending', :requested_at)
    ";

  $stmt = $db->prepare($query);
  $stmt->bindParam(':ambulance_id', $ambulance['id']);
  $stmt->bindParam(':patient_id', $_SESSION['user_id'] ?? null);
  $stmt->bindParam(':pickup_latitude', $lat);
  $stmt->bindParam(':pickup_longitude', $lng);
  $stmt->bindParam(':requested_at', $timestamp);
  $stmt->execute();

  // Update ambulance availability
  $query = "
        UPDATE ambulances 
        SET is_available = 0 
        WHERE id = :ambulance_id
    ";

  $stmt = $db->prepare($query);
  $stmt->bindParam(':ambulance_id', $ambulance['id']);
  $stmt->execute();
}

// Return JSON response
echo json_encode([
  'success' => true,
  'sos_request_id' => $sosRequestId,
  'ambulance' => $ambulance,
  'phone' => $ambulance['driver_phone'] ?? null
]);

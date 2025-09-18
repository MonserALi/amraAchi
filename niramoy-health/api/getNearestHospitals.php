<?php
header('Content-Type: application/json');

// Include database connection
require_once '../includes/db.php';

// Get parameters
$lat = isset($_GET['lat']) ? floatval($_GET['lat']) : 23.8103; // Default to Dhaka
$lng = isset($_GET['lng']) ? floatval($_GET['lng']) : 90.4125; // Default to Dhaka
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10; // Default limit

// Create database connection
$database = new Database();
$db = $database->getConnection();

// Query to get nearest hospitals
$query = "
    SELECT 
        h.id, 
        h.name, 
        h.address, 
        h.district, 
        h.division, 
        h.phone, 
        h.latitude, 
        h.longitude,
        (6371 * acos(cos(radians(:lat)) * cos(radians(h.latitude)) * 
        cos(radians(h.longitude) - radians(:lng)) + 
        sin(radians(:lat)) * sin(radians(h.latitude)))) AS distance
    FROM 
        hospitals h
    WHERE 
        h.is_active = 1
    HAVING 
        distance < 50  // Within 50 km
    ORDER BY 
        distance ASC
    LIMIT 
        :limit
";

$stmt = $db->prepare($query);
$stmt->bindParam(':lat', $lat);
$stmt->bindParam(':lng', $lng);
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->execute();

$hospitals = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Format distance to 2 decimal places
foreach ($hospitals as &$hospital) {
  $hospital['distance'] = round($hospital['distance'], 2);
}

// Return JSON response
echo json_encode([
  'success' => true,
  'hospitals' => $hospitals
]);

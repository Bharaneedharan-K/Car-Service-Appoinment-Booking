<?php
// Include db_connection.php to access session and database
include '../db_connection.php';

// Get the JSON input
$data = json_decode(file_get_contents('php://input'), true);

// Sanitize and validate inputs
$serviceId = isset($data['service_id']) ? intval($data['service_id']) : 0;
$serviceName = !empty($data['service_name']) ? trim($data['service_name']) : 'Unknown Service';
$servicePrice = isset($data['service_price']) ? floatval($data['service_price']) : 0.0;
$shopId = isset($data['shop_id']) ? intval($data['shop_id']) : 0;
$userName = isset($currentUsername) ? $currentUsername : 'guest'; // Get the username from the session

// Assuming the service photo is coming from the database
$servicePhoto = !empty($data['service_photo']) ? trim($data['service_photo']) : 'placeholder.jpg';

// Insert into the garage_cart table
$sql = "INSERT INTO garage_cart (service_photo, service_name, shop_id, user_name, price) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssisd", $servicePhoto, $serviceName, $shopId, $userName, $servicePrice);

$response = array();
if ($stmt->execute()) {
    $response = array("success" => true, "message" => "Service added to cart.");
} else {
    $response = array("success" => false, "error" => $stmt->error);
}

$stmt->close();
$conn->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
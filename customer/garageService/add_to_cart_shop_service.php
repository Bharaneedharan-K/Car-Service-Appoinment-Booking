<?php
// Include db_connection.php to access session and database
include '../db_connection.php';

// Get the JSON input
$data = json_decode(file_get_contents('php://input'), true);

$serviceId = $data['service_id'] ?? 0;
$serviceName = $data['service_name'] ?? 'Unknown Service';
$servicePrice = $data['service_price'] ?? 0;
$shopId = $data['shop_id'] ?? 0;
$userName = $data['user_name'] ?? 'guest'; // Default to 'guest' if no username provided

// Assuming the service photo is coming from the database
// Modify the path for fetching service photo
$servicePhoto = ($data['service_photo'] ?? 'placeholder.jpg');

// Insert into the garage_cart table
$sql = "INSERT INTO garage_cart (service_photo, service_name, shop_id, user_name, price) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssisd", $servicePhoto, $serviceName, $shopId, $userName, $servicePrice);

if ($stmt->execute()) {
    $response = array("success" => true);
} else {
    $response = array("success" => false, "error" => $stmt->error);
}

$stmt->close();
$conn->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>

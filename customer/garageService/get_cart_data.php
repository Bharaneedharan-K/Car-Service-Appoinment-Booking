<?php
include '../db_connection.php';

if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$user_name = $_SESSION['username'];

$query = "SELECT serial_no, service_photo, service_name, shop_id, price FROM garage_cart WHERE user_name = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $user_name);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
}

// Return cart items as JSON
header('Content-Type: application/json');
echo json_encode($cart_items);
?>

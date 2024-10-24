<?php
include '../db_connection.php';

if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$user_name = $_SESSION['username'];

// Update the query to include the shop name from the vendor table
$query = "SELECT gc.serial_no, gc.service_photo, gc.service_name, v.shop_name, gc.price 
          FROM garage_cart gc 
          JOIN vendor v ON gc.shop_id = v.shop_id 
          WHERE gc.user_name = ?";
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

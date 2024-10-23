<?php
include '../db_connection.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['service_id'], $data['service_name'], $data['price'], $data['user_name'], $data['shop_id'])) {
    $service_id = $data['service_id'];
    $service_name = $data['service_name'];
    $price = $data['price'];
    $user_name = $data['user_name'];
    $shop_id = $data['shop_id'];

    // Prepare the SQL statement
    $sql = "INSERT INTO cart (service_id, service_name, price, user_name, shop_id) VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $service_id, $service_name, $price, $user_name, $shop_id);

    if ($stmt->execute()) {
        header('HTTP/1.1 201 Created');
        echo json_encode(['message' => 'Service added to cart']);
    } else {
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => 'Failed to add service to cart']);
    }

    $stmt->close();
} else {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'Incomplete data provided']);
}

$conn->close();
?>

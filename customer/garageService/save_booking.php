<?php
// save_booking.php

// Include database connection
include '../db_connection.php';

// Check if the user is logged in by checking the session
if (isset($_SESSION['username'])) {
    // Retrieve username from the session
    $username = $_SESSION['username'];
    
    // Get JSON input
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['shop_id'], $data['service_name'], $data['price'], $data['service_date'])) {
        $shop_id = $data['shop_id'];
        $service_name = $data['service_name'];
        $price = $data['price'];
        $service_date = $data['service_date'];
        $status = 'progress'; // Default status

        // Prepare SQL statement
        $stmt = $conn->prepare("INSERT INTO my_service (username, shop_id, service_name, price, service_date, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sisdss", $username, $shop_id, $service_name, $price, $service_date, $status);

        // Execute statement and check success
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid input']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
}
?>

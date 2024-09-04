<?php
// Include the database connection
include 'db_connection.php';

// Set the content type to JSON
header('Content-Type: application/json');

// Check if session is started and shop_id is set
session_start();
if (!isset($_SESSION['shop_id'])) {
    echo json_encode(['error' => 'Shop ID not set']);
    exit;
}

// Retrieve shop_id from session
$shopid = $_SESSION['shop_id'];

// Prepare and execute the SQL statement
$sql = "SELECT * FROM service_list WHERE shop_id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $shopid);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the data and encode it as JSON
    $services = [];
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }

    echo json_encode($services);

    $stmt->close();
} else {
    // Handle SQL preparation errors
    echo json_encode(['error' => 'SQL preparation failed']);
}

// Close the database connection
$conn->close();
?>

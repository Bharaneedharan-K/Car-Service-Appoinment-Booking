<?php
include 'db_connection.php';

// Fetch data from the service_list table
$sql = "SELECT service_id, shop_id, service_name, service_type, service_price, number_days, service_description, service_photo FROM service_list";
$result = $conn->query($sql);

$service_data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Ensure the correct relative path
        $row['service_photo'] = '../uploads/' . basename($row['service_photo']);
        $service_data[] = $row;
    }
}

// Return the data as JSON
header('Content-Type: application/json');
echo json_encode($service_data);

$conn->close();
?>

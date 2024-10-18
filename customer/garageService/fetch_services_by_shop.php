<?php
include '../db_connection.php';

if (isset($_GET['shop_id'])) {
    $shop_id = $_GET['shop_id'];

    // Fetch services for the given shop_id
    $sql = "SELECT service_name, service_price, service_description, service_photo 
            FROM service_list 
            WHERE shop_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $shop_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $service_data = [];
    
    while ($row = $result->fetch_assoc()) {
        // Ensure the correct relative path for service images
        $row['service_photo'] = '../../uploads/' . basename($row['service_photo']);
        $service_data[] = $row;
    }

    // Return the data as JSON
    header('Content-Type: application/json');
    echo json_encode($service_data);

    $stmt->close();
}
$conn->close();
?>

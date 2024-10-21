<!-- fetch_service_list.php -->

<?php
include '../db_connection.php';

// Check if current district is set
if ($currentDistrict !== null) {
    // Fetch data from the service_list table and join with vendor table to get shop name
    $sql = "SELECT s.service_id, s.shop_id, v.shop_name, s.service_name, s.service_type, 
                   s.service_price, s.number_days, s.service_description, s.service_photo 
            FROM service_list s
            JOIN vendor v ON s.shop_id = v.shop_id
            WHERE v.district = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $currentDistrict);
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

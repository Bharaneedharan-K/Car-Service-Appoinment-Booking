<?php
include '../db_connection.php';

// Check if current district is set
if ($currentDistrict !== null) {
    // Fetch data from the home_service_list table and join with vendor table to get shop name
    $sql = "SELECT hs.service_id, hs.shop_id, v.shop_name, hs.service_name, 
                   hs.service_price, hs.number_days, hs.service_description, hs.service_photo 
            FROM home_service_list hs
            JOIN vendor v ON hs.shop_id = v.shop_id
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

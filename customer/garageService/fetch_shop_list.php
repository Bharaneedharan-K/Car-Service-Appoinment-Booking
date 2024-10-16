<?php
include '../db_connection.php';

if ($currentDistrict !== null) {
    // Fetch approved shop data from the vendor table
    $sql = "SELECT shop_id, shop_name, location, shop_photo 
            FROM vendor 
            WHERE status = 'approved' AND district = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $currentDistrict);
    $stmt->execute();
    $result = $stmt->get_result();

    $shop_data = [];
    
    while ($row = $result->fetch_assoc()) {
        // Ensure the correct relative path for shop images
        $row['shop_photo'] = '../../uploads/' . basename($row['shop_photo']);
        $shop_data[] = $row;
    }

    // Return the data as JSON
    header('Content-Type: application/json');
    echo json_encode($shop_data);

    $stmt->close();
}
$conn->close();
?>

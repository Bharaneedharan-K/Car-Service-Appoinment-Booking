<?php
include '../db_connection.php';

// Fetch approved shop data from the vendor table
$sql = "SELECT shop_id, shop_name, location, shop_photo FROM vendor WHERE status = 'approved'";
$result = $conn->query($sql);

$shop_data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Ensure the correct relative path for shop images
        $row['shop_photo'] = '../../uploads/' . basename($row['shop_photo']);
        $shop_data[] = $row;
    }
}

// Return the data as JSON
header('Content-Type: application/json');
echo json_encode($shop_data);

$conn->close();
?>

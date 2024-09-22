<?php
include '../db_connection.php';

// Fetch approved vendors
$sql = "SELECT * FROM vendor WHERE status = 'approved'";
$result = $conn->query($sql);

$vendors = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Add the correct path to the image
        $row['shop_photo'] = '../../uploads/' . $row['shop_photo'];
        $vendors[] = $row;
    }
}

echo json_encode($vendors);
$conn->close();
?>

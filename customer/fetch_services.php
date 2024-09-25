<?php
include 'db_connection.php';

// Fetch services from the database
$sql = "SELECT * FROM service_list";
$result = $conn->query($sql);

$services = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Use only the correct path for the image
        $row['service_photo'] = '../uploads/' . $row['service_photo']; // Using the image name directly from the database
        $services[] = $row;
    }
}

echo json_encode($services);
$conn->close();
?>

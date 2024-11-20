<?php
include '../db_connection.php';

// Ensure shop_id is retrieved from session
if (empty($shopid)) {
    die("Shop ID is not set.");
}

$query = "SELECT * FROM home_service_list WHERE shop_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $shopid);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $service_id = $row['service_id'];
    $service_name = $row['service_name'];
    $service_price = $row['service_price'];
    $number_days = $row['number_days'];
    $service_description = $row['service_description'];
    $service_photo = $row['service_photo'];

    echo "<div class='service-card'>
            <img src='../../uploads/$service_photo' alt='$service_name'>
            <div class='service-card-content'>
                <h3>$service_name</h3>
                <p>Price: $service_price</p>
                <p>Number of Services Per Day: $number_days</p>
                <p>Description: $service_description</p>
                <button class='edit-btn' data-service='" . json_encode($row) . "'>Edit</button>
            </div>
        </div>";
}

$stmt->close();
$conn->close();
?>
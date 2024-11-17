<?php
require '../db_connection.php'; // Make sure this is included to access $currentUsername

$response = [];

if (isset($currentUsername)) {
    $sql = "
        SELECT ms.photo, ms.shop_id, v.shop_name, ms.service_name, ms.price, ms.service_date, 
               v.phone, v.location, v.google_map_location_url 
        FROM my_service ms 
        JOIN vendor v ON ms.shop_id = v.shop_id 
        WHERE ms.username = ? AND ms.status = 'progress'";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $currentUsername);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
    
    $stmt->close();
} else {
    // If $currentUsername is not set, return an empty array or an error message
    $response = ['error' => 'User not logged in.'];
}

echo json_encode($response);
?>

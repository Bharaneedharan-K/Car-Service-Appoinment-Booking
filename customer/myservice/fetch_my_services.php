<?php
require '../db_connection.php'; // Ensure this file connects to your database and starts the session.

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Fetch the district of the user (from the db_connection.php)
    $currentDistrict = null;
    $sql = "SELECT district FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($district);
    if ($stmt->fetch()) {
        $currentDistrict = $district;
    }
    $stmt->close();

    // Query to get in-progress services and vendor details
    $query = "
        SELECT 
            my_service.photo AS service_photo,
            my_service.service_name,
            my_service.price,
            my_service.service_date,
            vendor.shop_name,
            vendor.phone,
            vendor.location,
            vendor.district,
            vendor.google_map_location_url
        FROM 
            my_service
        INNER JOIN 
            vendor 
        ON 
            my_service.shop_id = vendor.id
        WHERE 
            my_service.username = ? 
            AND my_service.status = 'progress'
            AND vendor.district = ?";  // Ensuring the district filter is applied

    // Prepare and execute the query
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $currentDistrict);  // Bind username and district
    $stmt->execute();
    $result = $stmt->get_result();

    $services = [];
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }

    // Close the statement
    $stmt->close();

    // Set the response header and output the services in JSON format
    header('Content-Type: application/json');
    echo json_encode($services);
} else {
    // Handle the case where no user is logged in
    echo json_encode(['error' => 'User not logged in']);
}
?>

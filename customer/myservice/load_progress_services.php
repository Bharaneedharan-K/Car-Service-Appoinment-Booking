<?php
require '../db_connection.php';

if (!isset($_SESSION['username'])) {
    echo "Please log in to view your services.";
    exit;
}

$username = $_SESSION['username'];

// Query for progress services
$sql = "
    SELECT 
        my_service.photo AS service_photo, 
        my_service.shop_id, 
        vendor.shop_name, 
        my_service.service_name, 
        my_service.price, 
        my_service.service_date, 
        vendor.phone AS vendor_phone, 
        vendor.location AS vendor_location, 
        vendor.google_map_location_url 
    FROM my_service 
    INNER JOIN vendor ON my_service.shop_id = vendor.shop_id
    WHERE my_service.username = ? AND my_service.status = 'progress'
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '
        <div class="card progress">
            <img src="' . htmlspecialchars($row['service_photo']) . '" alt="Service Photo">
            <div class="card-details">
                <h3>' . htmlspecialchars($row['shop_name']) . '</h3>
                <p><strong>Shop ID:</strong> ' . htmlspecialchars($row['shop_id']) . '</p>
                <p><strong>Service:</strong> ' . htmlspecialchars($row['service_name']) . '</p>
                <p><strong>Price:</strong> ₹' . htmlspecialchars($row['price']) . '</p>
                <p><strong>Date:</strong> ' . htmlspecialchars($row['service_date']) . '</p>
                <p><strong>Phone:</strong> ' . htmlspecialchars($row['vendor_phone']) . '</p>
                <p><strong>Location:</strong> ' . htmlspecialchars($row['vendor_location']) . '</p>
                <a href="' . htmlspecialchars($row['google_map_location_url']) . '" target="_blank">
                    <button>View on Map</button>
                </a>
            </div>
        </div>';
    }
} else {
    echo '<p>No services in progress.</p>';
}
?>

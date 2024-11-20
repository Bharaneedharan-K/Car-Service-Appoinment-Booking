<?php
require '../db_connection.php';

if (!isset($_SESSION['username'])) {
    echo "Please log in to view your services.";
    exit;
}

$username = $_SESSION['username'];

// Query for completed and rejected services
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
        my_service.status, 
        my_service.reason 
    FROM my_service 
    INNER JOIN vendor ON my_service.shop_id = vendor.shop_id
    WHERE my_service.username = ? AND (my_service.status = 'complete' OR my_service.status = 'reject')
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cardClass = $row['status']; // Use status as CSS class
        echo '
        <div class="card ' . htmlspecialchars($cardClass) . '">
            <img src="' . htmlspecialchars($row['service_photo']) . '" alt="Service Photo">
            <div class="card-details">
                <h3>' . htmlspecialchars($row['shop_name']) . '</h3>
                <p><strong>Shop ID:</strong> ' . htmlspecialchars($row['shop_id']) . '</p>
                <p><strong>Service:</strong> ' . htmlspecialchars($row['service_name']) . '</p>
                <p><strong>Price:</strong> ₹' . htmlspecialchars($row['price']) . '</p>
                <p><strong>Date:</strong> ' . htmlspecialchars($row['service_date']) . '</p>
                <p><strong>Phone:</strong> ' . htmlspecialchars($row['vendor_phone']) . '</p>
                <p><strong>Location:</strong> ' . htmlspecialchars($row['vendor_location']) . '</p>';

        if ($row['status'] === 'reject') {
            echo '<p><strong>Reason for Rejection:</strong> ' . htmlspecialchars($row['reason']) . '</p>';
        }

        echo '
            </div>
        </div>';
    }
} else {
    echo '<p>No services in history.</p>';
}
?>

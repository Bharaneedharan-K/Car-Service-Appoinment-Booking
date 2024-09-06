<?php
include '../db_connection.php'; // Include the database connection

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Retrieve shop_id from session
if (!isset($_SESSION['shop_id'])) {
    die("Error: No shop ID in session.");
}
$shop_id = $_SESSION['shop_id'];

// Fetch services from the database
$sql = "SELECT * FROM service_list WHERE shop_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $shop_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $serviceData = json_encode($row); // Encode service data as JSON for editing
        echo "<div class='service-card'>
                <img src='" . htmlspecialchars($row['service_photo']) . "' alt='Service Photo'>
                <div class='service-card-content'>
                    <h3>" . htmlspecialchars($row['service_name']) . "</h3>
                    <p><strong>Price:</strong> $" . htmlspecialchars($row['service_price']) . "</p>
                    <p><strong>Number of Services Per Day:</strong> " . htmlspecialchars($row['number_days']) . "</p>
                    <p><strong>Description:</strong> " . htmlspecialchars($row['service_description']) . "</p>
                    <button class='edit-btn' data-service='" . htmlspecialchars($serviceData, ENT_QUOTES, 'UTF-8') . "'>Edit</button>
                </div>
              </div>";
    }
} else {
    echo "<p>No services found.</p>";
}

$stmt->close();
$conn->close();
?>

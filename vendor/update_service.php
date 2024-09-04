<?php
include 'db_connection.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Retrieve shop_id from session
if (!isset($_SESSION['shop_id'])) {
    die("Error: No shop ID in session.");
}
$shop_id = $_SESSION['shop_id'];

// Retrieve form data
$serviceName = $_POST['serviceName'];
$servicePrice = $_POST['servicePrice'];
$numServicesPerDay = $_POST['numServicesPerDay'];
$serviceDescription = $_POST['serviceDescription'];
$servicePhoto = $_FILES['servicePhoto']['name']; // Handle file upload as needed

// Update service in the database
$sql = "UPDATE service_list SET 
            service_name = ?, 
            service_price = ?, 
            number_days = ?, 
            service_description = ?, 
            service_photo = ? 
        WHERE 
            shop_id = ? AND 
            service_id = ?"; // Assuming service_id is used to identify which service to update

$stmt = $conn->prepare($sql);
$stmt->bind_param("sdissii", $serviceName, $servicePrice, $numServicesPerDay, $serviceDescription, $servicePhoto, $shop_id, $_POST['serviceId']); // Adjust parameters as needed
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Service updated successfully.";
} else {
    echo "Error updating service.";
}

$stmt->close();
$conn->close();
?>

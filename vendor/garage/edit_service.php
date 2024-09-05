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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $serviceId = $_POST['serviceId'];
    $serviceName = $_POST['serviceName'];
    $servicePrice = $_POST['servicePrice'];
    $serviceType = $_POST['serviceType'];
    $numServicesPerDay = $_POST['numServicesPerDay'];
    $serviceDescription = $_POST['serviceDescription'];

    // Initialize servicePhoto with NULL
    $servicePhoto = NULL;

    // Handle file upload if a new photo is uploaded
    if (isset($_FILES['servicePhoto']) && $_FILES['servicePhoto']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        // Ensure the uploads directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $uniqueName = uniqid() . '-' . basename($_FILES['servicePhoto']['name']);
        $servicePhoto = $uploadDir . $uniqueName;
        move_uploaded_file($_FILES['servicePhoto']['tmp_name'], $servicePhoto);
    }

    if ($servicePhoto) {
        // Update all fields including service_photo
        $sql = "UPDATE service_list 
                SET service_name = ?, service_price = ?, service_type = ?, number_days = ?, service_description = ?, service_photo = ?
                WHERE service_id = ? AND shop_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdssissi", $serviceName, $servicePrice, $serviceType, $numServicesPerDay, $serviceDescription, $servicePhoto, $serviceId, $shop_id);
    } else {
        // Update all fields except service_photo
        $sql = "UPDATE service_list 
                SET service_name = ?, service_price = ?, service_type = ?, number_days = ?, service_description = ?
                WHERE service_id = ? AND shop_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdssis", $serviceName, $servicePrice, $serviceType, $numServicesPerDay, $serviceDescription, $serviceId, $shop_id);
    }

    if ($stmt->execute()) {
        // Redirect back to the main page after successful update
        header("Location: garage_service.html");
        exit();
    } else {
        echo "Error updating service: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

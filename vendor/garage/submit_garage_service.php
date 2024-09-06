<?php
include '../db_connection.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    $serviceId = isset($_POST['serviceId']) ? $_POST['serviceId'] : null;
    $serviceName = $_POST['serviceName'];
    $servicePrice = $_POST['servicePrice'];
    $serviceType = $_POST['serviceType'];
    $numServicesPerDay = $_POST['numServicesPerDay'];
    $serviceDescription = $_POST['serviceDescription'];

    // Handle file upload
    $servicePhoto = '';
    if (isset($_FILES['servicePhoto']) && $_FILES['servicePhoto']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $servicePhoto = $uploadDir . uniqid() . '-' . basename($_FILES['servicePhoto']['name']);
        move_uploaded_file($_FILES['servicePhoto']['tmp_name'], $servicePhoto);
    }

    if ($serviceId) {
        // Update existing service
        if (!empty($servicePhoto)) {
            $sql = "UPDATE service_list SET service_name=?, service_price=?, number_days=?, service_description=?, service_photo=? WHERE service_id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sdissi", $serviceName, $servicePrice, $numServicesPerDay, $serviceDescription, $servicePhoto, $serviceId);
        } else {
            $sql = "UPDATE service_list SET service_name=?, service_price=?, number_days=?, service_description=? WHERE service_id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sdisi", $serviceName, $servicePrice, $numServicesPerDay, $serviceDescription, $serviceId);
        }
    } else {
        // Add new service
        $sql = "INSERT INTO service_list (shop_id, service_name, service_type, service_price, number_days, service_description, service_photo)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issdiss", $shop_id, $serviceName, $serviceType, $servicePrice, $numServicesPerDay, $serviceDescription, $servicePhoto);
    }

    if ($stmt->execute()) {
        header("Location: garage_service.html");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<?php
include 'db_connection.php'; // Include the database connection

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

    // Prepare and execute SQL statement
    $sql = "INSERT INTO service_list (shop_id, service_name, service_type, service_price, number_days, service_description, service_photo)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issdiss", $shop_id, $serviceName, $serviceType, $servicePrice, $numServicesPerDay, $serviceDescription, $servicePhoto);

    if ($stmt->execute()) {
        echo "New service added successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

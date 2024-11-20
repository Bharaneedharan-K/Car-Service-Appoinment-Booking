<?php
include '../db_connection.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $serviceId = $_POST['serviceId'];
    $serviceName = $_POST['editServiceName'];
    $servicePrice = $_POST['editServicePrice'];
    $numServicesPerDay = $_POST['editNumServicesPerDay'];
    $serviceDescription = $_POST['editServiceDescription'];

    // Handle file upload if a new photo is uploaded
    if (!empty($_FILES['editServicePhoto']['name'])) {
        $target_dir = "../../uploads/";
        $target_file = $target_dir . basename($_FILES["editServicePhoto"]["name"]);
        move_uploaded_file($_FILES["editServicePhoto"]["tmp_name"], $target_file);
        $servicePhoto = $target_file;
    } else {
        // Keep the existing photo if no new photo is uploaded
        $sql = "SELECT service_photo FROM service_list WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $serviceId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $servicePhoto = $row['service_photo'];
        $stmt->close();
    }

    // Update service in the database
    $sql = "UPDATE service_list SET service_name = ?, service_price = ?, number_days = ?, service_description = ?, service_photo = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdissi", $serviceName, $servicePrice, $numServicesPerDay, $serviceDescription, $servicePhoto, $serviceId);

    if ($stmt->execute()) {
        echo "Service updated successfully.";
    } else {
        echo "Error updating service: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    // Redirect back to the main page
    header("Location: garage_service.html");
}
?>

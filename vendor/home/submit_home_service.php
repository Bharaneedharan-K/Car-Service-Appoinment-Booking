<?php
include '../db_connection.php';

// Ensure shop_id is retrieved from session
if (empty($shopid)) {
    http_response_code(400); // Bad request
    exit;
}

$serviceId = $_POST['serviceId'];
$serviceName = $_POST['serviceName'];
$servicePrice = $_POST['servicePrice'];
$numServicesPerDay = $_POST['numServicesPerDay'];
$serviceDescription = $_POST['serviceDescription'];
$servicePhoto = '';

// Handle file upload
if (isset($_FILES['servicePhoto']) && $_FILES['servicePhoto']['error'] == UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['servicePhoto']['tmp_name'];
    $fileName = $_FILES['servicePhoto']['name'];
    $fileSize = $_FILES['servicePhoto']['size'];
    $fileType = $_FILES['servicePhoto']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));
    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
    $uploadFileDir = '../../uploads/';
    $dest_path = $uploadFileDir . $newFileName;

    if (move_uploaded_file($fileTmpPath, $dest_path)) {
        $servicePhoto = $newFileName;
    }
}

if ($serviceId) {
    // If there's a new photo, update the photo in the database
    if ($servicePhoto) {
        $query = "UPDATE home_service_list SET 
                    service_name = ?, 
                    service_price = ?, 
                    number_days = ?, 
                    service_description = ?, 
                    service_photo = ? 
                  WHERE service_id = ?";
    } else {
        // If no new photo is uploaded, keep the existing photo
        $query = "UPDATE home_service_list SET 
                    service_name = ?, 
                    service_price = ?, 
                    number_days = ?, 
                    service_description = ? 
                  WHERE service_id = ?";
    }
    $stmt = $conn->prepare($query);
    if ($servicePhoto) {
        $stmt->bind_param('sdissi', $serviceName, $servicePrice, $numServicesPerDay, $serviceDescription, $servicePhoto, $serviceId);
    } else {
        $stmt->bind_param('sdiss', $serviceName, $servicePrice, $numServicesPerDay, $serviceDescription, $serviceId);
    }
} else {
    $query = "INSERT INTO home_service_list 
                (shop_id, service_name, service_price, number_days, service_description, service_photo) 
              VALUES 
                (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('isdiss', $shopid, $serviceName, $servicePrice, $numServicesPerDay, $serviceDescription, $servicePhoto);
}

if ($stmt->execute()) {
    header("Location: home_service.html");
} else {
    http_response_code(500); // Internal server error
}

$stmt->close();
$conn->close();
?>

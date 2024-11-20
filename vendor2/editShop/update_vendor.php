<?php
include '../db_connection.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$shopid = $_SESSION['shop_id'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($shopid)) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $shop_name = $_POST['shop_name'];
    $gst_no = $_POST['gst_no'];
    $location = $_POST['location'];
    $district = $_POST['district'];
    $map_url = $_POST['map_url'];

    // Initialize variable to hold new or existing image filename
    $shop_photo = null;

    // Retrieve current shop photo filename from the database
    $stmt = $conn->prepare("SELECT shop_photo FROM vendor WHERE shop_id = ?");
    $stmt->bind_param("s", $shopid);
    $stmt->execute();
    $result = $stmt->get_result();
    $vendor = $result->fetch_assoc();
    $current_shop_photo = $vendor['shop_photo'];
    $stmt->close();

    // File upload handling
    if (isset($_FILES['shop_image']) && $_FILES['shop_image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "../../uploads/";
        $new_shop_photo = basename($_FILES["shop_image"]["name"]);
        $target_file = $target_dir . $new_shop_photo;

        // Move the uploaded file
        if (move_uploaded_file($_FILES["shop_image"]["tmp_name"], $target_file)) {
            $shop_photo = $new_shop_photo;
        } else {
            die("Error: Failed to upload the file.");
        }
    } else {
        // No new file uploaded, use the existing filename
        $shop_photo = $current_shop_photo;
    }

    // Prepare SQL update statement, ensuring to update shop_photo
    $stmt = $conn->prepare("UPDATE vendor SET name = ?, phone = ?, email = ?, shop_name = ?, gst_no = ?, location = ?, district = ?, google_map_location_url = ?, shop_photo = ? WHERE shop_id = ?");
    $stmt->bind_param("ssssssssss", $name, $phone, $email, $shop_name, $gst_no, $location, $district, $map_url, $shop_photo, $shopid);

    // Execute the update and redirect or show an error
    if ($stmt->execute()) {
        header("Location: edit.html?success=1");
        exit;
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
} else {
    die("Invalid request.");
}

$conn->close();
?>

<!-- update_vendor.php -->
<?php
include '../db_connection.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize variables
$shopid = $_SESSION['shop_id'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($shopid)) {
    // Collect form data
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $shop_name = $_POST['shop_name'];
    $gst_no = $_POST['gst_no'];
    $location = $_POST['location'];
    $district = $_POST['district'];
    $map_url = $_POST['map_url'];
    
    // Handle image upload
    $shop_photo = null;
    if (isset($_FILES['shop_image']) && $_FILES['shop_image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "../../uploads/";
        $shop_photo = basename($_FILES["shop_image"]["name"]);
        $target_file = $target_dir . $shop_photo;

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($_FILES["shop_image"]["tmp_name"], $target_file)) {
            die("Error uploading file.");
        }
    }

    // Prepare and execute SQL update statement
    $stmt = $conn->prepare("UPDATE vendor SET name = ?, phone = ?, email = ?, shop_name = ?, gst_no = ?, location = ?, district = ?, google_map_location_url = ?, shop_photo = ? WHERE shop_id = ?");
    $stmt->bind_param("ssssssssss", $name, $phone, $email, $shop_name, $gst_no, $location, $district, $map_url, $shop_photo, $shopid);
    
    if ($stmt->execute()) {
        // Redirect with success parameter
        header("Location: edit.html?success=1");
        exit; // Important to exit after redirect
    } else {
        // Handle errors
        echo "Error updating record: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    // Handle the case where shop_id is not found in session or invalid request
    die("Invalid request.");
}

// Close the database connection
$conn->close();
?>

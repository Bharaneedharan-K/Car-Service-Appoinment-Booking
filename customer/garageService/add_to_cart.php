<?php
// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "carservice";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_SESSION['username'])) {
    $currentUsername = $_SESSION['username'];

    // Get the POST data from the AJAX request
    if (isset($_POST['service_name']) && isset($_POST['service_price']) && isset($_POST['shop_id']) && isset($_POST['service_photo'])) {
        $service_name = $_POST['service_name'];
        $service_price = $_POST['service_price'];
        $shop_id = $_POST['shop_id'];
        $service_photo = $_POST['service_photo'];

        // Insert data into garage_cart table
        $stmt = $conn->prepare("INSERT INTO garage_cart (service_photo, service_name, shop_id, user_name, price) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssisd", $service_photo, $service_name, $shop_id, $currentUsername, $service_price);
        
        if ($stmt->execute()) {
            echo "Service added to cart successfully";
        } else {
            echo "Error adding to cart: " . $conn->error;
        }
        
        $stmt->close();
    } else {
        echo "Invalid request data";
    }
} else {
    echo "User not logged in";
}

$conn->close();
?>
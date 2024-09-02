<?php
// Database connection
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "carservice";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shop_id = $_POST['forgot_shop_id'];
    $phone = $_POST['forgot_phone'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        echo "Passwords do not match.";
        exit();
    }

    // Check if vendor exists with provided shop ID and phone number
    $sql = "SELECT * FROM vendor WHERE shop_id = ? AND phone = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $shop_id, $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update password
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $update_sql = "UPDATE vendor SET password = ? WHERE shop_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ss", $hashed_password, $shop_id);
        $update_stmt->execute();

        echo "Password updated successfully.";
    } else {
        echo "Invalid shop ID or phone number.";
    }

    $stmt->close();
}

$conn->close();
?>

<?php
include 'db_connection.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shop_id = $_POST['shop_id']; // Get shop_id from POST request
    $password = $_POST['password'];

    // Check if vendor exists, password is correct, and status is 'approved'
    $sql = "SELECT * FROM vendor WHERE shop_id = ? AND status = 'approved'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $shop_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $vendor = $result->fetch_assoc();
        if (password_verify($password, $vendor['password'])) {
            // Start session and store vendor information
            session_start();
            $_SESSION['vendor_id'] = $vendor['id'];
            $_SESSION['shop_id'] = $vendor['shop_id']; // Store shop_id in session
            header("Location: dashboard/dashboard.php");
            //header("Location: garage/garage_service.html");
            //header("Location: home/home_service.html");
            exit();
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "Shop ID does not exist or your account has not been approved.";
    }

    $stmt->close();
}

$conn->close();
?>

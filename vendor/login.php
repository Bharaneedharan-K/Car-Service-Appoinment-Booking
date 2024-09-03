<?php
include 'db_connection.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Now you can use $shopid if needed
    $shop_id = $shopid; // This uses the shop_id stored in db_connection.php
    $password = $_POST['password'];

    // Check if vendor exists and password is correct
    $sql = "SELECT * FROM vendor WHERE shop_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $shop_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $vendor = $result->fetch_assoc();
        if (password_verify($password, $vendor['password'])) {
            // Start session and redirect to vendor dashboard
            session_start();
            $_SESSION['vendor_id'] = $vendor['id'];
            header("Location: vendor_dashboard.php");
            exit();
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "Shop ID does not exist.";
    }

    $stmt->close();
}

$conn->close();
?>

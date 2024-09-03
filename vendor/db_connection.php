<!-- db_connection.php -->

<?php
// Database connection
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "carservice";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize shop_id
$shopid = "";

// Retrieve shop_id from session
if (isset($_SESSION['shop_id'])) {
    $shopid = $_SESSION['shop_id']; // Use shop_id stored in session
}
?>

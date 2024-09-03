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

// Temporary variable to store shop_id if available
$shopid = "";

// Check if shop_id is set in POST request and store it in a temporary variable
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['shop_id'])) {
    $shopid = $_POST['shop_id'];
}
?>

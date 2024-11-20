<?php
// Start the session only if it is not already started
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

$currentDistrict = null;

if (isset($_SESSION['username'])) {
    $currentUsername = $_SESSION['username'];
    
    // Fetch the district of the user
    $sql = "SELECT district FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $currentUsername);
    $stmt->execute();
    $stmt->bind_result($district);
    if ($stmt->fetch()) {
        $currentDistrict = $district;
    }
    $stmt->close();
}
?>
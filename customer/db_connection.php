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

// Store the username from the session in a variable if it exists
if (isset($_SESSION['username'])) {
    $currentUsername = $_SESSION['username'];
    // Optional: you could also store it in a database or log it as needed
}
?>
    
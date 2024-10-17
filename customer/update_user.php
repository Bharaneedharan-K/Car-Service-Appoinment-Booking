<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require 'db_connection.php';

// Check if the user is logged in by checking the session variable
if (!isset($_SESSION['username'])) {
    exit("User not logged in.");
}

// Retrieve form data
$carBrand = isset($_POST['carBrand']) ? $conn->real_escape_string($_POST['carBrand']) : null;
$carModel = isset($_POST['carModel']) ? $conn->real_escape_string($_POST['carModel']) : null;
$district = isset($_POST['district']) ? $conn->real_escape_string($_POST['district']) : null;
$currentUsername = $_SESSION['username'];

// Update user data in the database
$sql = "UPDATE users SET car_brand = '$carBrand', car_model = '$carModel', district = '$district' WHERE username = '$currentUsername'";

if ($conn->query($sql) === TRUE) {
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit;
} else {
    // Optional: Handle the error internally (or redirect to an error page if desired)
    error_log("Error updating record: " . $conn->error); // Log error for debugging
}

$conn->close();
?>

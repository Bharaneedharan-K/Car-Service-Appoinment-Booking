<?php
// Include db_connection.php to access session and database
include '../db_connection.php';

// Check if the session username is set
if (isset($_SESSION['username'])) {
    $response = array("username" => $_SESSION['username']);
} else {
    $response = array("username" => null); // Null or guest for anonymous users
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>

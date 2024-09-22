<?php
// Start the session to manage login state
session_start();

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Default credentials (hard-coded)
    $default_username = 'admin';
    $default_password = '123';

    // Check if the entered credentials match the default credentials
    if ($username === $default_username && $password === $default_password) {
        // Set the session variable for the logged-in admin
        $_SESSION['admin'] = $username;
        // Redirect to the admin dashboard
        header("Location: admin_dashboard.php");
        exit();
    } else {
        // If credentials don't match, show an error message
        echo "<script>alert('Invalid username or password!');</script>";
    }
}
?>

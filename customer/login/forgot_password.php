<?php
// Initialize variables for messages
$message = '';
$message_type = '';

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include the database connection file
    include '../db_connection.php';

    // Get form data and sanitize it
    $username = $conn->real_escape_string($_POST['username']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $new_password = $conn->real_escape_string($_POST['new_password']);
    $confirm_password = $conn->real_escape_string($_POST['confirm_password']);

    // Check if passwords match
    if ($new_password !== $confirm_password) {
        $message = "Passwords do not match.";
        $message_type = 'error';
    } else {
        // Check if username and phone number match
        $sql = "SELECT * FROM users WHERE username = '$username' AND phone_no = '$phone'";
        $result = $conn->query($sql);

        if ($result->num_rows === 1) {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the password in the database
            $update_sql = "UPDATE users SET password = '$hashed_password' WHERE username = '$username' AND phone_no = '$phone'";
            if ($conn->query($update_sql) === TRUE) {
                $message = "Password has been successfully reset.";
                $message_type = 'success';
            } else {
                $message = "Error updating password: " . $conn->error;
                $message_type = 'error';
            }
        } else {
            $message = "Username and phone number do not match.";
            $message_type = 'error';
        }
    }

    // Close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Forgot Password</title>
</head>
<body>

    <div class="container">
        <h1>Forgot Password</h1>
        <form action="" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="phone">Phone Number:</label>
            <input type="text" id="phone" name="phone" required>

            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" required>

            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <button type="submit">Reset Password</button>
        </form>

        <?php if ($message): ?>
            <p class="<?php echo $message_type; ?>"><?php echo $message; ?></p>
        <?php endif; ?>
    </div>

</body>
</html>

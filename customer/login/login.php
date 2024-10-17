<?php
session_start();

// Initialize variables for messages
$message = '';
$message_type = '';

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include the database connection file
    include '../db_connection.php';

    // Get form data and sanitize it
    $username = $conn->real_escape_string(trim($_POST['username']));
    $password = trim($_POST['password']);

    // Check if the username exists in the database
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    // Verify the user exists and check the password
    if ($result && $result->num_rows === 1) {
        // Fetch the user data
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Store username in session
            $_SESSION['username'] = $user['username'];
            
            // Redirect to a protected page or dashboard
            header("Location: ../home.html");
            exit();
        } else {
            $message = "Incorrect password.";
            $message_type = 'error';
        }
    } else {
        $message = "Username does not exist.";
        $message_type = 'error';
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
    <title>Login</title>
    <style>
        /* Add basic styling for messages */
        .error {
            color: red;
        }
        .success {
            color: green;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Login</h1>
        <form action="" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
            <p>Forgot your password? <a href="forgot_password.php">Reset it here</a></p>
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </form>

        <?php if ($message): ?>
            <p class="<?php echo htmlspecialchars($message_type); ?>"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
    </div>

</body>
</html>

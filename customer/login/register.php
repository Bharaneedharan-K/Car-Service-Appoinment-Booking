<?php
// Initialize variables for messages
$message = '';
$message_type = '';

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include the database connection file
    include '../db_connection.php';

    // Get form data and sanitize it
    $name = $conn->real_escape_string($_POST['name']);
    $username = $conn->real_escape_string($_POST['username']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $email = $conn->real_escape_string($_POST['email']);
    $address = $conn->real_escape_string($_POST['address']);
    $password = $conn->real_escape_string($_POST['password']);
    $confirm_password = $conn->real_escape_string($_POST['confirm-password']);

    // Check if passwords match
    if ($password !== $confirm_password) {
        $message = "Passwords do not match.";
        $message_type = 'error';
    } else {
        // Check if username, email, or phone number already exists
        $check_query = "SELECT * FROM users WHERE username = '$username' OR email = '$email' OR phone_no = '$phone'";
        $result = $conn->query($check_query);

        if ($result->num_rows > 0) {
            // Determine which field is duplicated
            $row = $result->fetch_assoc();
            if ($row['username'] === $username) {
                $message = "Username is already taken.";
            } elseif ($row['email'] === $email) {
                $message = "Email ID is already registered.";
            } elseif ($row['phone_no'] === $phone) {
                $message = "Phone number is already registered.";
            }
            $message_type = 'error';
        } else {
            // Hash the password before storing it
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Prepare SQL statement to insert user data
            $sql = "INSERT INTO users (name, username, phone_no, email, address, password) 
                    VALUES ('$name', '$username', '$phone', '$email', '$address', '$hashed_password')";

            // Execute the query
            if ($conn->query($sql) === TRUE) {
                $message = "Registration successful!";
                $message_type = 'success';
            } else {
                $message = "Error: " . $conn->error;
                $message_type = 'error';
            }
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
    <title>Register</title>
    <style>
        /* Simple modal styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            text-align: center;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Register</h1>
        <form action="" method="POST">
            <!-- form inputs as before -->
            <div class="row">
                <div class="column">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="column">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
            </div>
            <div class="row">
                <div class="column">
                    <label for="phone">Phone No:</label>
                    <input type="text" id="phone" name="phone" required>
                </div>
                <div class="column">
                    <label for="email">Email ID:</label>
                    <input type="email" id="email" name="email" required>
                </div>
            </div>
            <div class="row">
                <div class="column">
                    <label for="address">Address:</label>
                    <textarea id="address" name="address" required></textarea>
                </div>
            </div>
            <div class="row">
                <div class="column">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="column">
                    <label for="confirm-password">Confirm Password:</label>
                    <input type="password" id="confirm-password" name="confirm-password" required>
                </div>
            </div>
            <button type="submit">Register</button>
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </form>
    </div>

    <!-- Modal structure -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p id="modalMessage"></p>
        </div>
    </div>

    <script>
        // Display the modal with the PHP message
        const message = "<?php echo $message; ?>";
        const messageType = "<?php echo $message_type; ?>";

        if (message) {
            const modal = document.getElementById("modal");
            const modalMessage = document.getElementById("modalMessage");
            modalMessage.innerHTML = message;
            modal.style.display = "block";
        }

        // Function to close the modal
        function closeModal() {
            document.getElementById("modal").style.display = "none";
        }
    </script>

</body>
</html>
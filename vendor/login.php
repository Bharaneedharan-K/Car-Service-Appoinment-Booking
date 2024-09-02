<?php
// Database connection
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "carservice";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shop_id = $_POST['shop_id'];
    $password = $_POST['password'];

    // Check if vendor exists and password is correct
    $sql = "SELECT * FROM vendor WHERE shop_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $shop_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $vendor = $result->fetch_assoc();
        if (password_verify($password, $vendor['password'])) {
            // Start session and redirect to vendor dashboard
            session_start();
            $_SESSION['vendor_id'] = $vendor['id'];
            header("Location: vendor_dashboard.php");
            exit();
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "Shop ID does not exist.";
    }

    $stmt->close();
}

$conn->close();
?>

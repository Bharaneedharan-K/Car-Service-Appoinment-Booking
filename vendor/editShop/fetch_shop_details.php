<!-- fetch_shop_details.php -->
<?php
include '../db_connection.php'; // Include the database connection

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Retrieve shop_id from session
if (!isset($_SESSION['shop_id'])) {
    die(json_encode(['error' => 'No shop ID in session.']));
}
$shop_id = $_SESSION['shop_id'];

// Fetch shop details from the database
$sql = "SELECT * FROM vendor WHERE shop_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $shop_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $shop = $result->fetch_assoc();
    echo json_encode($shop);
} else {
    echo json_encode(['error' => 'Shop not found']);
}

$stmt->close();
$conn->close();
?>

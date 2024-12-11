<?php
// confirm_booking.php
include '../db_connection.php'; // Ensure your DB connection is included

$data = json_decode(file_get_contents('php://input'), true);

$shop_id = $data['shop_id'];
$service_date = $data['service_date'];

// Use the username from the session, assuming it has been set in db_connection.php
if (!isset($_SESSION['username'])) {
    echo json_encode(["success" => false, "message" => "User is not logged in."]);
    exit;
}
$username = $_SESSION['username'];

// Fetch all services for the shop from the `service_list` table
$query = "SELECT * FROM service_list WHERE shop_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $shop_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $insertSuccess = true;

    while ($row = $result->fetch_assoc()) {
        // Insert each service into `my_service`
        $insertQuery = "INSERT INTO my_service (username, shop_id, photo, service_name, price, service_date)
                        VALUES (?, ?, ?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param(
            "sissds",
            $username,
            $shop_id,
            $row['service_photo'],
            $row['service_name'],
            $row['service_price'], // Adjust the column name if needed
            $service_date
        );

        if (!$insertStmt->execute()) {
            $insertSuccess = false;
            break;
        }
    }

    if ($insertSuccess) {
        // Clear cart items for the user and the shop
        $deleteQuery = "DELETE FROM garage_cart WHERE user_name = ? AND shop_id = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bind_param("si", $username, $shop_id);
        $deleteStmt->execute();

        echo json_encode(["success" => true, "message" => "Booking confirmed and cart items cleared."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to add services to 'my_service'."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "No services found for this shop."]);
}

$conn->close();
?>
<?php
// Include the database connection
include 'db_connection.php';

header('Content-Type: application/json');

$shopid = $_SESSION['shop_id']; // Retrieve shop_id from session

$sql = "SELECT * FROM service_list WHERE shop_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $shopid);
$stmt->execute();
$result = $stmt->get_result();

$services = [];
while ($row = $result->fetch_assoc()) {
    $services[] = $row;
}

echo json_encode($services);

$stmt->close();
$conn->close();
?>

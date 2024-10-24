<?php
include '../db_connection.php';

if (isset($_GET['serial_no'])) {
    $serial_no = $_GET['serial_no'];

    // Delete the item from the garage_cart table
    $query = "DELETE FROM garage_cart WHERE serial_no = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $serial_no);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>

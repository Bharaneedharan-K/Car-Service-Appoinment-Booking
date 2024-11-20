<?php
include '../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $s_no = $_POST['s_no'];
    $status = $_POST['status'];

    // Depending on the status, update the database
    if ($status === 'complete') {
        $sql = "UPDATE my_service SET status = 'complete' WHERE s_no = ?";
    } elseif ($status === 'reject') {
        $reason = $_POST['reason']; // Ensure you handle the reason when rejecting
        $sql = "UPDATE my_service SET status = 'rejected', reason = ? WHERE s_no = ?";
    }

    $stmt = $conn->prepare($sql);
    if ($status === 'reject') {
        $stmt->bind_param('si', $reason, $s_no);
    } else {
        $stmt->bind_param('i', $s_no);
    }

    if ($stmt->execute()) {
        echo 'Success';
    } else {
        echo 'Error updating status';
    }
}
?>

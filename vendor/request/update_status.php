<?php
include '../db_connection.php'; // Include your DB connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $s_no = $_POST['s_no'];
    $status = $_POST['status'];

    // Validate status
    if (in_array($status, ['reject', 'complete'])) {
        $sql = "UPDATE my_service SET status = ? WHERE s_no = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $s_no);

        if ($stmt->execute()) {
            echo "<script>alert('Status updated successfully!'); window.location.href = 'request.php';</script>";
        } else {
            echo "<script>alert('Failed to update status. Please try again.'); window.location.href = 'request.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid status.'); window.location.href = 'request.php';</script>";
    }
}
?>

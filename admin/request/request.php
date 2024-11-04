<?php
include '../db_connection.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $vendor_id = isset($_POST['vendor_id']) ? intval($_POST['vendor_id']) : 0;
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($vendor_id > 0) {
        if ($action == 'reject') {
            $sql = "DELETE FROM vendor WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $vendor_id);

            if ($stmt->execute()) {
                echo "Vendor request rejected successfully.";
            } else {
                echo "Error rejecting vendor request: " . $conn->error;
            }

            $stmt->close();
        } elseif ($action == 'approve') {
            $sql = "UPDATE vendor SET status = 'approved' WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $vendor_id);

            if ($stmt->execute()) {
                echo "Vendor request approved successfully.";
            } else {
                echo "Error approving vendor request: " . $conn->error;
            }

            $stmt->close();
        }

        header("Location: request.html");
        exit();
    }
}

// Fetch pending vendor details for display
$sql = "SELECT * FROM vendor WHERE status = 'pending'";
$result = $conn->query($sql);

if ($result === FALSE) {
    die("Error fetching vendor details: " . $conn->error);
}

$vendorData = '';
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $vendorData .= "<tr>
                            <td>" . htmlspecialchars($row['name']) . "</td>
                            <td>" . htmlspecialchars($row['phone']) . "</td>
                            <td>" . htmlspecialchars($row['email']) . "</td>
                            <td>" . htmlspecialchars($row['shop_name']) . "</td>
                            <td>" . htmlspecialchars($row['shop_id']) . "</td>
                            <td>" . htmlspecialchars($row['location']) . "</td>
                            <td>
                                <form action='request.php' method='POST' style='display: inline;'>
                                    <input type='hidden' name='vendor_id' value='" . $row['id'] . "'>
                                    <button type='submit' name='action' value='approve' style='background-color: #4CAF50; color: white; border: none; padding: 5px 10px; cursor: pointer;'>Approve</button>
                                    <button type='submit' name='action' value='reject' style='background-color: #f44336; color: white; border: none; padding: 5px 10px; cursor: pointer;'>Reject</button>
                                </form>
                            </td>

                        </tr>";
    }
} else {
    $vendorData = "<tr><td colspan='7'>No pending requests</td></tr>";
}

$conn->close();

echo $vendorData; 
?>

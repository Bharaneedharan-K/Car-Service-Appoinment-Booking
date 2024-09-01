<?php
include 'db_connection.php'; // Include the database connection

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $vendor_id = isset($_POST['vendor_id']) ? intval($_POST['vendor_id']) : 0;
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($action == 'reject' && $vendor_id > 0) {
        // Delete the vendor entry from the database
        $sql = "DELETE FROM vendor WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $vendor_id);

        if ($stmt->execute()) {
            echo "Vendor request rejected successfully.";
        } else {
            echo "Error rejecting vendor request: " . $conn->error;
        }

        $stmt->close();
    } elseif ($action == 'approve' && $vendor_id > 0) {
        // You can handle the approval logic here
        echo "Vendor request approved successfully.";
    }

    // Redirect back to the vendor requests page
    header("Location: request.html");
    exit();
}

// Fetch pending vendor details for display
$sql = "SELECT * FROM vendor WHERE status = 'pending'";
$result = $conn->query($sql);

if ($result === FALSE) {
    die("Error fetching vendor details: " . $conn->error); // Add error handling for SQL query
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
                                    <input type='hidden' name='shop_id' value='" . $row['shop_id'] . "'>
                                    <button type='submit' name='action' value='approve'>Approve</button>
                                    <button type='submit' name='action' value='reject'>Reject</button>
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

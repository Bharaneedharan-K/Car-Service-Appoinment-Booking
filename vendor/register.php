<?php
include 'db_connection.php'; // Include the database connection

// Fetch pending vendor details for display
$sql = "SELECT * FROM vendor WHERE status = 'pending'";
$result = $conn->query($sql);

if ($result === FALSE) {
    die("Error fetching vendor details: " . $conn->error); // Add error handling for SQL query
}

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['name']) . "</td>
                <td>" . htmlspecialchars($row['phone']) . "</td>
                <td>" . htmlspecialchars($row['email']) . "</td>
                <td>" . htmlspecialchars($row['shop_name']) . "</td>
                <td>" . htmlspecialchars($row['shop_id']) . "</td>
                <td>" . htmlspecialchars($row['location']) . "</td>
                <td>
                    <form action='request_action.php' method='POST'>
                        <input type='hidden' name='vendor_id' value='" . $row['id'] . "'>
                        <input type='hidden' name='shop_id' value='" . $row['shop_id'] . "'>
                        <button type='submit' name='approve'>Approve</button>
                        <button type='submit' name='reject'>Reject</button>
                    </form>
                </td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='7'>No pending requests</td></tr>";
}

$conn->close();
?>
    
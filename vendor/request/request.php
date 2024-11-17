<?php
include '../db_connection.php'; // Include your DB connection file

// Fetch data for the specific shop_id, status='progress', and user details
$sql = "
    SELECT 
        my_service.s_no,
        my_service.photo,
        my_service.service_name,
        my_service.service_date,
        users.name AS user_name,
        users.car_brand,
        users.car_model,
        users.address,
        users.phone_no
    FROM 
        my_service
    JOIN 
        users 
    ON 
        my_service.username = users.username
    WHERE 
        my_service.shop_id = ? AND my_service.status = 'progress'
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $shopid); // Use shop_id from the session
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requests</title>
    <link rel="stylesheet" href="request.css"> <!-- Link to your CSS file -->
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>CityFix</h2>
        <ul>
            <li><a href="../dashboard/dashboard.html">Dashboard</a></li>
            <li><a href="../editShop/edit.php">Edit Shop Details</a></li>
            <li><a href="request.php" class="active">Request</a></li>
            <li><a href="../garage/garage_service.html">Garage Service</a></li>
            <li><a href="../home/home_service.html">Home Service</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Service Requests (In Progress)</h1>

        <!-- Display the data -->
        <div class="requests-container">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="request-card">
                        <div class="card-content">
                            <img src="../uploads/<?php echo htmlspecialchars($row['photo']); ?>" alt="Service Image" class="service-image">
                            <div class="details">
                                <h2><?php echo htmlspecialchars($row['service_name']); ?></h2>
                                <p><strong>Customer Name:</strong> <?php echo htmlspecialchars($row['user_name']); ?></p>
                                <p><strong>Service Date:</strong> <?php echo htmlspecialchars($row['service_date']); ?></p>
                                <p><strong>Car:</strong> <?php echo htmlspecialchars($row['car_brand']); ?> - <?php echo htmlspecialchars($row['car_model']); ?></p>
                                <p><strong>Address:</strong> <?php echo htmlspecialchars($row['address']); ?></p>
                                <p><strong>Phone No:</strong> <?php echo htmlspecialchars($row['phone_no']); ?></p>
                                <div class="actions">
                                    <form id="request-form-<?php echo htmlspecialchars($row['s_no']); ?>" method="POST" action="update_status.php">
                                        <input type="hidden" name="s_no" value="<?php echo htmlspecialchars($row['s_no']); ?>">
                                        <button type="button" class="reject-btn" onclick="showRejectPopup(<?php echo htmlspecialchars($row['s_no']); ?>)">Reject</button>
                                        <button type="button" onclick="completeRequest(<?php echo htmlspecialchars($row['s_no']); ?>)" class="complete-btn">Complete</button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No service requests in progress for this shop.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Reject Popup -->
    <div id="reject-popup" class="popup" style="display: none;">
        <div class="popup-content">
            <h3>Provide a Reason for Rejection</h3>
            <textarea id="reason" placeholder="Enter reason..." rows="4" cols="50"></textarea><br>
            <button onclick="submitRejectReason()">Submit</button>
            <button onclick="closeRejectPopup()">Cancel</button>
        </div>
    </div>

    <script>
        let selectedServiceNo = null;

        // Function to handle "Complete" button click
function completeRequest(serviceNo) {
    // Get the form associated with this request
    const form = document.getElementById(`request-form-${serviceNo}`);
    
    // Get the s_no and prepare data
    const formData = new FormData(form);
    formData.append('status', 'complete'); // Add the status for completion

    // Use fetch to send the form data to the server
    fetch('update_status.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert('Request marked as complete!');
        // Optionally reload the page to reflect changes
        location.reload(); // Or you can update the UI dynamically
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}


        function showRejectPopup(serviceNo) {
            selectedServiceNo = serviceNo;
            document.getElementById('reject-popup').style.display = 'block';
        }

        function closeRejectPopup() {
            document.getElementById('reject-popup').style.display = 'none';
        }

        function submitRejectReason() {
            const reason = document.getElementById('reason').value.trim();

            if (reason) {
                // Send the data to update the status and reason
                const formData = new FormData();
                formData.append('s_no', selectedServiceNo);
                formData.append('status', 'reject');
                formData.append('reason', reason);

                // Use fetch to send the data to the server without reloading the page
                fetch('update_status.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    alert('Request rejected successfully!');
                    closeRejectPopup();
                    location.reload(); // Reload the page to reflect changes
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            } else {
                alert('Please provide a reason for rejection.');
            }
        }
    </script>

</body>
</html>

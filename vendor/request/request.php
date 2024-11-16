<?php
include '../db_connection.php'; // Include your DB connection file

// Fetch data for the specific shop_id, status='progress', and user details
$sql = "
    SELECT 
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
                                <p><strong>Brand and Model:</strong> <?php echo htmlspecialchars($row['car_brand']); ?> - <?php echo htmlspecialchars($row['car_model']); ?></p>
                                <p><strong>Address:</strong> <?php echo htmlspecialchars($row['address']); ?></p>
                                <p><strong>Phone No:</strong> <?php echo htmlspecialchars($row['phone_no']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No service requests in progress for this shop.</p>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>

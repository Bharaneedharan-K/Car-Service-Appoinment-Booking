<?php
include_once '../db_connection.php';

// Initialize variables
$totalCompleted = 0;
$totalSales = 0.0;
$totalPending = 0;
$totalUsers = 0;

// Fetch total number of completed services
$queryCompleted = "SELECT COUNT(*) AS total_completed FROM my_service WHERE shop_id = ? AND status = 'complete'";
$stmtCompleted = $conn->prepare($queryCompleted);
$stmtCompleted->bind_param("i", $shopid);
$stmtCompleted->execute();
$resultCompleted = $stmtCompleted->get_result();
if ($row = $resultCompleted->fetch_assoc()) {
    $totalCompleted = $row['total_completed'];
}
$stmtCompleted->close();

// Fetch total sales amount
$querySales = "SELECT SUM(price) AS total_sales FROM my_service WHERE shop_id = ? AND status = 'complete'";
$stmtSales = $conn->prepare($querySales);
$stmtSales->bind_param("i", $shopid);
$stmtSales->execute();
$resultSales = $stmtSales->get_result();
if ($row = $resultSales->fetch_assoc()) {
    $totalSales = $row['total_sales'] ?? 0.0;
}
$stmtSales->close();

// Fetch total number of pending services
$queryPending = "SELECT COUNT(*) AS total_pending FROM my_service WHERE shop_id = ? AND status = 'progress'";
$stmtPending = $conn->prepare($queryPending);
$stmtPending->bind_param("i", $shopid);
$stmtPending->execute();
$resultPending = $stmtPending->get_result();
if ($row = $resultPending->fetch_assoc()) {
    $totalPending = $row['total_pending'];
}
$stmtPending->close();

// Fetch total number of users for the shop
$queryUsers = "SELECT COUNT(DISTINCT username) AS total_users FROM my_service WHERE shop_id = ?";
$stmtUsers = $conn->prepare($queryUsers);
$stmtUsers->bind_param("i", $shopid);
$stmtUsers->execute();
$resultUsers = $stmtUsers->get_result();
if ($row = $resultUsers->fetch_assoc()) {
    $totalUsers = $row['total_users'];
}
$stmtUsers->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="sidebar">
        <h2>CityFix</h2>
        <ul>
            <li><a href="dashboard.php" class="active">Dashboard</a></li>
            <li><a href="../editShop/edit.php">Edit Shop Details</a></li>
            <li><a href="../complectService/complect.php">Complected Service</a></li>
            <li><a href="../request/request.php">Request</a></li>
            <li><a href="../garage/garage_service.html">Garage Service</a></li>
            <li><a href="../home/home_service.html">Home Service</a></li>
        </ul>
    </div>

    <div class="main-content">
    
        <div class="card-container">
    <div class="card">
        <h3>Total Services Completed</h3>
        <p><?= $totalCompleted ?></p>
    </div>
    <div class="card">
        <h3>Total Sales Amount</h3>
        <p>₹<?= number_format($totalSales, 2) ?></p>
    </div>
    <div class="card">
        <h3>Pending Services</h3>
        <p><?= $totalPending ?></p>
    </div>
    <div class="card">
        <h3>Total Users</h3>
        <p><?= $totalUsers ?></p>
    </div>
</div>


    </div>
</body>
</html>

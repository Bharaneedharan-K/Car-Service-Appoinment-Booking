<?php
require '../db_connection.php';

if (!isset($_SESSION['username'])) {
    echo "Please log in to view your services.";
    exit;
}

$username = $_SESSION['username'];

$sql = "
    SELECT 
        my_service.photo AS service_photo, 
        my_service.shop_id, 
        vendor.shop_name, 
        my_service.service_name, 
        my_service.price, 
        my_service.service_date, 
        vendor.phone AS vendor_phone, 
        vendor.location AS vendor_location, 
        vendor.google_map_location_url 
    FROM my_service 
    INNER JOIN vendor ON my_service.shop_id = vendor.shop_id
    WHERE my_service.username = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Services</title>
    <style>
        .card {
            display: flex;
            flex-direction: row;
            align-items: center;
            margin: 15px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .card img {
            width: 150px;
            height: 100px;
            object-fit: cover;
            margin-right: 15px;
            border-radius: 8px;
        }
        .card-details {
            flex: 1;
        }
        .card button {
            margin-top: 10px;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .card button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>My Services</h1>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="card">
            <img src="<?= htmlspecialchars($row['service_photo']) ?>" alt="Service Photo">
            <div class="card-details">
                <h3><?= htmlspecialchars($row['shop_name']) ?></h3>
                <p><strong>Shop ID:</strong> <?= htmlspecialchars($row['shop_id']) ?></p>
                <p><strong>Service:</strong> <?= htmlspecialchars($row['service_name']) ?></p>
                <p><strong>Price:</strong> ₹<?= htmlspecialchars($row['price']) ?></p>
                <p><strong>Date:</strong> <?= htmlspecialchars($row['service_date']) ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($row['vendor_phone']) ?></p>
                <p><strong>Location:</strong> <?= htmlspecialchars($row['vendor_location']) ?></p>
                <a href="<?= htmlspecialchars($row['google_map_location_url']) ?>" target="_blank">
                    <button>View on Map</button>
                </a>
            </div>
        </div>
    <?php endwhile; ?>
    <?php if ($result->num_rows === 0): ?>
        <p>No services found.</p>
    <?php endif; ?>
</body>
</html>

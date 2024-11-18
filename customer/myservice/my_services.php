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
    <link rel="stylesheet" href="myservice.css">
    
</head>
<body>
    <div class="button-group">
        <button onclick="showSection('default-services')" class="active">Progess</button>
        <button onclick="showSection('all-services')">History</button>
    </div>

    <div id="default-services" class="service-section">
        <!-- Default services -->
        <?php while ($row = $result->fetch_assoc()): ?>
            <?php if ($row['price'] > 50000): ?>

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
            <?php endif; ?>
        <?php endwhile; ?>
        <?php if ($result->num_rows === 0): ?>
            <p>No default services found.</p>
        <?php endif; ?>
    </div>

    <div id="all-services" class="service-section hidden">
        <!-- All services -->
        <?php 
        // Re-execute the query for all services
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()): ?>
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
    </div>

    <script>
        window.onload = function() {
            // Ensure the default services are shown by default
            showSection('default-services');
        };

        function showSection(sectionId) {
            // Hide all sections
            const sections = document.querySelectorAll('.service-section');
            sections.forEach(section => section.classList.add('hidden'));

            // Show the selected section
            document.getElementById(sectionId).classList.remove('hidden');

            // Update the active button
            document.querySelectorAll('.button-group button').forEach(button => {
                button.classList.remove('active');
            });

            // Add active class to the clicked button
            const activeButton = document.querySelector(`.button-group button[onclick="showSection('${sectionId}')"]`);
            if (activeButton) {
                activeButton.classList.add('active');
            }
        }
    </script>
</body>
</html>

<!-- edit.html -->
<?php
include '../db_connection.php';

// Check if shop_id is set in session
if ($shopid !== "") {
    // Prepare and execute SQL statement to fetch vendor details
    $stmt = $conn->prepare("SELECT * FROM vendor WHERE shop_id = ?");
    $stmt->bind_param("s", $shopid);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the vendor data
    $vendor = $result->fetch_assoc();
    
    // Close the statement
    $stmt->close();
} else {
    // Handle the case where shop_id is not found in session
    die("Shop ID not found.");
}

// Get the image path
$image_path = !empty($vendor['shop_photo']) ? "../../uploads/" . $vendor['shop_photo'] : "img/blog/b6.jpg";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Shop Details</title>
    <link rel="stylesheet" href="editshop.css">
    <style>
        /* Simple CSS for notification */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #4CAF50; /* Green */
            color: white;
            padding: 15px;
            border-radius: 5px;
            display: none; /* Hidden by default */
            z-index: 1000; /* Sit on top */
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Notification -->
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="notification" id="notification">Shop details updated successfully!</div>
        <?php endif; ?>

        <!-- Sidebar -->
        <div class="sidebar">
            <h2>CityFix</h2>
            <ul>
                <li><a href="../dashboard/dashboard.html">Dashboard</a></li>
                <li><a href="edit.html" class="active">Edit Shop Details</a></li>
                <li><a href="../request/request.html">Request</a></li>
                <li><a href="../garage/garage_service.html">Garage Service</a></li>
                <li><a href="../home/home_service.html">Home Service</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="profile-picture">
                <h2>Edit Shop Details</h2>
                <div id="image-container">
                    <img src="<?php echo $image_path; ?>" alt="Shop Image" id="shop_image_preview" onclick="showChangeButton()">
                    <input type="file" id="shop_image" name="shop_image" accept="image/*" style="display: none;" onchange="updateImagePreview()">
                    <button id="change_image_button" style="display: none;" onclick="document.getElementById('shop_image').click();">Change Image</button>
                </div>
            </div>
            <form class="edit-form" action="update_vendor.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($vendor['name']); ?>" placeholder="Enter Your Name" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number:</label>
                    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($vendor['phone']); ?>" placeholder="Enter Your Phone Number" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($vendor['email']); ?>" placeholder="Enter Your Email" required>
                </div>
                <div class="form-group">
                    <label for="shop_name">Shop Name:</label>
                    <input type="text" id="shop_name" name="shop_name" value="<?php echo htmlspecialchars($vendor['shop_name']); ?>" placeholder="Enter Shop Name" required>
                </div>
                <div class="form-group">
                    <label for="gst_no">GST Number:</label>
                    <input type="text" id="gst_no" name="gst_no" value="<?php echo htmlspecialchars($vendor['gst_no']); ?>" placeholder="Enter GST Number" required>
                </div>
                <div class="form-group">
                    <label for="location">Location:</label>
                    <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($vendor['location']); ?>" placeholder="Enter Location" required>
                </div>
                <div class="form-group">
                    <label for="district">District:</label>
                    <input type="text" id="district" name="district" value="<?php echo htmlspecialchars($vendor['district']); ?>" placeholder="Enter District" required>
                </div>
                <div class="form-group">
                    <label for="map_url">Google Map Location URL:</label>
                    <input type="url" id="map_url" name="map_url" value="<?php echo htmlspecialchars($vendor['google_map_location_url']); ?>" placeholder="Enter Google Map URL" required>
                </div>
                <div class="buttons">
                    <button type="button" class="cancel-btn">Cancel</button>
                    <button type="submit" class="save-btn">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showChangeButton() {
            document.getElementById('change_image_button').style.display = 'block';
        }

        function updateImagePreview() {
            const file = document.getElementById('shop_image').files[0];
            const preview = document.getElementById('shop_image_preview');
            const reader = new FileReader();

            reader.onloadend = function() {
                preview.src = reader.result;
                document.getElementById('change_image_button').style.display = 'none';
            }

            if (file) {
                reader.readAsDataURL(file);
            }
        }

        // Show notification if exists
        const notification = document.getElementById('notification');
        if (notification) {
            notification.style.display = 'block'; // Show the notification
            setTimeout(() => {
                notification.style.display = 'none'; // Hide after 3 seconds
            }, 3000);
        }
    </script>
</body>
</html>

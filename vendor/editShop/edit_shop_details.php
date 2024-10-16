<?php
include '../db_connection.php'; // Include the database connection

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Retrieve shop_id from session
if (!isset($_SESSION['shop_id'])) {
    die("Error: No shop ID in session.");
}
$shop_id = $_SESSION['shop_id'];

// Fetch shop details from the database
$sql = "SELECT * FROM vendor WHERE shop_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $shop_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $shop = $result->fetch_assoc();
    echo json_encode($shop);
} else {
    echo json_encode(['error' => 'Shop not found']);
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Shop Details</title>
    <link rel="stylesheet" href="editshop.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2>CityFix</h2>
            <ul>
                <li><a href="../dashboard/dashboard.html">Dashboard</a></li>
                <li><a href="edit_shop_details.php" class="active">Edit Shop Details</a></li>
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
                    <img src="" alt="Shop Image" id="shop_image_preview" onclick="showChangeButton()">
                    <input type="file" id="shop_image" name="shop_image" accept="image/*" style="display: none;" onchange="updateImagePreview()">
                    <button id="change_image_button" style="display: none;" onclick="document.getElementById('shop_image').click();">Change Image</button>
                </div>
            </div>
            <form class="edit-form">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number:</label>
                    <input type="text" id="phone" name="phone" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="shop_name">Shop Name:</label>
                    <input type="text" id="shop_name" name="shop_name" required>
                </div>
                <div class="form-group">
                    <label for="gst_no">GST Number:</label>
                    <input type="text" id="gst_no" name="gst_no" required>
                </div>
                <div class="form-group">
                    <label for="location">Location:</label>
                    <input type="text" id="location" name="location" required>
                </div>
                <div class="form-group">
                    <label for="district">District:</label>
                    <input type="text" id="district" name="district" required>
                </div>
                <div class="form-group">
                    <label for="map_url">Google Map Location URL:</label>
                    <input type="url" id="map_url" name="map_url" required>
                </div>
                <div class="buttons">
                    <button type="button" class="cancel-btn">Cancel</button>
                    <button type="submit" class="save-btn">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch('fetch_shop_details.php')
                .then(response => {
                    if (!response.ok) {
                        console.error('HTTP error:', response.statusText);
                        return response.text();  // Get the raw response text
                    }
                    return response.json();
                })
                .then(data => {
                    // Log the data to see if it's being fetched correctly
                    console.log('Fetched data:', data);

                    // Ensure data is an object and has the expected properties
                    if (typeof data === 'object' && data !== null) {
                        if (data.error) {
                            console.error('Error:', data.error);
                        } else {
                            // Fill the form fields with fetched data
                            document.getElementById('name').value = data.name || '';
                            document.getElementById('phone').value = data.phone || '';
                            document.getElementById('email').value = data.email || '';
                            document.getElementById('shop_name').value = data.shop_name || '';
                            document.getElementById('gst_no').value = data.gst_no || '';
                            document.getElementById('location').value = data.location || '';
                            document.getElementById('district').value = data.district || '';
                            document.getElementById('map_url').value = data.google_map_location_url || '';

                            // Load the shop image
                            const shopImage = document.getElementById('shop_image_preview');
                            const shopPhoto = data.shop_photo ? data.shop_photo.replace('..', '.') : ''; // Adjust the path
                            if (shopPhoto) {
                                shopImage.src = shopPhoto; // Set the image source
                            } else {
                                console.warn('Shop photo is not available.');
                            }
                        }
                    } else {
                        console.error('Fetched data is not an object:', data);
                    }
                })
                .catch(error => console.error('Error fetching shop details:', error));
        });

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
    </script>
</body>
</html>

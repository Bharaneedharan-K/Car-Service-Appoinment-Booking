<?php
// Database connection
$servername = "localhost";
$username = "root"; // Adjust your database username
$password = ""; // Adjust your database password
$dbname = "carservice";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = $conn->real_escape_string($_POST['name']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $email = $conn->real_escape_string($_POST['email']);
    $shop_name = $conn->real_escape_string($_POST['shop_name']);
    $shop_id = $conn->real_escape_string($_POST['shop_id']);
    $location = $conn->real_escape_string($_POST['location']);
    $shop_photo = $_FILES['shop_photo']['name'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Initialize a variable to track errors
    $errors = [];

    // Check if passwords match
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Target directory for file upload
    $target_dir = "../uploads/";

    // Check if the uploads directory exists and is writable
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    if (!is_writable($target_dir)) {
        $errors[] = "Upload directory is not writable.";
    }

    $target_file = $target_dir . basename($shop_photo);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is an actual image or fake image
    $check = getimagesize($_FILES["shop_photo"]["tmp_name"]);
    if ($check === false) {
        $errors[] = "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size (limit to 2MB)
    if ($_FILES["shop_photo"]["size"] > 2000000) {
        $errors[] = "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        $errors[] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo $error . "<br>";
            }
        }
        echo "Your file was not uploaded.";
    } else {
        // If all checks are passed, upload file and insert data
        if (move_uploaded_file($_FILES["shop_photo"]["tmp_name"], $target_file)) {
            // Prepare SQL query to insert data
            $sql = "INSERT INTO vendor (name, phone, email, shop_name, shop_id, location, shop_photo, password, status)
                    VALUES ('$name', '$phone', '$email', '$shop_name', '$shop_id', '$location', '$target_file', '$hashed_password', 'pending')";

            if ($conn->query($sql) === TRUE) {
                echo "New vendor registered successfully!";
            } else {
                // Check for duplicate entry error
                if ($conn->errno == 1062) {
                    // Extract the field causing the duplicate entry
                    $duplicate_field = "";
                    if (strpos($conn->error, "phone") !== false) {
                        $duplicate_field = "Phone number";
                    } elseif (strpos($conn->error, "email") !== false) {
                        $duplicate_field = "Email ID";
                    } elseif (strpos($conn->error, "shop_id") !== false) {
                        $duplicate_field = "Shop ID";
                    }
                    echo $duplicate_field . " already exists. Please use a unique value.";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

$conn->close();
?>

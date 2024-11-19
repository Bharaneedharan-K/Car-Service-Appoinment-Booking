<?php
require '../db_connection.php';

if (!isset($_SESSION['username'])) {
    echo "Please log in to view your services.";
    exit;
}

$username = $_SESSION['username'];
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
        <button onclick="showSection('progress')" class="active">Progress</button>
        <button onclick="showSection('history')">History</button>
    </div>

    <div id="progress" class="service-section">
        <!-- Progress services will be dynamically loaded here -->
    </div>

    <div id="history" class="service-section hidden">
        <!-- History services will be dynamically loaded here -->
    </div>

    <script>
    window.onload = function() {
        // Load progress services by default
        loadServices('progress');
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

        const activeButton = document.querySelector(`.button-group button[onclick="showSection('${sectionId}')"]`);
        if (activeButton) {
            activeButton.classList.add('active');
        }

        // Load services for the selected section
        loadServices(sectionId);
    }

    function loadServices(section) {
        const sectionElement = document.getElementById(section);
        sectionElement.innerHTML = ''; // Clear previous content

        const phpFile = section === 'progress' ? 'load_progress_services.php' : 'load_history_services.php';

        const xhr = new XMLHttpRequest();
        xhr.open('GET', `${phpFile}?t=${new Date().getTime()}`, true); // Append timestamp to prevent caching
        xhr.onload = function() {
            if (xhr.status === 200) {
                sectionElement.innerHTML = xhr.responseText; // Populate with new data
            } else {
                sectionElement.innerHTML = '<p>Error loading services.</p>';
            }
        };
        xhr.send();
    }
    </script>
</body>
</html>

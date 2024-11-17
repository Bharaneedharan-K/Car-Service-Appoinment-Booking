<?php
include('../db_connection.php');
session_start();

$username = $_SESSION['username'];

$response = [
    "success" => false,
    "message" => "",
    "progress" => [],
    "history" => []
];

try {
    // Fetch progress services
    $stmt = $conn->prepare("
        SELECT * FROM my_service 
        WHERE username = ? AND status = 'progress'
    ");
    $stmt->execute([$username]);
    $response['progress'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch completed/rejected services
    $stmt = $conn->prepare("
        SELECT * FROM my_service 
        WHERE username = ? AND status IN ('reject', 'complete')
    ");
    $stmt->execute([$username]);
    $response['history'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $response['success'] = true;
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>

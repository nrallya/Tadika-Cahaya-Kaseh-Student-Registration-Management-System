<?php
session_start();
include 'connection.php'; // Include your database connection

// Check if the request is a POST request and action is set
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['action']) && $data['action'] === 'notify') {
        $message = "Please check the status of your child!";
        
        // Insert notification into the database
        $stmt = $conn->prepare("INSERT INTO notifications (message, created_at) VALUES (?, NOW())");
        if ($stmt) {
            $stmt->bind_param("s", $message);
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => $stmt->error]);
            }
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid action']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>

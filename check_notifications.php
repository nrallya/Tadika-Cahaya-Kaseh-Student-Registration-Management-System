<?php
session_start();
include 'connection.php';

$user_id = $_SESSION['userID']; // Assuming the user is logged in and session holds the user ID

// Fetch the latest unread notification
$stmt = $conn->prepare("SELECT id, message FROM notifications WHERE user_id = ? AND status = 'unread' ORDER BY created_at DESC LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($notification_id, $message);
$stmt->fetch();

if ($message) {
    // If there's an unread notification, return it
    echo json_encode(['new_notification' => true, 'message' => $message, 'notification_id' => $notification_id]);
} else {
    // No new notifications
    echo json_encode(['new_notification' => false]);
}

$stmt->close();
?>

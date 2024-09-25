<?php
session_start();
include 'connection.php';

$data = json_decode(file_get_contents("php://input"), true);
$notification_id = $data['notification_id'];

$stmt = $conn->prepare("UPDATE notifications SET status = 'read' WHERE id = ?");
$stmt->bind_param("i", $notification_id);
$stmt->execute();
$stmt->close();
?>

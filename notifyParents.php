<?php
session_start();
include "connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $class_id = $_POST['class_id'];

    if ($class_id) {
        $today = date("Y-m-d");

        // Update the daily_update table to set the ready_for_pickup flag for all children in the class
        $stmt = $conn->prepare("UPDATE daily_update SET ready_for_pickup = 1 WHERE class_id = ? AND date = ?");
        $stmt->bind_param("is", $class_id, $today);

        if ($stmt->execute()) {
            echo "Parents have been notified to pick up their children.";
        } else {
            echo "Failed to send notifications.";
        }

        $stmt->close();
    } else {
        echo "No class ID provided.";
    }
} else {
    echo "Invalid request.";
}
?>

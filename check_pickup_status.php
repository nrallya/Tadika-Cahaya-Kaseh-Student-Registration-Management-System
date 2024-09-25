<?php
session_start();
include 'connection.php'; // Include your database connection

$child_id = $_SESSION['child_id']; // Assuming child_id is stored in the session
$today = date("Y-m-d");

$stmt = $conn->prepare("SELECT ready_for_pickup FROM daily_update WHERE child_id = ? AND date = ?");
$stmt->bind_param("is", $child_id, $today);
$stmt->execute();
$stmt->bind_result($ready_for_pickup);
$stmt->fetch();
$stmt->close();

echo $ready_for_pickup; // This will return 1 if ready, 0 if not
?>

<?php
session_start();
include "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $class_id = $_POST['class_id'];
    $staff_id = $_POST['staff_id'];
    $update_text = $_POST['update_text'];
    $date = date("Y-m-d");

    $stmt = $conn->prepare("INSERT INTO daily_update (class_id, staff_id, date, update_text) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $class_id, $staff_id, $date, $update_text);

    if ($stmt->execute()) {
        echo "Update submitted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: staffmainpage.php");
    exit();
}
?>

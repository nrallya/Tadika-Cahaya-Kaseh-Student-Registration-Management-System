<?php
session_start();
include 'connection.php';

$email = $_POST['email'];
$password = $_POST['password'];

// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare("SELECT * FROM staff WHERE email = ? AND password = ?");
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION['staffID'] = $row['staffID'];
    $_SESSION['class_id'] = $row['class_id'];
    header("Location: syllabusmanagement.php");
} else {
    echo "Invalid email or password.";
}
$stmt->close();
$conn->close();
?>

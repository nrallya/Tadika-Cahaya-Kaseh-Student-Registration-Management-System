<?php
session_start();

// Include the database connection file
include 'connection.php';

if (!isset($_SESSION['staffID'])) {
    die("Session expired. Please log in again.");
}

$staffID = $_SESSION['staffID'];

// Fetch current staff member details
$sqlStaff = "SELECT * FROM staff WHERE staffID = ?";
$stmtStaff = $conn->prepare($sqlStaff);

if (!$stmtStaff) {
    die("Staff statement preparation failed: " . $conn->error);
}

$stmtStaff->bind_param("i", $staffID);
$stmtStaff->execute();
$resultStaff = $stmtStaff->get_result();

if ($resultStaff->num_rows > 0) {
    $staff = $resultStaff->fetch_assoc();
} else {
    echo "Staff details not found.";
    exit;
}

$stmtStaff->close();

// Validate form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate old password
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Verify old password
    $storedPasswordHash = $staff['password'];
    echo "Stored Password Hash: " . $storedPasswordHash . "<br>"; // Debugging
    echo "Input Current Password: " . $currentPassword . "<br>"; // Debugging

    if (!password_verify($currentPassword, $storedPasswordHash)) {
        echo "Incorrect old password. Please try again.";
        exit();
    }

    // Validate new password requirements
    if ($newPassword != $confirmPassword) {
        echo "New password and confirm password do not match.";
        exit();
    }

    // Password strength validation (example: minimum length)
    if (strlen($newPassword) < 8) {
        echo "Password must be at least 8 characters long.";
        exit();
    }

    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update password in database
    $sqlUpdatePassword = "UPDATE staff SET password = ? WHERE staffID = ?";
    $stmtUpdatePassword = $conn->prepare($sqlUpdatePassword);

    if (!$stmtUpdatePassword) {
        die("Update statement preparation failed: " . $conn->error);
    }

    $stmtUpdatePassword->bind_param("si", $hashedPassword, $staffID);
    if ($stmtUpdatePassword->execute()) {
        echo "Password updated successfully.";
    } else {
        echo "Error updating password: " . $stmtUpdatePassword->error;
    }

    $stmtUpdatePassword->close();
}

$conn->close();
?>

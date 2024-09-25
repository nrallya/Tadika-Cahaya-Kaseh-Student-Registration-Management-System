<?php
session_start();


// Database connection
$conn = new mysqli('localhost', 'root', '', 'tadika cahaya kaseh');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userID = $_SESSION['userID'];

// Fetch current user details
$sqlUser = "SELECT * FROM user WHERE userID = ?";
$stmtUser = $conn->prepare($sqlUser);

if (!$stmtUser) {
    die("User statement preparation failed: " . $conn->error);
}

$stmtUser->bind_param("i", $userID);
$stmtUser->execute();
$resultUser = $stmtUser->get_result();

if ($resultUser->num_rows > 0) {
    $user = $resultUser->fetch_assoc();
} else {
    echo "User details not found.";
    exit;
}

$stmtUser->close();

// Validate form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate old password
    $oldPassword = $_POST['old_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Verify old password
    $storedPasswordHash = $user['password']; // Assuming 'password' is the column name in the 'user' table
    if (!password_verify($oldPassword, $storedPasswordHash)) {
        die("Incorrect old password. Please try again.");
    }

    // Validate new password requirements
    if ($newPassword != $confirmPassword) {
        die("New password and confirm password do not match.");
    }

    // Password strength validation (example: minimum length)
    if (strlen($newPassword) < 8) {
        die("Password must be at least 8 characters long.");
    }

    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update password in database
    $sqlUpdatePassword = "UPDATE user SET password = ? WHERE userID = ?";
    $stmtUpdatePassword = $conn->prepare($sqlUpdatePassword);

    if (!$stmtUpdatePassword) {
        die("Update statement preparation failed: " . $conn->error);
    }

    $stmtUpdatePassword->bind_param("si", $hashedPassword, $userID);
    if ($stmtUpdatePassword->execute()) {
        echo "Password updated successfully.";
        // Optionally, you can update the $user array with the new hashed password here
        $user['password'] = $hashedPassword;
    } else {
        echo "Error updating password: " . $stmtUpdatePassword->error;
    }

    $stmtUpdatePassword->close();
}

$conn->close();
?>

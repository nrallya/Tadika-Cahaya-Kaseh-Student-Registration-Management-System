<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    die("Session expired. Please log in again.");
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'tadika cahaya kaseh');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userID = $_SESSION['userID'];

// Sanitize and validate inputs
$username = $conn->real_escape_string($_POST['username']);
$password = !empty($_POST['password']) ? password_hash($conn->real_escape_string($_POST['password']), PASSWORD_BCRYPT) : null;
$gender = $conn->real_escape_string($_POST['gender']);
$email = $conn->real_escape_string($_POST['email']);
$contact_number = $conn->real_escape_string($_POST['contact_number']);
$father_name = $conn->real_escape_string($_POST['father_name']);
$fatherIC = $conn->real_escape_string($_POST['fatherIC']);
$fatherOcc = $conn->real_escape_string($_POST['fatherOcc']);
$fatherOccAddr = $conn->real_escape_string($_POST['fatherOccAddr']);
$fathersalary = $conn->real_escape_string($_POST['fathersalary']);
$fatherPhoneNum = $conn->real_escape_string($_POST['fatherPhoneNum']);
$mother_name = $conn->real_escape_string($_POST['mother_name']);
$motherIC = $conn->real_escape_string($_POST['motherIC']);
$motherOcc = $conn->real_escape_string($_POST['motherOcc']);
$motherOccAdd = $conn->real_escape_string($_POST['motherOccAdd']);
$mothersalary = $conn->real_escape_string($_POST['mothersalary']);
$motherPhoneNum = $conn->real_escape_string($_POST['motherPhoneNum']);
$EmergencyContact = $conn->real_escape_string($_POST['EmergencyContact']);

// Update user details
if ($password) {
    $sqlUser = "UPDATE user SET username = ?, password = ?, gender = ?, email = ?, contact_number = ? WHERE userID = ?";
    $stmtUser = $conn->prepare($sqlUser);
    $stmtUser->bind_param("sssssi", $username, $password, $gender, $email, $contact_number, $userID);
} else {
    $sqlUser = "UPDATE user SET username = ?, gender = ?, email = ?, contact_number = ? WHERE userID = ?";
    $stmtUser = $conn->prepare($sqlUser);
    $stmtUser->bind_param("ssssi", $username, $gender, $email, $contact_number, $userID);
}

if (!$stmtUser->execute()) {
    die("User update failed: " . $stmtUser->error);
}

// Update parent details
$sqlParent = "UPDATE parent SET father_name = ?, fatherIC = ?, fatherOcc = ?, fatherOccAddr = ?, fathersalary = ?, fatherPhoneNum = ?, mother_name = ?, motherIC = ?, motherOcc = ?, motherOccAdd = ?, mothersalary = ?, motherPhoneNum = ?, EmergencyContact = ? WHERE userID = ?";
$stmtParent = $conn->prepare($sqlParent);
$stmtParent->bind_param("sssssssssssssi", $father_name, $fatherIC, $fatherOcc, $fatherOccAddr, $fathersalary, $fatherPhoneNum, $mother_name, $motherIC, $motherOcc, $motherOccAdd, $mothersalary, $motherPhoneNum, $EmergencyContact, $userID);

if (!$stmtParent->execute()) {
    die("Parent update failed: " . $stmtParent->error);
}

// Close connections
$stmtUser->close();
$stmtParent->close();
$conn->close();

// Redirect or provide success message
echo "Details updated successfully.";
?>

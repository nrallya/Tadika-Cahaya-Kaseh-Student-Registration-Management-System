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

// Fetch user details based on user ID
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

// Fetch parent details based on user ID
$sqlParent = "SELECT * FROM parent WHERE userID = ?";
$stmtParent = $conn->prepare($sqlParent);

if (!$stmtParent) {
    die("Parent statement preparation failed: " . $conn->error);
}

$stmtParent->bind_param("i", $userID);
$stmtParent->execute();
$resultParent = $stmtParent->get_result();

if ($resultParent->num_rows > 0) {
    $parent = $resultParent->fetch_assoc();
} else {
    echo "Parent details not found for logged-in user.";
    exit;
}

$stmtUser->close();
$stmtParent->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Parent and User Details</title>
    <style>
    /* Reset margins and paddings */
    html, body {
        margin: 0;
        padding: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #52B4B7, #549DB7, #FBFDFF);
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .container {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        width: 90%;
        max-width: 800px;
        margin: 20px;
        text-align: center;
        overflow-x: auto;
    }

    h1 {
        color: #333;
        font-size: 35px;
        margin-bottom: 30px;
    }

    h2 {
        font-size: 24px;
        color: #333;
        margin-bottom: 10px;
        text-align: left;
        margin-top: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        background-color: #f9f9f9;
        border: 1px solid #ddd;
    }

    th, td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #f2f2f2;
        color: #333;
        font-weight: bold;
    }

    input[type="text"], input[type="password"], input[type="email"], select {
        width: calc(100% - 20px);
        padding: 8px 10px;
        margin: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
        box-sizing: border-box;
    }

    .edit-button {
        display: inline-block;
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        text-align: center;
        margin-top: 20px;
        transition: background-color 0.3s ease;
        border: none;
        cursor: pointer;
        font-size: 16px;
    }
    .back-button {
        display: inline-block;
        padding: 10px 20px;
        margin-top: 20px;
        margin-right: 10px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        font-size: 16px;
        transition: background-color 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .edit-button:hover {
        background-color: #45a049;
    }
    </style>
</head>
<body>
<div class="container">
    <h1>Edit Parent and User Details</h1>

    <!-- Form for editing parent and user details -->
    <form action="updateparentdetails.php" method="post">
        <!-- User details section -->
        <h2>User Details</h2>
        <table>
            <tr>
                <td>Username:</td>
                <td><input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>"></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td>
                    <a href="userchangepassword.php" class="edit-button">Change Password</a>
                </td>            
            </tr>
            <tr>
                <td>Gender:</td>
                <td>
                    <select name="gender">
                        <option value="Male" <?php if ($user['gender'] === 'Male') echo 'selected'; ?>>Male</option>
                        <option value="Female" <?php if ($user['gender'] === 'Female') echo 'selected'; ?>>Female</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Email:</td>
                <td><input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"></td>
            </tr>
            <tr>
                <td>Contact Number:</td>
                <td><input type="text" name="contact_number" value="<?php echo htmlspecialchars($user['contact_number']); ?>"></td>
            </tr>
        </table>

        <!-- Parent details section -->
        <h2>Father's Details</h2>
        <table>
            <tr>
                <td>Father Name:</td>
                <td><input type="text" name="father_name" value="<?php echo htmlspecialchars($parent['father_name']); ?>"></td>
            </tr>
            <tr>
                <td>Father IC:</td>
                <td><input type="text" name="fatherIC" value="<?php echo htmlspecialchars($parent['fatherIC']); ?>"></td>
            </tr>
            <tr>
                <td>Father Occupation:</td>
                <td><input type="text" name="fatherOcc" value="<?php echo htmlspecialchars($parent['fatherOcc']); ?>"></td>
            </tr>
            <tr>
                <td>Father Occupation Address:</td>
                <td><input type="text" name="fatherOccAddr" value="<?php echo htmlspecialchars($parent['fatherOccAddr']); ?>"></td>
            </tr>
            <tr>
                <td>Father Salary:</td>
                <td><input type="text" name="fathersalary" value="<?php echo htmlspecialchars($parent['fathersalary']); ?>"></td>
            </tr>
            <tr>
                <td>Father Phone Number:</td>
                <td><input type="text" name="fatherPhoneNum" value="<?php echo htmlspecialchars($parent['fatherPhoneNum']); ?>"></td>
            </tr>
        </table>

        <h2>Mother's Details</h2>
        <table>
            <tr>
                <td>Mother Name:</td>
                <td><input type="text" name="mother_name" value="<?php echo htmlspecialchars($parent['mother_name']); ?>"></td>
            </tr>
            <tr>
                <td>Mother IC:</td>
                <td><input type="text" name="motherIC" value="<?php echo htmlspecialchars($parent['motherIC']); ?>"></td>
            </tr>
            <tr>
                <td>Mother Occupation:</td>
                <td><input type="text" name="motherOcc" value="<?php echo htmlspecialchars($parent['motherOcc']); ?>"></td>
            </tr>
            <tr>
                <td>Mother Occupation Address:</td>
                <td><input type="text" name="motherOccAdd" value="<?php echo htmlspecialchars($parent['motherOccAdd']); ?>"></td>
            </tr>
            <tr>
                <td>Mother Salary:</td>
                <td><input type="text" name="mothersalary" value="<?php echo htmlspecialchars($parent['mothersalary']); ?>"></td>
            </tr>
            <tr>
                <td>Mother Phone Number:</td>
                <td><input type="text" name="motherPhoneNum" value="<?php echo htmlspecialchars($parent['motherPhoneNum']); ?>"></td>
            </tr>
        </table>

        <!-- Emergency contact section -->
        <h2>Emergency Contact</h2>
        <table>
            <tr>
                <td>Emergency Contact:</td>
                <td><input type="text" name="EmergencyContact" value="<?php echo htmlspecialchars($parent['EmergencyContact']); ?>"></td>
            </tr>
        </table>

        <!-- Submit button -->
        <button type="submit" class="edit-button">Save Changes</button>
        <a href="profileparent.php" class="back-button">Back</a>

    </form>
</div>

</body>
</html>

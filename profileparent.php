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

// Fetch user details
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
    echo "User details not found for logged-in user.";
    exit;
}

// Fetch parent details based on userID
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
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Parent Details</title>
        <style>
            /* Your styles can go here */
            .container {
                font-family: Arial, sans-serif;
                text-align: center;
                padding: 20px;
            }
            .message {
                font-size: 24px;
                margin-bottom: 20px;
            }
        </style>
        <script>
            setTimeout(function() {
                window.location.href = "parentregistration.php";
            }, 5000); // Redirect after 10 seconds
        </script>
    </head>
    <body>
        <div class="container">
            <h1>This page will directly bring you to parent registration form ....</h1>
            <div class="message">PLEASE REGISTER YOUR PARENTS DETAILS FIRST.</div>
        </div>
    </body>
    </html>';
    exit; // Exit after displaying the message
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
    <title>Parent Details</title>
    <style>
    /* Reset margins and paddings */
    html, body {
        margin: 0;
        padding: 0;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #52B4B7, #549DB7, #FBFDFF);
        min-height: 100vh;
        overflow-y: auto;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .container {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 30px;
        width: 90%;
        max-width: 800px;
        text-align: center;
    }

    h1 {
        color: #333;
        font-size: 36px;
        margin-bottom: 20px;
    }

    h2 {
        font-size: 24px;
        color: #333;
        margin-top: 30px;
        margin-bottom: 10px;
        text-align: left;
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

    .edit-button, .back-button {
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

    .edit-button:hover, .back-button:hover {
        background-color: #0056b3;
    }
    </style>
</head>
<body>

<div class="container">


    <h1>Profile Details</h1>

    <!-- User details -->
    <h2>User Details</h2>
    <table>
        <tr>
            <td><strong>Username:</strong></td>
            <td><?php echo htmlspecialchars($user['username']); ?></td>
        </tr>
        <tr>
            <td><strong>Password:</strong></td>
            <td>********</td> <!-- Displayed as asterisks for security purposes -->
        </tr>
        <tr>
            <td><strong>Gender:</strong></td>
            <td><?php echo htmlspecialchars($user['gender']); ?></td>
        </tr>
        <tr>
            <td><strong>Email:</strong></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
        </tr>
        <tr>
            <td><strong>Contact Number:</strong></td>
            <td><?php echo htmlspecialchars($user['contact_number']); ?></td>
        </tr>
    </table>

    <!-- Parent details table -->
    <h2>Parent Details</h2>
    <table>
        <tr>
            <th colspan="2">Father's Details</th>
        </tr>
        <tr>
            <td><strong>Father Name:</strong></td>
            <td><?php echo htmlspecialchars($parent['father_name']); ?></td>
        </tr>
        <tr>
            <td><strong>Father IC:</strong></td>
            <td><?php echo htmlspecialchars($parent['fatherIC']); ?></td>
        </tr>
        <tr>
            <td><strong>Father Occupation:</strong></td>
            <td><?php echo htmlspecialchars($parent['fatherOcc']); ?></td>
        </tr>
        <tr>
            <td><strong>Father Occupation Address:</strong></td>
            <td><?php echo htmlspecialchars($parent['fatherOccAddr']); ?></td>
        </tr>
        <tr>
            <td><strong>Father Salary:</strong></td>
            <td><?php echo htmlspecialchars($parent['fathersalary']); ?></td>
        </tr>
        <tr>
            <td><strong>Father Phone Number:</strong></td>
            <td><?php echo htmlspecialchars($parent['fatherPhoneNum']); ?></td>
        </tr>
        <tr>
            <th colspan="2">Mother's Details</th>
        </tr>
        <tr>
            <td><strong>Mother Name:</strong></td>
            <td><?php echo htmlspecialchars($parent['mother_name']); ?></td>
        </tr>
        <tr>
            <td><strong>Mother IC:</strong></td>
            <td><?php echo htmlspecialchars($parent['motherIC']); ?></td>
        </tr>
        <tr>
            <td><strong>Mother Occupation:</strong></td>
            <td><?php echo htmlspecialchars($parent['motherOcc']); ?></td>
        </tr>
        <tr>
            <td><strong>Mother Occupation Address:</strong></td>
            <td><?php echo htmlspecialchars($parent['motherOccAdd']); ?></td>
        </tr>
        <tr>
            <td><strong>Mother Salary:</strong></td>
            <td><?php echo htmlspecialchars($parent['mothersalary']); ?></td>
        </tr>
        <tr>
            <td><strong>Mother Phone Number:</strong></td>
            <td><?php echo htmlspecialchars($parent['motherPhoneNum']); ?></td>
        </tr>
        <tr>
            <th colspan="2">Emergency Contact </th>
            
        </tr>
        <tr>
            <td><?php echo htmlspecialchars($parent['EmergencyContact']); ?></td>
        </tr>
    </table>

    <!-- Edit Details button -->
     
    <a href="editparentdetail.php" class="edit-button">Edit Details</a>
    <a href="mainpage.php" class="back-button">Back</a>

</div>

</body>
</html>

<?php
$pageTitle = "Edit Parent Details";
session_start();

// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'tadika cahaya kaseh');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize variables
$parentID = $_GET['parentID'] ?? null;
$errors = [];

// Fetch parent and child data for the given parentID
$sql = "SELECT p.parentID, p.father_name, p.fatherIC, p.fatherOcc, p.fatherOccAddr, p.fathersalary, p.fatherPhoneNum, 
               p.mother_name, p.motherIC, p.motherOcc, p.motherOccAdd, p.mothersalary, p.motherPhoneNum, 
               p.EmergencyContact, c.childID, c.full_name, c.age, c.DOB, c.IC, c.Birthplace, c.Address, c.userID 
        FROM parent p 
        INNER JOIN child c ON p.userID = c.userID 
        WHERE p.parentID = " . mysqli_real_escape_string($conn, $parentID);
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    // Parent not found
    array_push($errors, "Parent not found.");
} else {
    $parentData = mysqli_fetch_assoc($result);
}

// Handle form submission to update parent details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $father_name = $_POST['father_name'] ?? '';
    $fatherIC = $_POST['fatherIC'] ?? '';
    $fatherOcc = $_POST['fatherOcc'] ?? '';
    $fatherOccAddr = $_POST['fatherOccAddr'] ?? '';
    $fathersalary = $_POST['fathersalary'] ?? '';
    $fatherPhoneNum = $_POST['fatherPhoneNum'] ?? '';
    $mother_name = $_POST['mother_name'] ?? '';
    $motherIC = $_POST['motherIC'] ?? '';
    $motherOcc = $_POST['motherOcc'] ?? '';
    $motherOccAdd = $_POST['motherOccAdd'] ?? '';
    $mothersalary = $_POST['mothersalary'] ?? '';
    $motherPhoneNum = $_POST['motherPhoneNum'] ?? '';
    $EmergencyContact = $_POST['EmergencyContact'] ?? '';

    // Perform update query
    $updateParentSQL = "UPDATE parent SET 
                        father_name = '" . mysqli_real_escape_string($conn, $father_name) . "',
                        fatherIC = '" . mysqli_real_escape_string($conn, $fatherIC) . "',
                        fatherOcc = '" . mysqli_real_escape_string($conn, $fatherOcc) . "',
                        fatherOccAddr = '" . mysqli_real_escape_string($conn, $fatherOccAddr) . "',
                        fathersalary = '" . mysqli_real_escape_string($conn, $fathersalary) . "',
                        fatherPhoneNum = '" . mysqli_real_escape_string($conn, $fatherPhoneNum) . "',
                        mother_name = '" . mysqli_real_escape_string($conn, $mother_name) . "',
                        motherIC = '" . mysqli_real_escape_string($conn, $motherIC) . "',
                        motherOcc = '" . mysqli_real_escape_string($conn, $motherOcc) . "',
                        motherOccAdd = '" . mysqli_real_escape_string($conn, $motherOccAdd) . "',
                        mothersalary = '" . mysqli_real_escape_string($conn, $mothersalary) . "',
                        motherPhoneNum = '" . mysqli_real_escape_string($conn, $motherPhoneNum) . "',
                        EmergencyContact = '" . mysqli_real_escape_string($conn, $EmergencyContact) . "'
                        WHERE parentID = " . mysqli_real_escape_string($conn, $parentID);

    if (mysqli_query($conn, $updateParentSQL)) {
        header("Location: parentmanagement.php"); // Redirect to parent management page after successful update
        exit();
    } else {
        array_push($errors, "Error updating parent: " . mysqli_error($conn));
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #52B4B7;
            color: white;
            padding: 15px;
            text-align: center;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: white;
            font-size: 24px;
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .form-group input {
            width: calc(100% - 20px);
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .form-group textarea {
            width: calc(100% - 20px);
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            resize: vertical;
            min-height: 100px;
        }

        .btn-submit {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-submit:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <header>
        <h1><?php echo $pageTitle; ?></h1>
    </header>

    <div class="container">
        <?php if (!empty($errors)) : ?>
            <div class="error">
                <?php foreach ($errors as $error) : ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?parentID=' . $parentID; ?>" method="POST">
            <div class="form-group">
                <label for="father_name">Father's Name:</label>
                <input type="text" id="father_name" name="father_name" value="<?php echo htmlspecialchars($parentData['father_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="fatherIC">Father's IC:</label>
                <input type="text" id="fatherIC" name="fatherIC" value="<?php echo htmlspecialchars($parentData['fatherIC']); ?>" required>
            </div>
            <div class="form-group">
                <label for="fatherOcc">Father's Occupation:</label>
                <input type="text" id="fatherOcc" name="fatherOcc" value="<?php echo htmlspecialchars($parentData['fatherOcc']); ?>">
            </div>
            <div class="form-group">
                <label for="fatherOccAddr">Father's Occupation Address:</label>
                <textarea id="fatherOccAddr" name="fatherOccAddr"><?php echo htmlspecialchars($parentData['fatherOccAddr']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="fathersalary">Father's Salary:</label>
                <input type="text" id="fathersalary" name="fathersalary" value="<?php echo htmlspecialchars($parentData['fathersalary']); ?>">
            </div>
            <div class="form-group">
                <label for="fatherPhoneNum">Father's Phone Number:</label>
                <input type="text" id="fatherPhoneNum" name="fatherPhoneNum" value="<?php echo htmlspecialchars($parentData['fatherPhoneNum']); ?>" required>
            </div>
            <div class="form-group">
                <label for="mother_name">Mother's Name:</label>
                <input type="text" id="mother_name" name="mother_name" value="<?php echo htmlspecialchars($parentData['mother_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="motherIC">Mother's IC:</label>
                <input type="text" id="motherIC" name="motherIC" value="<?php echo htmlspecialchars($parentData['motherIC']); ?>" required>
            </div>
            <div class="form-group">
                <label for="motherOcc">Mother's Occupation:</label>
                <input type="text" id="motherOcc" name="motherOcc" value="<?php echo htmlspecialchars($parentData['motherOcc']); ?>">
            </div>
            <div class="form-group">
                <label for="motherOccAdd">Mother's Occupation Address:</label>
                <textarea id="motherOccAdd" name="motherOccAdd"><?php echo htmlspecialchars($parentData['motherOccAdd']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="mothersalary">Mother's Salary:</label>
                <input type="text" id="mothersalary" name="mothersalary" value="<?php echo htmlspecialchars($parentData['mothersalary']); ?>">
            </div>
            <div class="form-group">
                <label for="motherPhoneNum">Mother's Phone Number:</label>
                <input type="text" id="motherPhoneNum" name="motherPhoneNum" value="<?php echo htmlspecialchars($parentData['motherPhoneNum']); ?>" required>
            </div>
            <div class="form-group">
                <label for="EmergencyContact">Emergency Contact:</label>
                <input type="text" id="EmergencyContact" name="EmergencyContact" value="<?php echo htmlspecialchars($parentData['EmergencyContact']); ?>">
            </div>
            <button type="submit" class="btn-submit">Update Parent Details</button>
        </form>
    </div>

</body>
</html>

<?php
$pageTitle = "Register Parent";
session_start();

// Database connection configuration
$servername = "localhost"; // Replace with your server name
$username = "root";        // Replace with your database username
$password = "";            // Replace with your database password
$dbname = "tadika cahaya kaseh"; 
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape user inputs for security
    function sanitizeInput($input) {
        global $conn;
        return mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($input))));
    }

    // Sanitize and store form data into variables
    $father_name = sanitizeInput($_POST['father_name']);
    $fatherIC = sanitizeInput($_POST['fatherIC']);
    $fatherOcc = sanitizeInput($_POST['fatherOcc']);
    $fatherOccAddr = sanitizeInput($_POST['fatherOccAddr']);
    $fatherSalary = sanitizeInput($_POST['fathersalary']);
    $fatherPhoneNum = sanitizeInput($_POST['fatherPhoneNum']);
    $mother_name = sanitizeInput($_POST['mother_name']);
    $motherIC = sanitizeInput($_POST['motherIC']);
    $motherOcc = sanitizeInput($_POST['motherOcc']);
    $motherOccAdd = sanitizeInput($_POST['motherOccAdd']);
    $motherSalary = sanitizeInput($_POST['mothersalary']);
    $motherPhoneNum = sanitizeInput($_POST['motherPhoneNum']);
    $EmergencyContact = sanitizeInput($_POST['EmergencyContact']);
    
    // Assuming userID is obtained from session or elsewhere in your application
    $userID = $_SESSION['userID'];     // Replace with actual logic to obtain userID

    // SQL query to insert data into database
    $sql = "INSERT INTO parent (father_name, fatherIC, fatherOcc, fatherOccAddr, fathersalary, fatherPhoneNum, mother_name, motherIC, motherOcc, motherOccAdd, mothersalary, motherPhoneNum, EmergencyContact, userID)
        VALUES ('$father_name', '$fatherIC', '$fatherOcc', '$fatherOccAddr', '$fatherSalary', '$fatherPhoneNum', '$mother_name', '$motherIC', '$motherOcc', '$motherOccAdd', '$motherSalary', '$motherPhoneNum', '$EmergencyContact', '$userID')";
    if ($conn->query($sql) === TRUE) {
        // Redirect to success page or next step
        header("Location: register.php");
        exit();
    } else {
        // Handle error - display an error message or log the error
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
}

// Close connection
$conn->close();
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
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .back-button {
        display: inline-block;
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        text-decoration: none;
        border: none; /* Remove default button border */
        border-radius: 4px;
        text-align: center;
        margin-top: 10px; /* Adjusted for spacing */
        transition: background-color 0.3s ease;
        cursor: pointer; /* Add cursor pointer */
    }

        h1, h2 {
            color: #52B4B7;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-top: 10px;
            color: #333;
        }

        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: #52B4B7;
            color: #fff;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #549DB7;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Register Parent</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <h2>Father Details</h2>
        <label for="father_name">Father Name:</label>
        <input type="text" id="father_name" name="father_name" required>
        
        <label for="fatherIC">Father IC:</label>
        <input type="text" id="fatherIC" name="fatherIC" required>
        
        <label for="fatherOcc">Father Occupation:</label>
        <input type="text" id="fatherOcc" name="fatherOcc" required>
        
        <label for="fatherOccAddr">Father Occupation Address:</label>
        <input type="text" id="fatherOccAddr" name="fatherOccAddr" required>
        
        <label for="fathersalary">Father Salary:</label>
        <input type="number" id="fathersalary" name="fathersalary" required>
        
        <label for="fatherPhoneNum">Father Phone Number:</label>
        <input type="text" id="fatherPhoneNum" name="fatherPhoneNum" required>

        <h2>Mother Details</h2>
        <label for="mother_name">Mother Name:</label>
        <input type="text" id="mother_name" name="mother_name" required>
        
        <label for="motherIC">Mother IC:</label>
        <input type="text" id="motherIC" name="motherIC" required>
        
        <label for="motherOcc">Mother Occupation:</label>
        <input type="text" id="motherOcc" name="motherOcc" required>
        
        <label for="motherOccAdd">Mother Occupation Address:</label>
        <input type="text" id="motherOccAdd" name="motherOccAdd" required>
        
        <label for="mothersalary">Mother Salary:</label>
        <input type="number" id="mothersalary" name="mothersalary" required>
        
        <label for="motherPhoneNum">Mother Phone Number:</label>
        <input type="text" id="motherPhoneNum" name="motherPhoneNum" required>

        <h2>Emergency Contact</h2>
        <label for="EmergencyContact">Emergency Contact:</label>
        <input type="text" id="EmergencyContact" name="EmergencyContact" required>

        <input type="submit" value="Next">
        <a href="mainpage.php" class="back-button">Back</a>

    </form>
</div>

</body>
</html>

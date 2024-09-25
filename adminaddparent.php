<?php
// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'tadika cahaya kaseh');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to fetch child names from database
function getChildNames($conn) {
    $sql = "SELECT childID, full_name FROM child";
    $result = mysqli_query($conn, $sql);
    $children = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $children[$row['childID']] = $row['full_name'];
        }
    }
    return $children;
}

// Function to fetch user IDs and names from another relevant table (e.g., user table)
function getUsers($conn) {
    $sql = "SELECT userID, username FROM user";
    $result = mysqli_query($conn, $sql);
    $users = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $users[$row['userID']] = $row['username'];
        }
    }
    return $users;
}

// Process form submission
$successMessage = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs
    $father_name = mysqli_real_escape_string($conn, $_POST['father_name']);
    $fatherIC = intval($_POST['fatherIC']); // Convert to integer
    $fatherOcc = mysqli_real_escape_string($conn, $_POST['fatherOcc']);
    $fatherOccAddr = mysqli_real_escape_string($conn, $_POST['fatherOccAddr']);
    $fathersalary = floatval($_POST['fathersalary']); // Convert to float for salary
    $fatherPhoneNum = mysqli_real_escape_string($conn, $_POST['fatherPhoneNum']);
    $mother_name = mysqli_real_escape_string($conn, $_POST['mother_name']);
    $motherIC = intval($_POST['motherIC']); // Convert to integer
    $motherOcc = mysqli_real_escape_string($conn, $_POST['motherOcc']);
    $motherOccAdd = mysqli_real_escape_string($conn, $_POST['motherOccAdd']);
    $mothersalary = floatval($_POST['mothersalary']); // Convert to float for salary
    $motherPhoneNum = mysqli_real_escape_string($conn, $_POST['motherPhoneNum']);
    $EmergencyContact = mysqli_real_escape_string($conn, $_POST['EmergencyContact']);
    $userID = isset($_POST['userID']) ? intval($_POST['userID']) : null; // Convert to integer if provided

    // Insert parent details into parent table (using prepared statement for security)
    $sql_parent = "INSERT INTO parent (father_name, fatherIC, fatherOcc, fatherOccAddr, fathersalary, fatherPhoneNum, 
                                  mother_name, motherIC, motherOcc, motherOccAdd, mothersalary, motherPhoneNum, 
                                  EmergencyContact, userID) 
               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql_parent);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sisdsdsssdsdsi", 
                       $father_name, $fatherIC, $fatherOcc, $fatherOccAddr, $fathersalary, $fatherPhoneNum, 
                       $mother_name, $motherIC, $motherOcc, $motherOccAdd, $mothersalary, $motherPhoneNum, 
                       $EmergencyContact, $userID);

        if (mysqli_stmt_execute($stmt)) {
            $parentID = mysqli_insert_id($conn); // Get the auto-generated parent ID

            // Assigning parent to child (if child ID is provided)
            if (isset($_POST['child_id'])) {
                $child_id = intval($_POST['child_id']); // Convert to integer
                $sql_assign = "UPDATE child SET parentID = $parentID WHERE childID = $child_id";
                mysqli_query($conn, $sql_assign);
            }

            // Set the success message
            $successMessage = "Parent added successfully.";

            mysqli_stmt_close($stmt);
        } else {
            echo "Error: " . mysqli_stmt_error($stmt);
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Fetch child names and user names for use in HTML form
$children = getChildNames($conn);
$users = getUsers($conn);

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Parent</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        form {
            margin-top: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
        }
        input[type="text"], input[type="number"], input[type="tel"], input[type="email"] {
            width: calc(100% - 16px);
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"], .btn-back {
            background-color: #007bff;
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
        input[type="submit"]:hover, .btn-back:hover {
            background-color: #0056b3;
        }
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>
    <script>
        function showAlert(message) {
            alert(message);
            window.location.href = "parentmanagement.php";
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Add New Parent</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="father_name">Father's Name:</label>
            <input type="text" id="father_name" name="father_name" required>

            <label for="fatherIC">Father's IC:</label>
            <input type="number" id="fatherIC" name="fatherIC" required>

            <label for="fatherOcc">Father's Occupation:</label>
            <input type="text" id="fatherOcc" name="fatherOcc" required>

            <label for="fatherOccAddr">Father's Occupation Address:</label>
            <input type="text" id="fatherOccAddr" name="fatherOccAddr" required>

            <label for="fathersalary">Father's Salary:</label>
            <input type="number" id="fathersalary" name="fathersalary" step="0.01" required>

            <label for="fatherPhoneNum">Father's Phone Number:</label>
            <input type="tel" id="fatherPhoneNum" name="fatherPhoneNum" required>

            <label for="mother_name">Mother's Name:</label>
            <input type="text" id="mother_name" name="mother_name" required>

            <label for="motherIC">Mother's IC:</label>
            <input type="number" id="motherIC" name="motherIC" required>

            <label for="motherOcc">Mother's Occupation:</label>
            <input type="text" id="motherOcc" name="motherOcc" required>

            <label for="motherOccAdd">Mother's Occupation Address:</label>
            <input type="text" id="motherOccAdd" name="motherOccAdd" required>

            <label for="mothersalary">Mother's Salary:</label>
            <input type="number" id="mothersalary" name="mothersalary" step="0.01" required>

            <label for="motherPhoneNum">Mother's Phone Number:</label>
            <input type="tel" id="motherPhoneNum" name="motherPhoneNum" required>

            <label for="EmergencyContact">Emergency Contact:</label>
            <input type="tel" id="EmergencyContact" name="EmergencyContact" required>

            <!-- User ID Selection -->
            <label for="userID">User Name (if applicable):</label>
            <select id="userID" name="userID">
                <option value="">Select User Name</option>
                <?php foreach ($users as $userID => $username) : ?>
                    <option value="<?php echo $userID; ?>"><?php echo $username; ?></option>
                <?php endforeach; ?>
            </select>

            <input type="submit" value="Add Parent">
            <!-- Back Button -->
            <a href="parentmanagement.php" class="btn-back">Back to Parent Management</a>
        </form>
    </div>
    <?php if ($successMessage) : ?>
        <script>
            showAlert("<?php echo $successMessage; ?>");
        </script>
    <?php endif; ?>
</body>
</html>

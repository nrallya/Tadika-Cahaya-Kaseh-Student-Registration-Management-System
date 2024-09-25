<?php
$pageTitle = "Add Student";
session_start();

// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'tadika cahaya kaseh');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch parents data
$sqlParents = "SELECT parentID, father_name, mother_name FROM parent";
$resultParents = mysqli_query($conn, $sqlParents);

// Fetch staff's class ID based on staff ID from session
if (isset($_SESSION['staffID'])) {
    $staffID = $_SESSION['staffID'];

    // Fetch the class_id associated with the staff
    $sql_class = "SELECT class_id FROM staff WHERE staffID = $staffID";
    $result_class = mysqli_query($conn, $sql_class);

    if ($result_class && mysqli_num_rows($result_class) > 0) {
        $row_class = mysqli_fetch_assoc($result_class);
        $class_id = $row_class['class_id'];
    } else {
        // Handle error if class_id is not found
        die("Error: Class ID not found for staff ID " . $staffID);
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $age = $_POST['age'];
    $DOB = $_POST['DOB'];
    $IC = $_POST['IC'];
    $Birthplace = $_POST['Birthplace'];
    $Address = $_POST['Address'];
    $userID = $_POST['userID']; // Parent ID

    // Insert new child data into child table with class_id
    $insertSql = "INSERT INTO child (full_name, age, DOB, IC, Birthplace, Address, userID, class_id) 
                  VALUES ('$full_name', '$age', '$DOB', '$IC', '$Birthplace', '$Address', '$userID', '$class_id')";

    if (mysqli_query($conn, $insertSql)) {
        echo "<script>alert('Student added successfully'); window.location.href = 'staffstudentmanagement.php';</script>";
    } else {
        echo "Error: " . $insertSql . "<br>" . mysqli_error($conn);
    }
}
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
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: white;
        }

        h2 {
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            color: #333;
        }

        input[type="text"], input[type="date"], input[type="number"], select {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
        }

        button {
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .back-btn {
            display: block;
            width: 120px;
            padding: 10px;
            margin: 20px 0;
            background-color: #007BFF;
            color: white;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            margin: 20px auto;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <h1><?php echo $pageTitle; ?></h1>
    </header>

    <div class="container">
        <h2>Add New Student</h2>
        <form method="POST">
            <label for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name" required>

            <label for="age">Age</label>
            <input type="number" id="age" name="age" required>

            <label for="DOB">Date of Birth</label>
            <input type="date" id="DOB" name="DOB" required>

            <label for="IC">IC</label>
            <input type="text" id="IC" name="IC" required>

            <label for="Birthplace">Birthplace</label>
            <input type="text" id="Birthplace" name="Birthplace" required>

            <label for="Address">Address</label>
            <input type="text" id="Address" name="Address" required>

            <label for="userID">Select Parent</label>
            <select id="userID" name="userID" required>
                <option value="">Select Parent</option>
                <?php
                if (mysqli_num_rows($resultParents) > 0) {
                    while ($row = mysqli_fetch_assoc($resultParents)) {
                        echo "<option value='" . $row['parentID'] . "'>" . $row['father_name'] . " / " . $row['mother_name'] . "</option>";
                    }
                } else {
                    echo "<option value=''>No parents found</option>";
                }
                ?>
            </select>

            <button type="submit">Add Student</button>
        </form>
        <a href="staffstudentmanagement.php" class="back-btn">Back to Student Management</a>
    </div>
</body>
</html>
<?php mysqli_close($conn); ?>

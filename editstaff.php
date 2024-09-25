<?php
$pageTitle = "Edit Staff";
session_start();

// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'tadika cahaya kaseh');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize variables
$staffID = "";
$name = "";
$password = "";
$age = "";
$gender = "";
$phonenum = "";
$email = "";
$address = "";

// Check if staffID is set in URL
if (isset($_GET['staffID'])) {
    $staffID = $_GET['staffID'];

    // Fetch staff data based on staffID
    $sql = "SELECT * FROM staff WHERE staffID = $staffID";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $name = $row['name'];
        $password = $row['password'];
        $age = $row['age'];
        $gender = $row['gender'];
        $phonenum = $row['phonenum'];
        $email = $row['email'];
        $address = $row['address'];
    } else {
        echo "No staff found with the given ID.";
    }
}

// Handle form submission for updating staff details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $staffID = $_POST['staffID'];
    $name = $_POST['name'];
    $password = $_POST['password'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $phonenum = $_POST['phonenum'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    $sql = "UPDATE staff SET name='$name', password='$password', age=$age, gender='$gender', phonenum='$phonenum', email='$email', address='$address' WHERE staffID=$staffID";

    if (mysqli_query($conn, $sql)) {
        header("Location: staffmanagement.php");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
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
            margin: 30px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
            font-weight: bold;
        }

        input, select {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            background-color: #52B4B7;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: #439a99;
        }

        a {
            color: #007BFF;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <h1><?php echo $pageTitle; ?></h1>
    </header>

    <div class="container">
        <h1>Edit Staff Details</h1>
        <form method="post" action="editstaff.php">
            <input type="hidden" name="staffID" value="<?php echo $staffID; ?>">

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $name; ?>" required>

            <label for="password">Password:</label>
            <input type="text" id="password" name="password" value="<?php echo $password; ?>" required>

            <label for="age">Age:</label>
            <input type="number" id="age" name="age" value="<?php echo $age; ?>" required>

            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="male" <?php if ($gender == 'male') echo 'selected'; ?>>Male</option>
                <option value="female" <?php if ($gender == 'female') echo 'selected'; ?>>Female</option>
            </select>

            <label for="phonenum">Phone Number:</label>
            <input type="text" id="phonenum" name="phonenum" value="<?php echo $phonenum; ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" value="<?php echo $address; ?>" required>

            <button type="submit" name="update">Update</button>
        </form>
        <a href="staffmanagement.php">Back to Staff List</a>
    </div>
</body>
</html>
<?php mysqli_close($conn); ?>

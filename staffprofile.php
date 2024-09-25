<?php
session_start();

$pageTitle = "Edit Staff Profile";

// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'tadika cahaya kaseh');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get staffID from session
if (!isset($_SESSION['staffID'])) {
    // Redirect to login if staffID is not set in session
    header("Location: login.php");
    exit;
}

$staffID = $_SESSION['staffID'];

// Fetch staff data based on staffID
$sql = "SELECT s.*, c.class_name 
        FROM staff s
        LEFT JOIN class c ON s.class_id = c.class_id
        WHERE s.staffID = $staffID";

$result = mysqli_query($conn, $sql);

if (!$result) {
    echo "Error fetching staff record: " . mysqli_error($conn);
    exit;
}

$staff = mysqli_fetch_assoc($result);

if (!$staff) {
    echo "No staff record found for ID: " . $staffID;
    exit;
}

// Initialize variables to hold current data
$name = isset($staff['name']) ? $staff['name'] : '';
$age = isset($staff['age']) ? $staff['age'] : '';
$gender = isset($staff['gender']) ? $staff['gender'] : '';
$phone = isset($staff['phonenum']) ? $staff['phonenum'] : '';
$email = isset($staff['email']) ? $staff['email'] : '';
$address = isset($staff['address']) ? $staff['address'] : '';
$class_id = isset($staff['class_id']) ? $staff['class_id'] : '';
$class_name = isset($staff['class_name']) ? $staff['class_name'] : '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate inputs
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $age = intval($_POST['age']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $class_id = intval($_POST['class_id']);

    // Update staff data
    $updateSql = "UPDATE staff SET 
                    name = '$name', 
                    age = $age, 
                    gender = '$gender', 
                    phonenum = '$phone', 
                    email = '$email', 
                    address = '$address', 
                    class_id = $class_id 
                  WHERE staffID = $staffID";

    if (mysqli_query($conn, $updateSql)) {
        // Redirect to staff profile page after successful update
        header("Location: staffprofile.php");
        exit;
    } else {
        echo "Error updating record: " . mysqli_error($conn);
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
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        header {
            background-color: #52B4B7;
            color: white;
            padding: 15px 0;
            text-align: center;
            margin-bottom: 20px;
        }

        h1 {
            margin: 0;
            padding: 10px 0;
            color: white;
        }
        h2 {
            margin: 0;
            padding: 10px 0;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
            color: #333;
        }

        input, select {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
            font-size: 16px;
        }

        button {
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            margin-top: 10px;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }

        .details {
            margin-bottom: 20px;
        }

        .details div {
            margin-bottom: 8px;
        }

        .details strong {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header>
        <h1>Staff Profile</h1>
    </header>

    <div class="container">
        <h2></h2>

        <?php if ($_SERVER['REQUEST_METHOD'] !== 'POST' && !isset($_GET['edit'])): ?>
            <div class="details">
                <div><strong>Full Name:</strong> <?php echo htmlspecialchars($name); ?></div>
                <div><strong>Age:</strong> <?php echo htmlspecialchars($age); ?></div>
                <div><strong>Gender:</strong> <?php echo htmlspecialchars($gender); ?></div>
                <div><strong>Phone:</strong> <?php echo htmlspecialchars($phone); ?></div>
                <div><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></div>
                <div><strong>Address:</strong> <?php echo htmlspecialchars($address); ?></div>
                <div><strong>Class:</strong> <?php echo htmlspecialchars($class_name); ?></div>
                <a href="?staffID=<?php echo $staffID; ?>&edit=true" class="back-btn">Edit</a>
                <a href="staffmainpage.php" class="back-btn">Back to Staff Mainpage</a>
                <a href="staffchangepass.php" class="back-btn">Change Password</a>
            </div>
        <?php else: ?>
            <form method="POST">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>

                <label for="age">Age</label>
                <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($age); ?>" required>

                <label for="gender">Gender</label>
                <select id="gender" name="gender" required>
                    <option value="female" <?php if ($gender == 'female') echo 'selected'; ?>>Female</option>
                    <option value="male" <?php if ($gender == 'male') echo 'selected'; ?>>Male</option>
                </select>

                <label for="phone">Phone</label>
                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

                <label for="address">Address</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($address); ?>" required>

                <label for="class_id">Class ID</label>
                <input type="number" id="class_id" name="class_id" value="<?php echo htmlspecialchars($class_id); ?>">

                <button type="submit">Update</button>
                <a href="staffprofile.php" class="back-btn">Back to Staff Mainpage</a>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>

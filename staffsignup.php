<?php
$pageTitle = "Sign Up";
session_start();

$login_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = mysqli_connect('localhost', 'root', '', 'tadika cahaya kaseh');

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Sanitize and validate inputs
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);
    $confirm_password = $conn->real_escape_string($_POST['confirm_password']);
    $age = (int)$_POST['age'];
    $gender = $conn->real_escape_string($_POST['gender']);
    $email = $conn->real_escape_string($_POST['email']);
    $contact_number = $conn->real_escape_string($_POST['contact_number']);
    $address = $conn->real_escape_string($_POST['address']);

    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match.'); window.history.back();</script>";
        exit();
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO staff (name, password, age, gender, phonenum, email, address) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssissss", $username, $password, $age, $gender, $contact_number, $email, $address);

    if ($stmt->execute()) {
        // Set session variable to indicate successful signup
        $_SESSION['signup_success'] = true;
        // Redirect to the same page to display the success message
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
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
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background: linear-gradient(135deg, #52B4B7, #549DB7, #FBFDFF);
    }

    .container {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        width: 350px;
        text-align: center;
    }

    h1 {
        color: white;
        font-size: 35px;
        margin-bottom: 30px;
    }

    h2 {
        font-size: 30px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    form {
        display: flex;
        flex-direction: column;
    }

    .form-group {
        margin-bottom: 15px;
        text-align: left;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
    }

    .form-group input, .form-group select {
        width: calc(100% - 22px); /* Adjusted width */
        padding: 10px;
        border: 2px solid #ccc;
        border-radius: 5px;
    }

    button, input[type="submit"] {
        padding: 10px;
        margin-top: 20px;
        background-color: #52B4B7;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        width: 100%; /* Make buttons full width */
        box-sizing: border-box; /* Ensure padding and border are included in the width */
    }

    button:hover, input[type="submit"]:hover {
        background-color: #549DB7;
    }

    .back-button {
        background-color: #ccc;
        color: #333;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        padding: 10px;
        border-radius: 5px;
        margin-top: 10px;
        width: 100%; /* Make buttons full width */
        box-sizing: border-box; /* Ensure padding and border are included in the width */
        cursor: pointer;
    }

    .back-button:hover {
        background-color: #bbb;
    }
</style>
</head>
<body>
    <div class="container">
        <h1>TADIKA CAHAYA KASEH</h1>
        <h2>Create Account</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label for="username">User Name:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" required>
            </div>
            <div class="form-group">
                <label for="gender">Gender:</label>
                <select id="gender" name="gender" required>
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="form-group">
                <label for="contact_number">Contact Number:</label>
                <input type="text" id="contact_number" name="contact_number" required>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Sign Up">
            </div>
            <div class="form-group">
                <a href="login.php" class="back-button">Back to Login</a>
            </div>
        </form>
    </div>

    <?php
    // Check if the session variable is set and display the success message
    if (isset($_SESSION['signup_success']) && $_SESSION['signup_success']) {
        echo "<script>alert('Yay! You successfully created your account! You can log in now!');</script>";
        // Unset the session variable to prevent the message from showing again
        unset($_SESSION['signup_success']);
    }
    ?>
</body>
</html>

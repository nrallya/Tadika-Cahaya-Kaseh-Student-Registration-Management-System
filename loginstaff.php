<?php
$pageTitle = "Login Page";
session_start();

$login_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = mysqli_connect('localhost', 'root', '', 'tadika cahaya kaseh');

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if (!empty($email) && !empty($password)) {
        // Check if email exists in staff table
        $query_staff = "SELECT * FROM staff WHERE email = ?";
        $stmt_staff = $conn->prepare($query_staff);
        $stmt_staff->bind_param('s', $email);
        $stmt_staff->execute();
        $result_staff = $stmt_staff->get_result();

        if ($result_staff->num_rows > 0) {
            $row_staff = $result_staff->fetch_assoc();
            if ($password === $row_staff['password']) {  // Directly compare the plain text passwords
                // Password is correct, set session variables
                $_SESSION['staffID'] = $row_staff['staffID'];
                $_SESSION['name'] = $row_staff['name'];
                
                // Redirect to staff main page
                header("Location: staffmainpage.php");
                exit();
            } else {
                $login_error = "Invalid email or password.";
            }
        } else {
            $login_error = "Invalid email or password.";
        }

        $stmt_staff->close();
    } else {
        $login_error = "Please enter valid email and password!";
    }

    mysqli_close($conn);
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
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #52B4B7, #549DB7, #FBFDFF);
        }
        header {
            text-align: center;
            color: white;
            font-size: 30px;
            margin-right: 2cm;
            margin-bottom: 20px;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: left;
        }
        .container h2 {
            margin-bottom: 20px;
            text-align: center;
        }
        .container input[type="email"],
        .container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            transition: border 0.3s ease;
        }
        .container input[type="email"]:focus,
        .container input[type="password"]:focus {
            border: 1px solid #52B4B7;
            outline: none;
        }
        .container .login-button {
            width: 100%;
            padding: 15px;
            background-color: #52B4B7;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-bottom: 10px;
        }
        .container .login-button:hover {
            background-color: #549DB7;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <header>
        <h1>TADIKA CAHAYA KASEH</h1>
    </header>

    <div class="container">
        <h2>WELCOME STAFF!</h2>
        <form class="login-form" method="post">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <div class="form-group">
                <button type="submit" name="submit" class="login-button">Login</button>
            </div>
            <?php if (!empty($login_error)) { echo "<div class='error'>$login_error</div>"; } ?>
        </form>
    </div>

</body>
</html>

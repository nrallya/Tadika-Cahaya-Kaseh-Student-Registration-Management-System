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
        // Check if email and password match in usertable
        $query_user= "SELECT * FROM user WHERE email = ? AND password = ?";
        $stmt = $conn->prepare($query_user);
        $stmt->bind_param('ss', $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row_user= $result->fetch_assoc();
            $_SESSION['userID'] = $row_user['userID'];
            $_SESSION['username'] = $row_user['username'];

            echo '<script type="text/javascript">
                    alert("Welcome ' . $_SESSION['username'] . '")
                  </script>';
            echo "<meta http-equiv=\"refresh\" content=\"0;URL=mainpage.php\">";
        } else {
            $login_error = "Email or Password incorrect!";
        }
    } else {
        $login_error = "Please enter valid Email and Password!";
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
            text-align: center; /* Center the text */
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
        .container .login-button, .container .signup-button {
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
        .container .login-button:hover, .container .signup-button:hover {
            background-color: #549DB7;
        }
        .signup-button {
            background-color: #52B4B7;
            margin-bottom: 0;
        }
        .signup-button:hover {
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
    <h2>WELCOME USER!</h2>
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
            <button type="button" class="signup-button" onclick="window.location.href='signup.php';">Sign Up</button>
        </div>
        <?php if (!empty($login_error)) { echo "<div class='error'>$login_error</div>"; } ?>
    </form>
</div>

</body>
</html>

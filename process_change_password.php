<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = mysqli_connect('localhost', 'root', '', 'tadika cahaya kaseh');

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Sanitize inputs
    $old_password = mysqli_real_escape_string($conn, $_POST['old_password']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Validate if new password and confirm password match
    if ($new_password !== $confirm_password) {
        die("New password and confirm password do not match.");
    }

    // Assuming you have stored user ID in $_SESSION['userID']
    $userID = $_SESSION['userID'];

    // Update the password in the database
    $update_query = "UPDATE user SET password = '$new_password' WHERE userID = $userID AND password = '$old_password'";
    $result = mysqli_query($conn, $update_query);

    if ($result) {
        // Password updated successfully
        mysqli_close($conn);
        echo '<script>alert("Password changed successfully."); window.location.href = "editparentdetail.php";</script>';
        exit();
    } else {
        echo "Error updating password: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>

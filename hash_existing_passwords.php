<?php
// Include your database connection code here if necessary
$conn = mysqli_connect('localhost', 'root', '', 'tadika cahaya kaseh');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Example: Update existing passwords in 'user' table
$query = "SELECT userID, password FROM user";
$result = mysqli_query($conn, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $hashed_password = password_hash($row['password'], PASSWORD_DEFAULT);
        $user_id = $row['userID'];

        // Update hashed password in database
        $update_query = "UPDATE user SET password = '$hashed_password' WHERE userID = $user_id";
        mysqli_query($conn, $update_query);
        echo "Password updated for user ID $user_id<br>";
    }
} else {
    echo "Error fetching users: " . mysqli_error($conn);
}

mysqli_close($conn);
?>

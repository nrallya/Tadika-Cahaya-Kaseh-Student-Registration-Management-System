<?php
// delete_child.php

session_start();

// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'tadika cahaya kaseh');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if childID is received
if (isset($_POST['childID'])) {
    $childID = mysqli_real_escape_string($conn, $_POST['childID']);
    
    // Delete the child record
    $sql = "DELETE FROM child WHERE childID = '$childID'";
    
    if (mysqli_query($conn, $sql)) {
        echo "Success";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

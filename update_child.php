<?php
session_start();

// Redirect to login if user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs
    $childID = $_POST['childID'];
    $child_name = $_POST['child_name'];
    $child_age = $_POST['child_age'];
    $child_dob_day = $_POST['child_dob_day'];
    $child_dob_month = $_POST['child_dob_month'];
    $child_dob_year = $_POST['child_dob_year'];
    $child_dob = "$child_dob_year-$child_dob_month-$child_dob_day";
    $child_ic = $_POST['child_ic'];
    $child_birthplace = $_POST['child_birthplace'];
    $child_address = $_POST['child_address'];

    // Validate inputs (you can add more validation as per your requirements)

    // Establish a connection to the database
    $conn = new mysqli('localhost', 'root', '', 'tadika cahaya kaseh');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update child record in the database
    $sql_update = "UPDATE child SET full_name=?, age=?, DOB=?, IC=?, Birthplace=?, Address=? WHERE childID=?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("sissssi", $child_name, $child_age, $child_dob, $child_ic, $child_birthplace, $child_address, $childID);

    if ($stmt->execute()) {
        // Redirect to manage students page after successful update
        header("Location: studentmanagement.php");
    } else {
        // Error handling if update fails
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    // Redirect if accessed directly without POST request
    header("Location: studentmanagement.php");
}
?>

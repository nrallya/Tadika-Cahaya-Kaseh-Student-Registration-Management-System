<?php
// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'tadika cahaya kaseh');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch hospital names based on user input
if (isset($_GET['term'])) {
    $term = mysqli_real_escape_string($conn, $_GET['term']);
    $query = "SELECT hospital_name FROM hospital_list WHERE hospital_name LIKE '%$term%'";
    $result = mysqli_query($conn, $query);

    $hospitalNames = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $hospitalNames[] = $row['hospital_name'];
    }

    // Return JSON encoded array
    echo json_encode($hospitalNames);
}

mysqli_close($conn);
?>

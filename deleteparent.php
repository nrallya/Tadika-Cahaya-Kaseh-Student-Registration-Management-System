<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['parentID'])) {
    $parentID = $_POST['parentID'];

    // Database connection
    $conn = mysqli_connect('localhost', 'root', '', 'tadika cahaya kaseh');

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Delete parent query
    $sql_parent = "DELETE FROM parent WHERE parentID = ?";

    // Prepare statement for parent deletion
    $stmt_parent = mysqli_prepare($conn, $sql_parent);
    if ($stmt_parent) {
        // Bind parameters for parent deletion
        mysqli_stmt_bind_param($stmt_parent, "i", $parentID);

        // Execute parent deletion
        if (mysqli_stmt_execute($stmt_parent)) {
            // Delete associated children query
            $sql_children = "DELETE FROM child WHERE parentID = ?";
            
            // Prepare statement for child deletion
            $stmt_children = mysqli_prepare($conn, $sql_children);
            if ($stmt_children) {
                // Bind parameters for child deletion
                mysqli_stmt_bind_param($stmt_children, "i", $parentID);

                // Execute child deletion
                if (mysqli_stmt_execute($stmt_children)) {
                    // Return a success response
                    http_response_code(200);
                    echo "Parent and associated children deleted successfully";
                } else {
                    // Return an error response for child deletion
                    http_response_code(500);
                    echo "Error deleting associated children: " . mysqli_stmt_error($stmt_children);
                }

                // Close child statement
                mysqli_stmt_close($stmt_children);
            } else {
                // Return an error response if prepare fails for child deletion
                http_response_code(500);
                echo "Error preparing delete statement for children: " . mysqli_error($conn);
            }

            // Close parent statement
            mysqli_stmt_close($stmt_parent);
        } else {
            // Return an error response for parent deletion
            http_response_code(500);
            echo "Error deleting parent record: " . mysqli_stmt_error($stmt_parent);
        }
    } else {
        // Return an error response if prepare fails for parent deletion
        http_response_code(500);
        echo "Error preparing delete statement for parent: " . mysqli_error($conn);
    }

    // Close connection
    mysqli_close($conn);
} else {
    // Return a bad request response if parentID is not provided
    http_response_code(400);
    echo "Bad request: Parent ID not provided";
}
?>

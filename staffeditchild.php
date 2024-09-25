<?php
$pageTitle = "Edit Student";
session_start();

// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'tadika cahaya kaseh');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get childID from URL
$childID = isset($_GET['childID']) ? intval($_GET['childID']) : 0;

// Fetch child data
$sql = "SELECT * FROM child WHERE childID = $childID";
$result = mysqli_query($conn, $sql);
$child = mysqli_fetch_assoc($result);

// Initialize variables to hold current data
$full_name = $child['full_name'];
$age = $child['age'];
$DOB = $child['DOB'];
$IC = $child['IC'];
$Birthplace = $child['Birthplace'];
$Address = $child['Address'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate inputs
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $age = intval($_POST['age']);
    $IC = mysqli_real_escape_string($conn, $_POST['IC']);
    $Birthplace = mysqli_real_escape_string($conn, $_POST['Birthplace']);
    $Address = mysqli_real_escape_string($conn, $_POST['Address']);
    
    // Check if DOB was modified
    if (!empty($_POST['DOB'])) {
        $DOB = mysqli_real_escape_string($conn, $_POST['DOB']);
    }

    // Update child data
    $updateSql = "UPDATE child SET full_name = '$full_name', age = '$age', DOB = '$DOB', IC = '$IC', Birthplace = '$Birthplace', Address = '$Address' WHERE childID = $childID";
    if (mysqli_query($conn, $updateSql)) {
        echo "<script>alert('Student updated successfully'); window.location.href = 'staffstudentmanagement.php';</script>";
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
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #52B4B7;
            color: white;
            padding: 15px;
            text-align: center;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: white;
        }

        h2 {
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            color: #333;
        }

        input[type="text"], input[type="date"] {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
        }

        button {
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .back-btn {
            display: block;
            width: 120px;
            padding: 10px;
            margin: 20px 0;
            background-color: #007BFF;
            color: white;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            margin: 20px auto;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }
    </style>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#Birthplace").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "fetch_hospitals.php",
                        type: "GET",
                        data: {
                            term: request.term
                        },
                        success: function(data) {
                            response(JSON.parse(data));
                        }
                    });
                },
                minLength: 2
            });
        });
    </script>
</head>
<body>
    <header>
        <h1><?php echo $pageTitle; ?></h1>
    </header>

    <div class="container">
        <h2>Edit Student</h2>
        <form method="POST">
            <label for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name" value="<?php echo $full_name; ?>" required>

            <label for="age">Age</label>
            <input type="text" id="age" name="age" value="<?php echo $age; ?>" required>

            <label for="DOB">Date of Birth</label>
            <input type="date" id="DOB" name="DOB" value="<?php echo $DOB; ?>">

            <label for="IC">IC</label>
            <input type="text" id="IC" name="IC" value="<?php echo $IC; ?>" required>

            <label for="Birthplace">Birthplace</label>
            <input type="text" id="Birthplace" name="Birthplace" value="<?php echo $Birthplace; ?>" required>

            <label for="Address">Address</label>
            <input type="text" id="Address" name="Address" value="<?php echo $Address; ?>" required>

            <button type="submit">Update</button>
        </form>
        <a href="staffstudentmanagement.php" class="back-btn">Back</a>
    </div>
</body>
</html>

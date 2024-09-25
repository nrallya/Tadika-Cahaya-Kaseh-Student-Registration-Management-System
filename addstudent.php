<?php
$pageTitle = "Add Student";
session_start();

// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'tadika cahaya kaseh');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch parents data
$sqlParents = "SELECT parentID, father_name, mother_name FROM parent";
$resultParents = mysqli_query($conn, $sqlParents);

// Fetch classes data
$sqlClasses = "SELECT class_id, class_name FROM class";
$resultClasses = mysqli_query($conn, $sqlClasses);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $age = intval($_POST['age']);
    $DOB = mysqli_real_escape_string($conn, $_POST['DOB']);
    $IC = mysqli_real_escape_string($conn, $_POST['IC']);
    $Birthplace = mysqli_real_escape_string($conn, $_POST['Birthplace']);
    $Address = mysqli_real_escape_string($conn, $_POST['Address']);
    $userID = intval($_POST['userID']); // Parent ID
    $class_id = intval($_POST['class_id']); // Class ID

    // Insert new child data into child table
    $insertSql = "INSERT INTO child (full_name, age, DOB, IC, Birthplace, Address, userID, class_id) 
                  VALUES ('$full_name', '$age', '$DOB', '$IC', '$Birthplace', '$Address', '$userID', '$class_id')";

    if (mysqli_query($conn, $insertSql)) {
        echo "<script>alert('Student added successfully'); window.location.href = 'studentmanagement.php';</script>";
    } else {
        echo "Error: " . $insertSql . "<br>" . mysqli_error($conn);
    }
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

        input[type="text"], input[type="date"], input[type="number"], select {
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
    <!-- Include jQuery UI CSS and JS -->
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
        <h2>Add New Student</h2>
        <form method="POST">
            <label for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name" required>

            <label for="age">Age</label>
            <input type="number" id="age" name="age" required>

            <label for="DOB">Date of Birth</label>
            <input type="date" id="DOB" name="DOB" required>

            <label for="IC">IC</label>
            <input type="text" id="IC" name="IC" required>

            <label for="Birthplace">Birthplace</label>
            <input type="text" id="Birthplace" name="Birthplace" required>

            <label for="Address">Address</label>
            <input type="text" id="Address" name="Address" required>

            <label for="userID">Select Parent</label>
            <select id="userID" name="userID" required>
                <option value="">Select Parent</option>
                <?php
                if (mysqli_num_rows($resultParents) > 0) {
                    while ($row = mysqli_fetch_assoc($resultParents)) {
                        echo "<option value='" . $row['parentID'] . "'>" . $row['father_name'] . " / " . $row['mother_name'] . "</option>";
                    }
                } else {
                    echo "<option value=''>No parents found</option>";
                }
                ?>
            </select>

            <label for="class_id">Select Class</label>
            <select id="class_id" name="class_id" required>
                <option value="">Select Class</option>
                <?php
                if (mysqli_num_rows($resultClasses) > 0) {
                    while ($row = mysqli_fetch_assoc($resultClasses)) {
                        echo "<option value='" . $row['class_id'] . "'>" . $row['class_name'] . "</option>";
                    }
                } else {
                    echo "<option value=''>No classes found</option>";
                }
                ?>
            </select>

            <button type="submit">Add Student</button>
        </form>
        <a href="studentmanagement.php" class="back-btn">Back to Student Management</a>
    </div>
</body>
</html>
<?php mysqli_close($conn); ?>

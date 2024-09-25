<?php
$pageTitle = "Student Management";
session_start();

// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'tadika cahaya kaseh');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to get total number of students
function getTotalStudents($conn) {
    $sql = "SELECT COUNT(*) as totalStudents FROM child";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['totalStudents'];
    } else {
        return 0; // Return 0 if there's an error or no students found
    }
}


// Fetch children data with optional name filter
$sql = "SELECT c.childID, c.full_name, c.age, c.DOB, c.IC, c.Birthplace, c.Address, p.father_name 
        FROM child c
        LEFT JOIN parent p ON c.userID = p.userID";
if (isset($_GET['filter_name']) && !empty($_GET['filter_name'])) {
    $filter_name = mysqli_real_escape_string($conn, $_GET['filter_name']);
    $sql .= " WHERE c.full_name LIKE '%$filter_name%'";
}
$result = mysqli_query($conn, $sql);

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
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative; /* Add position relative for absolute positioning */
        }

        h1 {
            color: white;
        }

        h2 {
            color: #333;
        }

        p {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .action-btn {
            padding: 8px 12px;
            border: none;
            border-radius: 3px;
            text-decoration: none;
            color: white;
            margin: 2px;
        }

        .edit-btn {
            background-color: #4CAF50;
        }

        .delete-btn {
            background-color: #f44336;
        }

        .action-btn:hover {
            opacity: 0.8;
        }

        .details-content {
            display: none;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 5px;
            margin-top: 5px;
            border: 1px solid #ddd;
        }

        .toggle-details {
            cursor: pointer;
            color: #007BFF;
        }

        .toggle-details:hover {
            text-decoration: underline;
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

        .add-btn {
            display: block;
            padding: 10px 20px; /* Increase padding for better button appearance */
            background-color: #007BFF; /* Blue color for the button */
            color: white;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            position: absolute;
            top: 20px;
            right: 20px; /* Align to the right */
        }

        .add-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <h1><?php echo $pageTitle; ?></h1>
    </header>

    <div class="container">
        <p>Total Students: <?php echo getTotalStudents($conn); ?></p>
        
        <a href="addstudent.php" class="add-btn">Add Student</a>
        <form class="filter-form" method="GET" action="">
            <input type="text" name="filter_name" placeholder="Filter by name" value="<?php echo isset($_GET['filter_name']) ? htmlspecialchars($_GET['filter_name']) : ''; ?>">
            <button type="submit">Apply Name Filter</button>
        </form>
        <br>
        <table>
            <tr>
                <th>Child ID</th>
                <th>Full Name (Click to Expand)</th>
                <th>Parent Name</th>
                <th>Actions</th>
            </tr>
            <?php
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>" . $row["childID"] . "</td>
                            <td class='toggle-details' data-child-id='" . $row["childID"] . "'>" . $row["full_name"] . "</td>
                            <td>" . $row["father_name"] . "</td>
                            <td>
                                <a href='edit_child.php?childID=" . $row["childID"] . "' class='action-btn edit-btn'>Edit</a>
                                <button class='action-btn delete-btn' onclick='deleteChild(" . $row["childID"] . ")'>Delete</button>
                                <div class='details-content' id='details_" . $row["childID"] . "'>
                                    <p><strong>Age:</strong> " . $row["age"] . "</p>
                                    <p><strong>Date of Birth:</strong> " . $row["DOB"] . "</p>
                                    <p><strong>IC:</strong> " . $row["IC"] . "</p>
                                    <p><strong>Birthplace:</strong> " . $row["Birthplace"] . "</p>
                                    <p><strong>Address:</strong> " . $row["Address"] . "</p>
                                </div>
                            </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No records found</td></tr>";
            }
            ?>
        </table>

        <a href="adminmainpage.php" class="back-btn">Back</a>
    </div>

    <script>
        function toggleDetails(childID) {
            var detailsContent = document.getElementById('details_' + childID);
            if (detailsContent.style.display === 'none' || detailsContent.style.display === '') {
                detailsContent.style.display = 'block';
            } else {
                detailsContent.style.display = 'none';
            }
        }
        function deleteChild(childID) {
    if (confirm("Are you sure you want to delete this record?")) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "delete_child.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    if (xhr.responseText.trim() === "Success") {
                        alert("Record deleted successfully.");
                        location.reload(); // Refresh the page to reflect the deletion
                    } else {
                        alert("Error: " + xhr.responseText);
                    }
                } else {
                    console.error("Error: Failed to delete the record.");
                }
            }
        };
        xhr.send("childID=" + encodeURIComponent(childID));
    }
}


        var toggleDetailsElements = document.querySelectorAll('.toggle-details');
        toggleDetailsElements.forEach(function(element) {
            element.addEventListener('click', function() {
                var childID = this.getAttribute('data-child-id');
                toggleDetails(childID);
            });
        });
    </script>
</body>
</html>
<?php mysqli_close($conn); ?>

<?php
$pageTitle = "Staff Management";
session_start();

// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'tadika cahaya kaseh');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to get total number of staff
function getTotalStaff($conn) {
    $sql = "SELECT COUNT(*) as totalStaff FROM staff";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['totalStaff'];
    } else {
        return 0; // Return 0 if there's an error or no staff found
    }
}

// Fetch staff data
$sql = "SELECT staffID, name, email, address, age, phonenum FROM staff";
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
        }

        h1 {
            color: white;
        }

        p {
            color: #333;
        }

        .add-staff-btn {
            display: block;
            width: 120px;
            padding: 10px;
            margin: 20px 0;
            background-color: #007BFF;
            color: white;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            float: right;
        }

        .add-staff-btn:hover {
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            clear: both;
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
    </style>
</head>
<body>
    <header>
        <h1><?php echo $pageTitle; ?></h1>
    </header>

    <div class="container">
        <p>Total Staff: <?php echo getTotalStaff($conn); ?></p>
        <a href="addstaff.php" class="add-staff-btn">Add Staff</a>
        <table>
            <tr>
                <th>Staff ID</th>
                <th>Name (Click to Expand)</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
            <?php
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>" . $row["staffID"] . "</td>
                            <td class='toggle-details' data-staff-id='" . $row["staffID"] . "'>" . $row["name"] . "</td>
                            <td>" . $row["email"] . "</td>
                            <td>
                                <a href='editstaff.php?staffID=" . $row["staffID"] . "' class='action-btn edit-btn'>Edit</a>
                                <button class='action-btn delete-btn' onclick='deleteStaff(" . $row["staffID"] . ")'>Delete</button>
                                <div class='details-content' id='details_" . $row["staffID"] . "'>
                                    <p><strong>Address:</strong> " . $row["address"] . "</p>
                                    <p><strong>Age:</strong> " . $row["age"] . "</p>
                                    <p><strong>Phone Number:</strong> " . $row["phonenum"] . "</p>
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
        function toggleDetails(staffID) {
            var detailsContent = document.getElementById('details_' + staffID);
            if (detailsContent.style.display === 'none' || detailsContent.style.display === '') {
                detailsContent.style.display = 'block';
            } else {
                detailsContent.style.display = 'none';
            }
        }

        function deleteStaff(staffID) {
            if (confirm("Are you sure you want to delete this record?")) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "delete_staff.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            location.reload();
                        } else {
                            console.error(xhr.responseText);
                        }
                    }
                };
                xhr.send("staffID=" + staffID);
            }
        }

        var toggleDetailsElements = document.querySelectorAll('.toggle-details');
        toggleDetailsElements.forEach(function(element) {
            element.addEventListener('click', function() {
                var staffID = this.getAttribute('data-staff-id');
                toggleDetails(staffID);
            });
        });
    </script>
</body>
</html>
<?php mysqli_close($conn); ?>

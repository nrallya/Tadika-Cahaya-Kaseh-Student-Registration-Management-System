<?php
session_start();

$pageTitle = "Student Management";

// Ensure user is authenticated
if (!isset($_SESSION['staffID'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'tadika cahaya kaseh');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to get total number of students for a specific class
function getTotalStudents($conn, $class_id) {
    $sql = "SELECT COUNT(*) as totalStudents FROM child WHERE class_id = $class_id";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['totalStudents'];
    } else {
        return 0; // Return 0 if there's an error or no students found
    }
}

// Fetch children data assigned under the logged-in staff member's class
if (isset($_SESSION['staffID'])) {
    $staffID = $_SESSION['staffID'];
    
    // Fetch the class_id associated with the staff
    $sql_class = "SELECT class_id FROM staff WHERE staffID = $staffID";
    $result_class = mysqli_query($conn, $sql_class);
    
    if ($result_class && mysqli_num_rows($result_class) > 0) {
        $row_class = mysqli_fetch_assoc($result_class);
        $class_id = $row_class['class_id'];

        // Build the base SQL query
        $sql_children = "SELECT c.childID, c.full_name, c.age, c.DOB, c.IC, c.Birthplace, c.Address, c.userID, c.FeePaid, p.fatherPhoneNum, p.motherPhoneNum
                         FROM child c
                         LEFT JOIN parent p ON c.userID = p.userID
                         WHERE c.class_id = $class_id";

        // Apply filters if set
        if (isset($_GET['filter_name']) && !empty($_GET['filter_name'])) {
            $filter_name = mysqli_real_escape_string($conn, $_GET['filter_name']);
            $sql_children .= " AND c.full_name LIKE '%$filter_name%'";
        }
        if (isset($_GET['filter_fee_status']) && in_array($_GET['filter_fee_status'], ['paid', 'unpaid'])) {
            $filter_fee_status = $_GET['filter_fee_status'] == 'paid' ? 1 : 0;
            $sql_children .= " AND c.FeePaid = $filter_fee_status";
        }

        $result_children = mysqli_query($conn, $sql_children);
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
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
            position: relative; /* Add position relative for absolute positioning */
        }

        h1 {
            color: white;
        }
        
        h2 {
            color: #333;
            text-align: center;
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
            cursor: pointer;
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
            margin: 20px auto;
            background-color: #007BFF;
            color: white;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
        }

        .back-btn:hover, .add-btn:hover {
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

        /* Add styles for rows based on fee payment status */
        .fee-paid {
            background-color: #d4edda; /* Light green for paid */
        }

        .fee-unpaid {
            background-color: #f8d7da; /* Light red for unpaid */
        }

        /* Filter form styling */
        .filter-form {
            margin-bottom: 20px;
        }

        .filter-form input, .filter-form select {
            padding: 10px;
            margin-right: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .filter-form button {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .filter-form button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <h1><?php echo $pageTitle; ?></h1>
    </header>

    <div class="container">
        <p>Total Students: <?php echo getTotalStudents($conn, $class_id); ?></p>
        <div class="add-btn-container">
            <a href="staffaddstudent.php" class="add-btn">Add Student</a>
        </div>
        
        <form class="filter-form" method="GET" action="">
            <input type="text" name="filter_name" placeholder="Filter by name" value="<?php echo isset($_GET['filter_name']) ? htmlspecialchars($_GET['filter_name']) : ''; ?>">
            <select name="filter_fee_status">
                <option value="">Filter by fee status</option>
                <option value="paid" <?php echo isset($_GET['filter_fee_status']) && $_GET['filter_fee_status'] == 'paid' ? 'selected' : ''; ?>>Paid</option>
                <option value="unpaid" <?php echo isset($_GET['filter_fee_status']) && $_GET['filter_fee_status'] == 'unpaid' ? 'selected' : ''; ?>>Unpaid</option>
            </select>
            <button type="submit">Apply Filters</button>
        </form>

        <?php if (isset($result_children) && mysqli_num_rows($result_children) > 0) : ?>
            <table>
                <thead>
                    <tr>
                        <th>Child ID</th>
                        <th>Full Name (Click to Expand)</th>
                        <th>Parent ID</th>
                        <th>Father's Phone</th>
                        <th>Mother's Phone</th>
                        <th>Fee Paid</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result_children)) : ?>
                        <tr class="<?php echo $row["FeePaid"] ? 'fee-paid' : 'fee-unpaid'; ?>">
                            <td><?php echo $row["childID"]; ?></td>
                            <td class="toggle-details" data-child-id="<?php echo $row["childID"]; ?>"><?php echo $row["full_name"]; ?></td>
                            <td><?php echo $row["userID"]; ?></td>
                            <td><?php echo $row["fatherPhoneNum"]; ?></td>
                            <td><?php echo $row["motherPhoneNum"]; ?></td>
                            <td><?php echo $row["FeePaid"] ? 'Yes' : 'No'; ?></td>
                            <td>
                                <a href="staffeditchild.php?childID=<?php echo $row["childID"]; ?>" class="action-btn edit-btn">Edit</a>
                                <button class="action-btn delete-btn" onclick="deleteChild(<?php echo $row["childID"]; ?>)">Delete</button>
                                <div class="details-content" id="details_<?php echo $row["childID"]; ?>">
                                    <p><strong>Age:</strong> <?php echo $row["age"]; ?></p>
                                    <p><strong>Date of Birth:</strong> <?php echo $row["DOB"]; ?></p>
                                    <p><strong>IC:</strong> <?php echo $row["IC"]; ?></p>
                                    <p><strong>Birthplace:</strong> <?php echo $row["Birthplace"]; ?></p>
                                    <p><strong>Address:</strong> <?php echo $row["Address"]; ?></p>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No students found for this class.</p>
        <?php endif; ?>
        
        <a href="staffmainpage.php" class="back-btn">Back</a>
    </div>

    <script>
        function deleteChild(childID) {
            if (confirm("Are you sure you want to delete this record?")) {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        // Refresh page or update DOM as needed
                        location.reload();
                    }
                };
                xmlhttp.open("GET", "delete_child.php?childID=" + childID, true);
                xmlhttp.send();
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            var toggleDetails = document.querySelectorAll('.toggle-details');
            toggleDetails.forEach(function(element) {
                element.addEventListener('click', function() {
                    var childID = this.getAttribute('data-child-id');
                    var detailsContent = document.getElementById('details_' + childID);
                    detailsContent.style.display = detailsContent.style.display === "block" ? "none" : "block";
                });
            });
        });
    </script>
</body>
</html>

<?php mysqli_close($conn); ?>

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

// Function to get total number of parents
function getTotalParents($conn) {
    $sql = "SELECT COUNT(*) as totalParents FROM parent";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['totalParents'];
    } else {
        return 0; // Return 0 if there's an error or no parents found
    }
}

// Fetch children data
$sql = "SELECT childID, full_name, age, DOB, IC, Birthplace, Address, userID FROM child";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kindergarten Admin Page</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f8ff;
            color: #333;
            line-height: 1.6;
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Ensure full height */
        }

        header {
            background-color: #52B4B7;
            color: #fff;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        nav {
            background-color: #fff;
            padding: 10px 0;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
        }

        nav ul li {
            margin: 0 15px;
        }

        nav ul li a {
            color: #52B4B7;
            text-decoration: none;
            font-weight: 600;
            padding: 10px 15px;
            border-radius: 3px;
            transition: background-color 0.3s;
            border: 1px solid transparent;
        }

        nav ul li a:hover {
            background-color: rgba(82, 180, 183, 0.2);
        }

        .content-section {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        h2 {
            color: #52B4B7;
            margin-bottom: 10px;
        }

        h3 {
            color: #87CEEB;
            margin-bottom: 10px;
        }

        footer {
            background-color: #52B4B7;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            width: 100%;
            margin-top: auto; /* Push footer to the bottom */
        }

        .logout-btn {
            background-color: #f44336;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .logout-btn:hover {
            background-color: #d32f2f;
        }

        .stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }

        .stats div {
            background-color: #e0f7fa;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            width: 30%;
        }
    </style>
</head>
<body>
    <header>
        <h1>Kindergarten Admin Page</h1>
    </header>

    <nav>
        <ul>
            <li><a href="#dashboard">Dashboard</a></li>
            <li><a href="studentmanagement.php">Student Management</a></li>
            <li><a href="staffmanagement.php">Staff Management</a></li>
            <li><a href="classmanagement.php">Class Details</a></li>
            <li><a href="parentmanagement.php">Parent Management</a></li>
            <li><a href="viewpayment.php">Payments</a></li>
            <li><a href="report.php">Reports</a></li>
            <li><button class="logout-btn" onclick="logout()">Logout</button></li>
        </ul>
    </nav>

    <section id="dashboard" class="content-section">
        <h2>Dashboard Overview</h2>
        <div class="stats">
            <div>
                <h3>Total Students</h3>
                <p><?php echo getTotalStudents($conn); ?></p>
            </div>
            <div>
                <h3>Total Staff</h3>
                <p><?php echo getTotalStaff($conn); ?></p>
            </div>
            <div>
                <h3>Total Parents</h3>
                <p><?php echo getTotalParents($conn); ?></p>
            </div>
        </div>
    </section>

    <section id="student-management" class="content-section">
        <h2>Student Management</h2>
        <div>
            <h3>Total Students: <?php echo getTotalStudents($conn); ?></h3>
            <!-- Add your student management tools here -->
        </div>
    </section>

    <section id="staff-management" class="content-section">
        <h2>Staff Management</h2>
        <div>
            <h3>Total Staff: <?php echo getTotalStaff($conn); ?></h3>
            <!-- Add your staff management tools here -->
        </div>
    </section>

    <section id="class-management" class="content-section">
        <h2>Class Details</h2>
        <!-- Add your class management tools here -->
    </section>

    <section id="parent-management" class="content-section">
        <h2>Parent Management</h2>
        <div>
            <h3>Total Parents: <?php echo getTotalParents($conn); ?></h3>
            <!-- Add your parent management tools here -->
        </div>
    </section>

    <section id="billing-payments" class="content-section">
        <h2>Payments</h2>
        <!-- Add your billing and payments tools here -->
    </section>

    <section id="reports-analytics" class="content-section">
        <h2>Reports</h2>
        <!-- Add your reports and analytics tools here -->
    </section>
    
    <footer>
        <p>&copy; 2024 Kindergarten. All rights reserved.</p>
    </footer>

    <script>
        function logout() {
            // Implement logout functionality here
            alert("Logout button clicked. Implement logout functionality.");
            window.location.href = "adminlogin.php";
        }
    </script>
</body>
</html>

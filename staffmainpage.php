<?php
session_start();

// Check if staff is logged in and has a valid session
if (!isset($_SESSION['name'])) {
    header("Location: loginstaff.php");
    exit();
}

// Database connection parameters
include "connection.php";

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assign staff name and class ID to variables
$staffName = $_SESSION['name'];
$staffID = $_SESSION['staffID'];

// Fetch the class_id associated with the staff member
$stmt = $conn->prepare("SELECT class_id FROM staff WHERE staffID = ?");
$stmt->bind_param("i", $staffID);
$stmt->execute();
$stmt->bind_result($class_id);
$stmt->fetch();
$stmt->close();

// Fetch class details based on class_id
if ($class_id) {
    $stmt_class = $conn->prepare("SELECT class_name FROM class WHERE class_id = ?");
    $stmt_class->bind_param("i", $class_id);
    $stmt_class->execute();
    $stmt_class->bind_result($class_name);
    $stmt_class->fetch();
    $stmt_class->close();

    // Query to count total students in the class
    $stmt_students = $conn->prepare("SELECT COUNT(*) AS total_students FROM child WHERE class_id = ?");
    $stmt_students->bind_param("i", $class_id);
    $stmt_students->execute();
    $stmt_students->bind_result($total_students);
    $stmt_students->fetch();
    $stmt_students->close();
}

// Handle notification sending
$notification_sent = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['notify_parents'])) {
    $message = "Your child is ready for pickup!";
    $notification_type = "pickup"; // You can define different types if needed

    // Insert notification into the database
    $stmt = $conn->prepare("INSERT INTO notifications (message, notification_type, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("ss", $message, $notification_type);

    if ($stmt->execute()) {
        $notification_sent = true; // Flag that the notification was sent
    }

    $stmt->close();
}

$pageTitle = "Kindergarten Staff Portal";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Body Styling */
        body {
            background-color: #F4F7FC;
            color: #333;
            line-height: 1.6;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Header Styling */
        header {
            background-color: #52B4B7;
            color: #fff;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }

        header p {
            font-size: 18px;
        }

        /* Navigation Bar Styling */
        nav {
            background-color: #fff;
            padding: 15px 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        nav ul {
            list-style: none;
            display: flex;
            justify-content: center;
            gap: 30px;
        }

        nav ul li {
            margin: 0;
        }

        nav ul li a,
        .logout-btn {
            color: #52B4B7;
            text-decoration: none;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }

        nav ul li a:hover,
        .logout-btn:hover {
            background-color: #52B4B7;
            color: #fff;
        }

        /* Main Content Styling */
        main {
            flex: 1;
            padding: 40px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        main .section {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 30px;
        }

        main .section h2 {
            font-size: 28px;
            margin-bottom: 15px;
            color: #52B4B7;
        }

        main .section p {
            font-size: 16px;
            color: #666;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            background-color: #52B4B7;
            color: #fff;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #023047;
        }

        /* Footer Styling */
        footer {
            background-color: #52B4B7;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            margin-top: auto;
        }
    </style>
</head>

<body>
    <header>
        <h1><?php echo $pageTitle; ?></h1>
        <p>Welcome, <?php echo htmlspecialchars($staffName); ?>!</p>
    </header>

    <nav>
        <ul>
            <li><a href="staffstudentmanagement.php">Student Management</a></li>
            <li><a href="syllabusmanagement.php">Syllabus Management</a></li>
            <li><a href="staffprofile.php">Profile Management</a></li>
            <li><a href="album.php">Album</a></li>
            <li><button class="logout-btn" onclick="logout()">Logout</button></li>
        </ul>
    </nav>

    <main>
        <section id="dashboard" class="section">
            <h2>Class Information</h2>
            <div class="dashboard-info">
                <?php if ($class_id && $class_name) { ?>
                    <p>Dear <?php echo htmlspecialchars($staffName); ?>, you are assigned to class <span><?php echo htmlspecialchars($class_name); ?></span></p>
                    <p>Max Students for this class: 10</p>
                <?php } else { ?>
                    <p>No class information found.</p>
                <?php } ?>
            </div>
        </section>

        <section id="student-management" class="section">
            <h2>Student Management</h2>
            <?php if ($class_id && isset($total_students)) { ?>
                <p>Total Students: <?php echo $total_students; ?></p>
            <?php } else { ?>
                <p>No students found in this class.</p>
            <?php } ?>
            <a href="staffstudentmanagement.php" class="btn">Manage Students</a>
        </section>

        <section id="syllabus-management" class="section">
            <h2>Syllabus Management</h2>
            <p>Upload, update, and manage syllabus details.</p>
            <a href="syllabusmanagement.php" class="btn">Manage Syllabus</a>
        </section>

        <section id="profile-management" class="section">
            <h2>Profile Management</h2>
            <p>Update your profile and credentials.</p>
            <a href="profile_management.php" class="btn">Manage Profile</a>
        </section>

        <section id="daily-update" class="section">
            <h2>Daily Update</h2>
            <form action="submit_update.php" method="POST">
                <textarea name="update_text" placeholder="What is the class doing today?" required></textarea>
                <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
                <input type="hidden" name="staff_id" value="<?php echo $staffID; ?>">
                <button type="submit" class="btn">Submit Update</button>
            </form>
        </section>

        <section id="staff-action" class="section">
            <h2>Notify Parents</h2>
            <p>Click the button below to notify parents that they can pick up their child.</p>
            <form method="POST">
            <button id="notifyButton" class="btn"><i class="fas fa-bell"></i> Notify Parents</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Kindergarten Staff Portal. All rights reserved.</p>
    </footer>

    <!-- Add an audio element for the notification sound -->
    <audio id="notification-sound" src="notify.mp3" preload="auto"></audio>

    <?php
// Assume $notification_sent is set based on your server-side logic
$notification_sent = true; // Example, replace with actual condition
?>

<script type="text/javascript">
    // Pass PHP variable to JavaScript
    var notificationSent = <?php echo json_encode($notification_sent); ?>;

    // JavaScript to handle the notification
    document.addEventListener('DOMContentLoaded', (event) => {
        if (notificationSent) {
            // Play the notification sound
            var audio = document.getElementById('notification-sound');
            if (audio) {
                audio.play();
            }

            // Show a notification message
            alert('Notification sent to parents!');
        }
    });
</script>

</body>

</html>

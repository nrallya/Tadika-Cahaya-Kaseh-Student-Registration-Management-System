<?php
include 'connection.php'; // Include your database connection file

// Define maximum capacities for each age group
$max_capacity = [
    4 => 10,
    5 => 10,
    6 => 10
];

// Fetch all classes
$sql_classes = "SELECT class_id, class_name FROM class";
$result_classes = mysqli_query($conn, $sql_classes);

// Initialize an array to hold class details
$class_details = [];

// Loop through each class to fetch staff and children details
while ($row_class = mysqli_fetch_assoc($result_classes)) {
    $class_id = $row_class['class_id'];
    $class_name = $row_class['class_name'];

    // Fetch staff in charge
    $sql_staff = "SELECT s.name
                  FROM staff s
                  INNER JOIN class cs ON s.staffID = cs.class_id
                  WHERE cs.class_id = $class_id";
    $result_staff = mysqli_query($conn, $sql_staff);
    $staff_in_charge = [];

    if ($result_staff && mysqli_num_rows($result_staff) > 0) {
        while ($row_staff = mysqli_fetch_assoc($result_staff)) {
            $staff_in_charge[] = $row_staff['name'];
        }
    } else {
        $staff_in_charge[] = 'Not assigned';
    }

    // Fetch children assigned to the class
    $sql_children = "SELECT ch.full_name, ch.age
                     FROM child ch
                     WHERE ch.class_id = $class_id";
    $result_children = mysqli_query($conn, $sql_children);
    $children_assigned = [];
    $total_students = 0;

    if ($result_children && mysqli_num_rows($result_children) > 0) {
        while ($row_child = mysqli_fetch_assoc($result_children)) {
            $children_assigned[] = $row_child['full_name'];
            $total_students++;

            // Calculate maximum capacity based on child's age
            $age_of_student = $row_child['age'];
            $max_capacity_for_age = isset($max_capacity[$age_of_student]) ? $max_capacity[$age_of_student] : 'Unknown';
        }
    } else {
        $children_assigned[] = 'No students assigned';
    }

    // Store class details in array
    $class_details[] = [
        'class_name' => $class_name,
        'staff_in_charge' => $staff_in_charge,
        'children_assigned' => $children_assigned,
        'total_students' => $total_students,
        'max_capacity' => $max_capacity_for_age // Maximum capacity based on age
    ];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Details</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            padding: 0;
            margin: 0;
        }

        /* Container Styles */
        .container {
            max-width: 700px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        /* Header Styles */
        header {
            background-color: #52B4B7;
            color: white;
            text-align: center;
            padding: 15px;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 30px;
            color: white;
            margin-bottom: 20px;
            text-align: center;
        }

        /* Class Details Section */
        .class-details {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .class-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .class-name {
            font-size: 24px;
            font-weight: bold;
            color: #333333;
        }

        .total-students {
            font-size: 18px;
            color: #777777;
        }

        .max-capacity {
            font-size: 18px;
            color: #777777;
        }

        /* Staff and Children Lists */
        .staff-list, .children-list {
            margin-bottom: 15px;
        }

        .staff-list h3, .children-list h3 {
            margin-bottom: 10px;
            color: #555555;
        }

        .staff-list ul, .children-list ul {
            list-style-type: none;
            padding-left: 0;
        }

        .staff-list ul li, .children-list ul li {
            margin-bottom: 5px;
            color: #777777;
            padding-left: 20px;
            position: relative;
        }

        .staff-list ul li::before, .children-list ul li::before {
            content: "\2022";
            color: #52B4B7;
            font-size: 12px;
            position: absolute;
            left: 0;
            top: 5px;
        }

        .no-data {
            color: #777777;
            font-style: italic;
        }

        /* Button Styles */
        .back-button {
            padding: 12px 25px;
            margin: 20px 0;
            background: linear-gradient(135deg, #52B4B7, #549DB7);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: auto;
            white-space: nowrap;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            font-size: 16px;
            text-transform: uppercase;
            display: block;
            text-align: center;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <header>
        <h1>Class Details</h1>
    </header>
    <div class="container">
        <?php foreach ($class_details as $class): ?>
            <div class="class-details">
                <div class="class-header">
                    <div class="class-name"><?php echo htmlspecialchars($class['class_name']); ?></div>
                    <div class="total-students">Total Students: <?php echo htmlspecialchars($class['total_students']); ?></div>
                    <div class="max-capacity">Max Capacity: <?php echo htmlspecialchars($class['max_capacity']); ?></div> <!-- Display max capacity -->
                </div>

                <div class="staff-list">
                    <h3>Staff in Charge:</h3>
                    <ul>
                        <?php if (!empty($class['staff_in_charge'])): ?>
                            <?php foreach ($class['staff_in_charge'] as $staff): ?>
                                <li><?php echo htmlspecialchars($staff); ?></li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="no-data">Not assigned</li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="children-list">
                    <h3>Children Assigned:</h3>
                    <ul>
                        <?php if (!empty($class['children_assigned'])): ?>
                            <?php foreach ($class['children_assigned'] as $child): ?>
                                <li><?php echo htmlspecialchars($child); ?></li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="no-data">No students assigned</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        <?php endforeach; ?>
        <a href="adminmainpage.php" class="back-button">Back</a>
    </div>
</body>
</html>

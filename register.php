<?php
$pageTitle = "Register Child";
session_start();

$register_error = "";

// Establish a connection to the database
$conn = new mysqli('localhost', 'root', '', 'tadika cahaya kaseh');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user has registered a parent
$userID = $_SESSION['userID'];
$sql = "SELECT * FROM parent WHERE userID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // Redirect to parent registration page if parent is not registered
    header("Location: parentregistration.php");
    exit();
}

// Function to get total students for a given class_id
function getTotalStudents($conn, $class_id) {
    $sql_count = "SELECT COUNT(*) AS total_students FROM child WHERE class_id = ?";
    $stmt_count = $conn->prepare($sql_count);
    $stmt_count->bind_param("i", $class_id);
    $stmt_count->execute();
    $result_count = $stmt_count->get_result();
    
    if ($result_count->num_rows > 0) {
        $row_count = $result_count->fetch_assoc();
        return $row_count['total_students'];
    } else {
        return 0;
    }
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
  // Child details from form
  $full_name = isset($_POST['full_name']) ? $_POST['full_name'] : '';
  $age = isset($_POST['age']) ? $_POST['age'] : '';
  $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
  $DOB = isset($_POST['DOB']) ? $_POST['DOB'] : '';
  $IC = isset($_POST['IC']) ? $_POST['IC'] : '';
  $Birthplace = isset($_POST['Birthplace']) ? $_POST['Birthplace'] : '';
  $Address = isset($_POST['Address']) ? $_POST['Address'] : '';
  $userID = isset($_POST['userID']) ? $_POST['userID'] : '';
  
  // Determine class_id based on age
  if ($age == 4) {
      $class_id = 3; // Class ID for Year 4
  } elseif ($age == 5) {
      $class_id = 1; // Class ID for Year 6
  } elseif ($age == 6) {
      $class_id = 1; // Class ID for Year 6
  } else {
      $register_error = "Invalid age entered.";
  }

  // Example: Age limits and corresponding maximum students per class
  $age_limits = [
      4 => 10,
      5 => 10,
      6 => 10
  ];

  // Check if the child's age is within the specified limits
  if (array_key_exists($age, $age_limits)) {
      $max_students = $age_limits[$age];
      $total_students = getTotalStudents($conn, $class_id);
      if ($total_students >= $max_students) {
          $register_error = "Cannot register child: Maximum students reached for age $age in this class.";
      }
  } else {
      $register_error = "Invalid age entered.";
  }
  
  // Proceed with insertion if no error
  if (empty($register_error)) {
      // Prepare SQL statement
      $sql_child = "INSERT INTO child (full_name, age, gender, DOB, IC, Birthplace, Address, userID, class_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
      $stmt_child = $conn->prepare($sql_child);

      if ($stmt_child) {
          // Bind parameters
          $stmt_child->bind_param("sisssisii", $full_name, $age, $gender, $DOB, $IC, $Birthplace, $Address, $userID, $class_id);

          // Execute statement
          if ($stmt_child->execute()) {
              echo "Child data inserted successfully";
          } else {
              echo "Error executing statement: " . $stmt_child->error;
          }

          // Close statement
          $stmt_child->close();
      } else {
          echo "Error preparing statement: " . $conn->error;
      }
  }

  // Close connection
  $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <style>
    /* Reset margins and paddings */
    html, body {
      margin: 0;
      padding: 0;
    }

    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(135deg, #52B4B7, #549DB7, #FBFDFF);
      min-height: 100vh;
      overflow-y: auto;
    }

    .container {
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      padding: 20px;
      width: 90%;
      max-width: 800px;
      margin: 60px auto;
      text-align: left;
      overflow-x: auto;
    }

    h1 {
      color: #333;
      font-size: 35px;
      margin-bottom: 30px;
      text-align: center;
    }

    label {
      font-size: 16px;
      margin-bottom: 5px;
      display: block;
      color: #333;
    }

    input[type="text"], input[type="number"], input[type="date"], textarea, select {
      width: calc(100% - 20px);
      padding: 10px;
      margin-bottom: 20px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 16px;
    }

    input[type="submit"] {
      background-color: #4CAF50;
      color: white;
      border: none;
      padding: 15px 20px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 18px;
      width: 100%;
    }

    input[type="submit"]:hover {
      background-color: #45a049;
    }

    h2 {
      color: #333;
      font-size: 28px;
      margin-bottom: 20px;
      border-bottom: 2px solid #ddd;
      padding-bottom: 10px;
    }

    textarea {
      height: 100px;
    }

    .back-button {
        display: inline-block;
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        text-decoration: none;
        border: none; /* Remove default button border */
        border-radius: 4px;
        text-align: center;
        margin-top: 10px; /* Adjusted for spacing */
        transition: background-color 0.3s ease;
        cursor: pointer; /* Add cursor pointer */
    }

    .error-message {
        color: red;
        font-weight: bold;
        margin-top: 10px;
    }

    </style>
</head>
<body>

<!-- Form to collect child details -->
<div class="container">
    <h1>Register Child</h1>
    <?php if (!empty($register_error)): ?>
        <p class="error-message"><?php echo $register_error; ?></p>
    <?php endif; ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <h2>Child Details</h2>
        <label for="child_name">Child Name:</label>
        <input type="text" id="child_name" name="full_name" required>

        <label for="child_age">Child Age:</label>
        <input type="number" id="child_age" name="age" required>

        <label for="child_gender">Child Gender:</label>
        <select id="child_gender" name="gender" required>
            <option value="">Select gender</option>
            <option value="female">Female</option>
            <option value="male">Male</option>
        </select>

        <label for="child_dob">Date of Birth:</label>
        <input type="date" id="child_dob" name="DOB" required>

        <label for="child_ic">Child IC:</label>
        <input type="text" id="child_ic" name="IC" required>

        <label for="child_birthplace">Child Birthplace:</label>
        <input type="text" id="child_birthplace" name="Birthplace" required>

        <label for="child_address">Child Address:</label>
        <textarea id="child_address" name="Address" required></textarea>

        <!-- Hidden input for userID -->
        <input type="hidden" name="userID" value="<?php echo $_SESSION['userID']; ?>">

        <input type="submit" value="Submit">
        <a href="mainpage.php" class="back-button">Back</a>
    </form>
</div>


</body>
</html>

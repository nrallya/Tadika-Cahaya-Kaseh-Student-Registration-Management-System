<?php
// Start the session to access session variables
session_start();

// Check if the user is logged in
if (!isset($_SESSION['userID'])) {
    // Redirect the user to the login page or display a message
    header("Location: login.php"); // Adjust the URL to your login page
    exit();
}

include "connection.php"; // Ensure this file contains your database connection settings

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userID = $_SESSION['userID'];

$sql = "SELECT childID, full_name, age, gender, DOB, IC, Address AS childAddress, FeePaid
        FROM child
        WHERE userID = ?"; // Adjust to filter child records by userID

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registered Child Details</title>
  <style>
    /* Reset margins and paddings */
    html, body {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      background: linear-gradient(135deg, #52B4B7, #549DB7, #FBFDFF);
      min-height: 100vh;
      overflow-y: auto;
    }

    .container {
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
      padding: 30px;
      width: 90%;
      max-width: 1000px;
      margin: 20px;
      text-align: center;
    }

    h1 {
      color: #333;
      font-size: 32px;
      margin-bottom: 20px;
      text-transform: uppercase;
      letter-spacing: 1px;
      border-bottom: 2px solid #549DB7;
      padding-bottom: 10px;
    }

    p {
      font-size: 18px;
      margin-bottom: 20px;
      color: #555;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th, td {
      padding: 12px 15px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: #f2f2f2;
      color: #333;
      font-weight: 600;
      text-transform: uppercase;
    }

    tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    tr:hover {
      background-color: #f1f1f1;
    }

    .btn-container {
      display: flex;
      justify-content: flex-end;
      margin-top: 20px;
    }

    .btn-container a {
      text-decoration: none;
      margin-left: 10px;
    }

    button {
      padding: 12px 25px;
      margin-top: 20px;
      background: #0056b3;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      width: auto;
      white-space: nowrap;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      transition: background 0.3s ease, transform 0.3s ease;
      font-size: 16px;
      text-transform: uppercase;
      display: inline-block;
    }

    button:hover {
      background: linear-gradient(135deg, #549DB7, #52B4B7);
      transform: translateY(-2px);
    }
  </style>
</head>
<body>

<div class="container">
  <h1>Registered Child Details</h1>

  <?php
  if ($result->num_rows > 0) {
      echo "<table>";
      echo "<tr><th>Full Name</th><th>Age</th><th>Gender</th><th>Date of Birth</th><th>IC Number</th><th>Address</th><th>Action</th></tr>";
      
      // Output data of each row
      while($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<td>" . htmlspecialchars($row["full_name"]) . "</td>";
          echo "<td>" . $row["age"] . "</td>";
          echo "<td>" . $row["gender"] . "</td>";
          echo "<td>" . $row["DOB"] . "</td>";
          echo "<td>" . $row["IC"] . "</td>";
          echo "<td>" . htmlspecialchars($row["childAddress"]) . "</td>";
          echo "<td>";
         if ($row["FeePaid"] == 1) {
          
              echo '<form action"invoiceForm" action="https://invoice.stripe.com/i/acct_1PUoT9AzjBDvh5ju/test_YWNjdF8xUFVvVDlBempCRHZoNWp1LF9RTXZVa1ZnSGxmQXROMm1pbjFuUVg0QTgxUmIzWmtnLDExMDAwOTk1NQ02004no27tAv?s=db" method="get">
                 <p>Paid</p>    
                <button type="submit">Generate Receipt</button>
                    </form>';
          } else {
              echo '<form action="create_checkout_session.php" method="post">
                      <input type="hidden" name="childID" value="' . htmlspecialchars($row["childID"]) . '">
                      <button type="submit">Pay Now</button>
                    </form>';
          }
          echo "</td>";
          echo "</tr>";
      }
      echo "</table>";
  } else {
      echo "<p>No registered children found.</p>";
  }
  
  $stmt->close();
  $conn->close();
  ?>
  
  <div class="btn-container">
    <a href="mainpage.php">
      <button type="button">Back</button>
    </a>
  </div>
</div>

</body>
</html>

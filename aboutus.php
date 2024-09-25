<?php
$pageTitle = "About Us";
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
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .container {
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      padding: 30px;
      width: 90%;
      max-width: 800px;
      margin: 40px auto;
      text-align: left;
    }

    h1 {
      color: #333;
      font-size: 36px;
      margin-bottom: 20px;
      text-align: center;
      text-transform: uppercase;
    }

    p {
      font-size: 18px;
      line-height: 1.6;
      color: #555;
      margin-bottom: 20px;
    }

    .highlight {
      background-color: #f8f9fa;
      padding: 20px;
      border-radius: 8px;
      margin-bottom: 30px;
    }

    .highlight h2 {
      font-size: 24px;
      color: #333;
      margin-bottom: 15px;
      text-transform: uppercase;
    }

    .highlight p {
      font-size: 18px;
      color: #555;
      line-height: 1.6;
      margin-bottom: 15px;
    }

    .contact-info {
      margin-bottom: 30px;
      padding-bottom: 20px;
      border-bottom: 1px solid #ddd;
    }

    .contact-info h2 {
      font-size: 24px;
      color: #333;
      margin-bottom: 10px;
      text-transform: uppercase;
    }

    .contact-info p {
      font-size: 18px;
      color: #555;
      margin-bottom: 8px;
    }

    .contact-info a {
      color: #007bff;
      text-decoration: none;
    }

    .contact-info a:hover {
      text-decoration: underline;
    }

    .location {
      margin-bottom: 30px;
    }

    .location h2 {
      font-size: 24px;
      color: #333;
      margin-bottom: 10px;
      text-transform: uppercase;
    }

    .location p {
      font-size: 18px;
      color: #555;
      margin-bottom: 15px;
    }

    .map {
      width: 100%;
      height: 400px;
      border: 0;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .back-button-container {
      text-align: center;
      margin-top: 20px;
    }

    .back-button {
      display: inline-block;
      padding: 12px 24px;
      background-color: #007bff;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      font-size: 16px;
      transition: background-color 0.3s ease;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .back-button:hover {
      background-color: #0056b3;
    }
    </style>
</head>
<body>

<div class="container">
    <h1>About Us</h1>
    <p>Welcome to Tadika Cahaya Kasih! We are a dedicated and nurturing kindergarten located in the heart of the community. Our mission is to provide a safe, stimulating, and caring environment where children can learn, grow, and develop foundational skills that will serve them throughout their educational journey.</p>

    <div class="highlight">
        <h2>Our Philosophy</h2>
        <p>At Tadika Cahaya Kasih, we believe in fostering a love of learning through play-based activities that are both fun and educational. Our experienced teachers are passionate about early childhood education and are committed to helping each child reach their full potential.</p>
    </div>

    <div class="contact-info">
        <h2>Contact Us</h2>
        <p>Headmaster: <a href="tel:+60124142213">012-4142213</a></p>
    </div>
    

    <div class="location">
        <h2>Our Location</h2>
        <p>We are conveniently located at:</p>
        <p><strong>1, Jalan Sri Wangsa 16, Taman Sri Wangsa, 30200 Batu Gajah, Perak, Malaysia</strong></p>
        <iframe class="map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63716.83613460764!2d101.04084682910158!3d4.462710596589273!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cae887c9b0dc83%3A0x41fbb3c4c94e7669!2s1%2C%20Jln%20Sri%20Wangsa%2016%2C%20Taman%20Sri%20Wangsa%2C%2030200%20Batu%20Gajah%2C%20Perak!5e0!3m2!1sen!2smy!4v1623045072476!5m2!1sen!2smy" allowfullscreen="" loading="lazy"></iframe>
    </div>

    <div class="back-button-container">
        <a href="mainpage.php" class="back-button">Back to Homepage</a>
    </div>
</div>

</body>
</html>

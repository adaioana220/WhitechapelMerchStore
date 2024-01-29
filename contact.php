<?php
// Start the session
session_start();

$host = "localhost";
$username = "root";
$password = "";
$database = "users_db";

// Create a database connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the session variable containing the email exists
if (isset($_SESSION['user_email'])) {
    $userEmail = $_SESSION['user_email'];

    // Retrieve name and surname from the database
    $userInfoQuery = "SELECT name, surname FROM user_data WHERE email = '$userEmail'";
    $userInfoResult = $conn->query($userInfoQuery);

    if ($userInfoResult->num_rows > 0) {
        $userInfo = $userInfoResult->fetch_assoc();
        $userName = $userInfo['name'];
        $userSurname = $userInfo['surname'];
    } else {
        // Handle the case where user information is not found
        $userName = 'User not found';
        $userSurname = 'User not found';
    }
} else {
    // Redirect the user to the login page if the session variable is not set
    header("Location: login.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize the input
    $message = trim($_POST['message']);
    $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

    // Check if the message is within the length limits
    if (strlen($message) < 100) {
        echo "<script>alert('The message must be at least 100 characters long.')</script>";
    } elseif (strlen($message) > 700) {
        echo "<script>alert('The message cannot exceed 700 characters.')</script>";
    } else {
        // Process the form if the message length is within the allowed range
        echo "<script>alert('Message sent successfully! You will be contacted as soon as possible. Thank you.')</script>";
        header("Location: home.html");    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Whitechapel Merchandise Store</title>
    <link rel="stylesheet" href="contactStyle.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
          crossorigin="anonymous"
    />
</head>
<body>

<nav class="navbar bg-black navbar-expand-lg">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler"
                aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#"><img src="img/whitechapel-logo.png" width="150" height="50" alt=""></a>
        <div class="collapse navbar-collapse" id="navbarToggler">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active text-white" href="home.html">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active text-white" href="theband.html">The Band</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active text-white" href="contact.php">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active text-white" target="_blank"
                       href="https://github.com/adaioana220/WhitechapelMerchStore">GitHub</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="wrapper">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <i class="bi bi-envelope-fill" ></i>
            <h1>Contact us</h1>

            <div class="name-box">
                <p id="p1">Name: <?php echo $userName; ?> <?php echo $userSurname; ?></p>
            </div>

            <div class="email">
                <p id="p2">Email: <?php echo $userEmail; ?></p>
            </div>

            <label id="message-label" for="message">Message (up to 700 characters):</label><br>
            <textarea id="message" name="message" rows="5" cols="50" maxlength="700"></textarea><br>

            <button type="submit" class= "btn" value="Submit">Submit</button>
        </form>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous">
</script>

</body>
</html>

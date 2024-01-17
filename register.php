<?php
// Database connection parameters
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

// Function to validate email format
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Function to validate name and surname
function validateNameSurname($name) {
    return preg_match('/^[A-Za-z-]+$/', $name);
}

// Handle registration
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $surname = $_POST["surname"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];

    // Validate name and/or surname
     if (!validateNameSurname($name) || !validateNameSurname($surname)) {
         echo "<script>alert('Invalid characters in the name or surname. Only letters and hyphens (-) are allowed.');</script>";
     }
     // Validate email
        elseif (!validateEmail($email)) {
            echo "<script>alert('Invalid email address.');</script>";
        }
        // Validate password
        elseif (strlen($password) < 8 || !preg_match('/[A-Za-z]/', $password) || !preg_match('/\d/', $password)) {
            echo "<script>alert('Password must be at least 8 characters long, contain letters and at least a number.');</script>";
        }
        // Confirm password
        elseif ($password !== $confirmPassword) {
            echo "<script>alert('Passwords do not match.');</script>";
        } else {
            // Check if email is already registered
            $checkEmailQuery = "SELECT * FROM user_data WHERE email = '$email'";
            $result = $conn->query($checkEmailQuery);

            if ($result->num_rows > 0) {
                echo "<script>alert('Email is already registered.');</script>";
            } else {
                // Insert user data into the database
                $insertQuery = "INSERT INTO user_data (name, surname, email, password) VALUES ('$name', '$surname', '$email', '$password')";

                if ($conn->query($insertQuery) === TRUE) {
                    echo "<script>alert('Registration successful. Redirecting to login page.')</script>";
                    echo "<script>window.location.href = 'login.php';</script>";
                    exit();
                } else {
                    echo "<script>alert('Error: " . $insertQuery . "\\n" . $conn->error . "');</script>";
                }
            }
       }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="registerStyle.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
<div class="wrapper">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <h1>Registration Form</h1>
        <div class="input-box">
            <input type="text" placeholder="Name" name="name" required>
            <i class="bi bi-person-fill"></i>
        </div>
        <div class="input-box">
            <input type="text" placeholder="Surname" name="surname" required>
            <i class="bi bi-person"></i>
        </div>
        <div class="input-box">
            <input type="email" placeholder="Email" name="email" required>
            <i class="bi bi-envelope-fill"></i>
        </div>
        <div class="input-box">
            <input type="password" placeholder="Password" name="password" required>
            <i class="bi bi-lock"></i>
        </div>
        <div class="input-box">
            <input type="password" placeholder="Confirm password" name="confirm_password" required>
            <i class="bi bi-lock-fill"></i>
        </div>
        <button type="submit" class= "btn" value="Register">Register</button>
</form>
</body>
</html>

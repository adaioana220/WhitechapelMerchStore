<?php
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

session_start();

// Function to validate email format
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Handle login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Check for empty fields
    if (empty($email) || empty($password)) {
        echo "<script>alert('Please enter both email and password.');</script>";
    } else {
        // Validate email
        if (!validateEmail($email)) {
            echo "<script>alert('Invalid email address.');</script>";
        } else {
            // Checking if user credentials already exist in the database
            $checkUserQuery = "SELECT * FROM user_data WHERE email = '$email' AND password = '$password'";
            $result = $conn->query($checkUserQuery);

            if ($result->num_rows > 0) {
                // Redirect to home.php
                header("Location: home.html");
                $_SESSION['user_email'] = $email;
                exit();
            } else {
                echo "<script>alert('Invalid email or password.');</script>";
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
    <title>Login</title>
    <link rel="stylesheet" href="loginStyle.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="wrapper">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <h1>Login</h1>
            <div class="input-box">
                <input type="email" placeholder="Email" name="email" required autocomplete="email">
                <i class="bi bi-envelope-fill" ></i>
            </div>
            <div class="input-box">
                <input type="password" placeholder="Password" name="password" required>
                <i class="bi bi-lock-fill"></i>
            </div>

            <button type="submit" class= "btn" value="Login">Log in</button>

            <div class="register-link">
                <p>Don't have an account? <a href = "register.php">Register</a></p>
            </div>
        </form>
    </div>
</body>
</html>

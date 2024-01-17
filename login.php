<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $surname = $_POST["surname"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    try {
        require_once "connection.php";

        $query = "INSERT INTO user_data (name, lastname, email, password) VALUES(?, ?, ?, ?);";

        $stmt = $pdo->prepare($query);

        $stmt->execute([]);

        $pdo = null;
        $stmt = null;
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
} else {
    header("Location: home.html");
}

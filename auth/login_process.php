<?php

session_start();

require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.php");
    exit;
}

$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";

if ($email === "" || $password === "") {
    die("Please enter your email and password.");
}

$sql = "SELECT id, full_name, email, password, role
        FROM users
        WHERE email = :email
        LIMIT 1";

$stmt = $pdo->prepare($sql);
$stmt->execute(["email" => $email]);

$user = $stmt->fetch();

if (!$user) {
    die("Invalid email or password.");
}

if (!password_verify($password, $user["password"])) {
    die("Invalid email or password.");
}

$_SESSION["user_id"] = $user["id"];
$_SESSION["full_name"] = $user["full_name"];
$_SESSION["email"] = $user["email"];
$_SESSION["role"] = $user["role"];

header("Location: ../dashboard.php");
exit;
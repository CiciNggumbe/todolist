<?php
session_start();
require 'db.php';

if (isset($_POST['submit'])) {
    $username = trim($_POST['username']);
    $password = hash('sha256', $_POST['password']);

    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing the SQL statement.");
    }

    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        header('Location: index.php');
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>
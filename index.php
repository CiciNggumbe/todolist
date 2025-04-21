<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['tambah'])) {
    $task = $_POST['task'];
    if (!empty($task)) {
        $stmt = $conn->prepare("INSERT INTO todos (user_id, task) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $task);
        $stmt->execute();
    }
}

if (isset($_GET['selesai'])) {
    $id = $_GET['selesai'];
    $conn->query("UPDATE todos SET selesai = 1 WHERE id = $id AND user_id = $user_id");
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $conn->query("DELETE FROM todos WHERE id = $id AND user_id = $user_id");
}

$todos = $conn->query("SELECT * FROM todos WHERE user_id = $user_id");
?>
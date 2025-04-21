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

<!DOCTYPE html>
<html>
<head>
    <title>To Do List</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background-color: #87CEEB; /* Warna Biru Langit */
            margin: 0; 
            padding: 0;
        }

        .container {
            width: 400px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border: 1px solid #87CEEB;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #87CEEB;
        }

        form {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        form input[type="text"] {
            width: 70%;
            padding: 10px;
            border: 1px solid #87CEEB;
            border-radius: 5px;
            display: inline-block;
        }

        form button {
            width: 20%;
            padding: 10px;
            background-color: #87CEEB;
            color: white;
            border: 1px solid #87CEEB;
            border-radius: 5px;
            cursor: pointer;
            display: inline-block;
        }

        form button:hover {
            background-color: #4682B4;
        }

        .task {
            background-color: #f9f9f9;
            border: 1px solid #87CEEB;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .task.done {
            text-decoration: line-through;
            color: gray;
        }

        .task a {
            color: #87CEEB;
            text-decoration: none;
            margin-left: 10px;
        }

        .task a:hover {
            text-decoration: underline;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            border-top: 1px solid #87CEEB;
            padding-top: 10px;
        }

        .footer h3, .footer h4 {
            margin: 5px 0;
            color: black;
        }

        .footer img {
            border-radius: 10px;
            width: 150px;
        }

    </style>
</head>
<body>
<div class="container">
<div class="footer">
        <h3>Cicilia Nggumbe</h3>
        <h4>235314098</h4>
        <img src="Cici1.jpeg" alt="Cici 1">
    </div>
    <h2>Halo, <?= htmlspecialchars($_SESSION['username']) ?> 👋</h2>
    
    <form method="POST">
        <input type="text" name="task" placeholder="Teks to do" required>
        <button type="submit" name="tambah">Tambah</button>
    </form>

    <form method="GET" action="logout.php">
        <button type="submit" name="logout">Logout</button>
    </form>

    <hr>

    <?php while ($row = $todos->fetch_assoc()): ?>
        <div class="task <?= $row['selesai'] ? 'done' : '' ?>">
            <div><?= htmlspecialchars($row['task']) ?></div>
            <div>
                <?php if (!$row['selesai']): ?>
                    <a href="?selesai=<?= $row['id'] ?>">Selesai</a>
                <?php endif; ?>
                <a href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Hapus tugas ini?')">Hapus</a>
            </div>
        </div>
    <?php endwhile; ?>
</body>
</html>
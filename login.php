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

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #87CEEB; /* Warna Biru Langit */
            margin: 0;
            padding: 0;
        }

        .login-box {
            width: 400px;
            margin: 100px auto;
            border: 1px solid #87CEEB;
            padding: 30px;
            background-color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .login-box h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #87CEEB;
        }

        .form-group {
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
        }

        .form-group label {
            width: 30%;
        }

        .form-group input {
            width: 65%;
            padding: 10px;
            border: 1px solid #87CEEB;
            border-radius: 5px;
        }

        .submit-btn {
            display: flex;
            justify-content: center;
        }

        .submit-btn input {
            padding: 10px 20px;
            border: none;
            background-color: #87CEEB;
            color: white;
            cursor: pointer;
            border-radius: 5px;
        }

        .submit-btn input:hover {
            background-color: #4682B4;
        }

        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Login</h2>
    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>User Name :</label>
            <input type="text" name="username" required>
        </div>

        <div class="form-group">
            <label>Password :</label>
            <input type="password" name="password" required>
        </div>

        <div class="submit-btn">
            <input type="submit" name="submit" value="Login">
        </div>
    </form>
</div>

</body>
</html>
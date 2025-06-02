<?php
session_start();
require_once 'config/db.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if ($username === '' || $password === '') {
        $error = "Username dan password wajib diisi.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $admin = $stmt->fetch();

        // Cek username dan password tanpa hash
        if ($admin && $password === $admin['password']) {
            $_SESSION['admin'] = $admin['username'];
            header("Location: admin/dashboard.php");
            exit;
        } else {
            $error = "Username atau password salah!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin - Ngacup</title>
    <style>
        body { font-family: Arial; background: #f2f2f2; }
        .login-box { width: 300px; margin: 100px auto; background: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input[type="text"], input[type="password"] { width: 90%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px; }
        button { padding: 10px; width: 97%; background: #007bff; color: white; border: none; border-radius: 4px; }
        .error { color: red; font-size: 14px; margin-bottom: 10px; }
        .btn-back{ display: block; text-decoration: none; margin-top: 10px; padding: 10px 0; background-color: #6c757d; width: 97%; box-sizing: border-box; text-align: center; border: none; color: white; border-radius: 4px}
    </style>
</head>
<body>
<div class="login-box">
    <h2>Login Admin</h2>
    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Masuk</button>
    </form>
    <a class="btn-back" href="index.php">← Kembali ke Dashboard</a>
</div>
</body>
</html>

<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Registrasi Coffee Shop</title>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;500&display=swap');

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    body, html {
        height: 100%;
        font-family: 'Poppins', sans-serif;
    }

    body {
        background: url('uploads/foto_login.png') no-repeat center center fixed;
        background-size: cover;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #3e2f1c;
    }

    .container {
        background: rgba(255, 255, 255, 0.85);
        padding: 40px 50px;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        width: 360px;
        text-align: center;
        backdrop-filter: blur(6px);
        color: #3e2f1c;
    }

    h2 {
        margin-bottom: 25px;
        font-weight: 500;
        letter-spacing: 1px;
        color: #6f4e37;
        text-shadow: none;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 12px 15px;
        margin: 10px 0 20px 0;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 16px;
        outline: none;
        transition: 0.3s ease;
        background-color: #fff;
        color: #3e2f1c;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="password"]:focus {
        box-shadow: 0 0 8px #d7a86e;
        border-color: #b58a47;
    }

    button {
        background-color: #d7a86e;
        border: none;
        padding: 14px 25px;
        font-size: 16px;
        color: #3e2f1c;
        font-weight: 600;
        border-radius: 10px;
        cursor: pointer;
        transition: 0.3s ease;
        width: 100%;
    }

    button:hover {
        background-color: #b58a47;
        color: #fff;
    }

    .message {
        margin-bottom: 15px;
        font-size: 14px;
        padding: 10px;
        border-radius: 8px;
    }

    .error {
        background-color: #a34747;
        color: #ffe5e5;
    }

    .success {
        background-color: #72b472;
        color: #e6f5e6;
    }

    .coffee-icon {
        font-size: 50px;
        margin-bottom: 15px;
        color: #d7a86e;
        text-shadow: 0 0 7px #6f4e37;
    }

    .login-link {
        margin-top: 20px;
        font-size: 14px;
        color: #6f4e37;
    }

    .login-link a {
        color: #b58a47;
        text-decoration: none;
        font-weight: 600;
        margin-left: 5px;
    }

    .login-link a:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>

<div class="container">
    <div class="coffee-icon">☕</div>
    <h2>Registrasi Coffee Shop</h2>

    <?php
    if (isset($_SESSION['error'])) {
        echo '<div class="message error">'.htmlspecialchars($_SESSION['error']).'</div>';
        unset($_SESSION['error']);
    }

    if (isset($_SESSION['success'])) {
        echo '<div class="message success">'.htmlspecialchars($_SESSION['success']).'</div>';
        unset($_SESSION['success']);
    }
    ?>

    <form action="includes/proses_register.php" method="POST" autocomplete="off">
        <input type="text" name="nama" placeholder="Nama Lengkap" required />
        <input type="email" name="email" placeholder="Email" required />
        <input type="password" name="password" placeholder="Password" required minlength="6" />
        <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required minlength="6" />
        <button type="submit">Daftar</button>
    </form>

    <div class="login-link">
        Sudah punya akun?
        <a href="login.php">Masuk</a>
    </div>
</div>

</body>
</html>

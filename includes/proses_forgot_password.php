<?php
session_start();
include __DIR__ . '/../config/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Simpan email di session untuk digunakan saat reset
        $_SESSION['reset_email'] = $email;
        header("Location: ../reset_password.php");
    } else {
        $_SESSION['error'] = "Email tidak ditemukan.";
        header("Location: ../forgot_password.php");
    }
    exit();
}

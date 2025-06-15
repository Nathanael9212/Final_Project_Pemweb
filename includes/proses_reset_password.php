<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($password !== $confirm) {
        $_SESSION['error'] = "Konfirmasi password tidak cocok.";
        header("Location: ../register.php");
        exit;
    }

    try {
        // Cek apakah email sudah digunakan
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $_SESSION['error'] = "Email sudah terdaftar.";
            header("Location: ../register.php");
            exit;
        }

        // Hash password sebelum simpan
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Simpan user baru
        $stmt = $pdo->prepare("INSERT INTO users (nama, email, password, jenis_user) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nama, $email, $hashed_password, 'user']);

        $_SESSION['success'] = "Pendaftaran berhasil, silakan login.";
        header("Location: ../login.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION['error'] = "Terjadi kesalahan saat mendaftar.";
        header("Location: ../register.php");
        exit;
    }
} else {
    header("Location: ../register.php");
    exit;
}

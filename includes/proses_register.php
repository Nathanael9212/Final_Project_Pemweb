<?php
session_start();
require '../config/db.php';  // pastikan path ini benar ke file koneksi database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validasi password konfirmasi
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Konfirmasi password tidak sesuai.";
        header("Location: ../register.php");
        exit;
    }

    // Validasi email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Format email tidak valid.";
        header("Location: ../register.php");
        exit;
    }

    // Cek email sudah terdaftar atau belum
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetchColumn() > 0) {
        $_SESSION['error'] = "Email sudah digunakan.";
        header("Location: ../register.php");
        exit;
    }

    // Hash password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insert user baru
    $stmt = $pdo->prepare("INSERT INTO users (nama, email, password, jenis_user, status, created_at) VALUES (?, ?, ?, 'user', 'aktif', NOW())");
    $insert = $stmt->execute([$nama, $email, $password_hash]);

    if ($insert) {
        $_SESSION['success'] = "Registrasi berhasil! Silakan login.";
        header("Location: ../login.php");
        exit;
    } else {
        $_SESSION['error'] = "Terjadi kesalahan, coba lagi.";
        header("Location: ../register.php");
        exit;
    }
} else {
    // jika langsung akses proses_register.php tanpa POST
    header("Location: ../register.php");
    exit;
}

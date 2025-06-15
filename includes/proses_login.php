<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Cek status akun
            if ($user['status'] === 'banned') {
                $_SESSION['error'] = "Akun Anda telah diblokir (banned). Silakan hubungi admin.";
                header("Location: ../login.php");
                exit;
            }

            if ($user['status'] === 'suspended') {
                $_SESSION['error'] = "Akun Anda sedang ditangguhkan (suspended).";
                header("Location: ../login.php");
                exit;
            }

            // Jika status aktif, login berhasil
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['jenis_user'] = $user['jenis_user'];

            if ($user['jenis_user'] === 'admin') {
                $_SESSION['is_admin'] = true;
                header("Location: ../admin/dashboard.php");
            } else {
                $_SESSION['is_admin'] = false;
                header("Location: ../index.php");
            }
            exit;
        } else {
            $_SESSION['error'] = "Email atau password salah.";
            header("Location: ../login.php");
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Terjadi kesalahan saat login.";
        header("Location: ../login.php");
        exit;
    }
} else {
    header("Location: ../login.php");
    exit;
}

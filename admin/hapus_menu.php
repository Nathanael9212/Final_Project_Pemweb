<?php
session_start();

// Pastikan hanya admin yang bisa mengakses
if (!isset($_SESSION['jenis_user']) || $_SESSION['jenis_user'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once '../config/db.php';

// Periksa apakah parameter ID tersedia
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manajemen_produk.php");
    exit;
}

$id = (int) $_GET['id'];

// Ambil informasi produk sebelum dihapus (terutama gambar)
$stmt = $pdo->prepare("SELECT gambar FROM menu WHERE id = ?");
$stmt->execute([$id]);
$produk = $stmt->fetch(PDO::FETCH_ASSOC);

// Jika produk tidak ditemukan
if (!$produk) {
    header("Location: manajemen_produk.php");
    exit;
}

// Hapus file gambar jika ada
if (!empty($produk['gambar'])) {
    $gambarPath = '../uploads/' . $produk['gambar'];
    if (file_exists($gambarPath)) {
        unlink($gambarPath); // hapus gambar dari server
    }
}

// Hapus produk dari database
$stmtHapus = $pdo->prepare("DELETE FROM menu WHERE id = ?");
$stmtHapus->execute([$id]);

// Redirect kembali ke halaman manajemen produk
header("Location: manajemen_produk.php");
exit;
?>

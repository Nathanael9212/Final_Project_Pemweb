<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_POST['produk_id'], $_POST['tipe'], $_POST['jumlah'])) {
    die('Data tidak lengkap');
}

$user_id = $_SESSION['user_id'];
$produk_id = (int) $_POST['produk_id'];
$tipe = $_POST['tipe'];
$jumlah = (int) $_POST['jumlah'];

if ($jumlah <= 0) {
    $jumlah = 1; // fallback agar tidak error
}

// Cek apakah item sudah ada di keranjang
$stmt = $pdo->prepare("SELECT id, jumlah FROM keranjang WHERE user_id = ? AND produk_id = ? AND tipe = ?");
$stmt->execute([$user_id, $produk_id, $tipe]);
$existing = $stmt->fetch();

if ($existing) {
    // Update jumlah
    $new_jumlah = $existing['jumlah'] + $jumlah;
    $update = $pdo->prepare("UPDATE keranjang SET jumlah = ? WHERE id = ?");
    $update->execute([$new_jumlah, $existing['id']]);
} else {
    // Insert baru
    $insert = $pdo->prepare("INSERT INTO keranjang (user_id, produk_id, tipe, jumlah) VALUES (?, ?, ?, ?)");
    $insert->execute([$user_id, $produk_id, $tipe, $jumlah]);
}

header("Location: keranjang.php");
exit;

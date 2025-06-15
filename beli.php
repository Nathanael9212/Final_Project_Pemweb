<?php
session_start();
require_once 'config/db.php';
require_once 'includes/auth.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produk_id = isset($_POST['produk_id']) ? (int) $_POST['produk_id'] : 0;
    $tipe = $_POST['tipe'] ?? 'hot';
    $qty = isset($_POST['qty']) ? (int) $_POST['qty'] : 1;

    if ($produk_id <= 0 || $qty <= 0 || !in_array($tipe, ['hot', 'cold'])) {
        header("Location: index.php");
        exit;
    }

    // Ambil data produk dari DB
    $stmt = $pdo->prepare("SELECT * FROM menu WHERE id = ?");
    $stmt->execute([$produk_id]);
    $produk = $stmt->fetch();

    if (!$produk) {
        header("Location: index.php");
        exit;
    }

    $harga = ($tipe === 'hot') ? $produk['harga_hot'] : $produk['harga_cold'];
    $cart_key = $produk_id . '_' . $tipe;

    $item = [
        'id' => $produk_id,
        'nama_produk' => $produk['nama_produk'],
        'kategori' => $produk['kategori'],
        'tipe' => $tipe,
        'harga' => $harga,
        'qty' => $qty,
        'gambar' => $produk['gambar']
    ];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$cart_key])) {
        $_SESSION['cart'][$cart_key]['qty'] += $qty;
    } else {
        $_SESSION['cart'][$cart_key] = $item;
    }

    header("Location: keranjang.php");
    exit;
} else {
    header("Location: index.php");
    exit;
}

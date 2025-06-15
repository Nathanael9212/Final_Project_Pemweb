<?php
session_start();
require_once 'config/db.php';
require_once 'includes/auth.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

if (
    !isset($_POST['checkout_ids']) || !is_array($_POST['checkout_ids']) ||
    !isset($_POST['alamat']) || !isset($_POST['metode_pengiriman']) ||
    !isset($_POST['metode_pembayaran']) || !isset($_POST['total_bayar']) ||
    !isset($_POST['biaya_kurir'])
) {
    $_SESSION['error'] = 'Data tidak lengkap.';
    header('Location: keranjang.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$checkout_ids = $_POST['checkout_ids'];
$alamat = trim($_POST['alamat']);
$metode_pengiriman = $_POST['metode_pengiriman'];
$metode_pembayaran = $_POST['metode_pembayaran'];
$total_bayar = (int) $_POST['total_bayar'];
$biaya_kurir = (int) $_POST['biaya_kurir'];

try {
    $pdo->beginTransaction();

    // Simpan ke transaksi
    $stmt = $pdo->prepare("
        INSERT INTO transaksi 
        (user_id, alamat, metode_pengiriman, metode_pembayaran, total_bayar, biaya_kurir, status, created_at)
        VALUES (?, ?, ?, ?, ?, ?, 'Menunggu Konfirmasi', NOW())
    ");
    $stmt->execute([$user_id, $alamat, $metode_pengiriman, $metode_pembayaran, $total_bayar, $biaya_kurir]);
    $transaksi_id = $pdo->lastInsertId();

    // Ambil item keranjang
    $placeholders = implode(',', array_fill(0, count($checkout_ids), '?'));
    $query = "
        SELECT k.id AS keranjang_id, k.produk_id, k.tipe, k.jumlah,
               CASE 
                   WHEN k.tipe = 'hot' THEN m.harga_hot
                   WHEN k.tipe = 'cold' THEN m.harga_cold
               END AS harga_satuan
        FROM keranjang k
        JOIN menu m ON k.produk_id = m.id
        WHERE k.user_id = ? AND k.id IN ($placeholders)
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array_merge([$user_id], $checkout_ids));
    $items = $stmt->fetchAll();

    foreach ($items as $item) {
        $subtotal = $item['harga_satuan'] * $item['jumlah'];
        $stmtDetail = $pdo->prepare("
            INSERT INTO transaksi_detail 
            (transaksi_id, produk_id, tipe, jumlah, harga_satuan, subtotal)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmtDetail->execute([
            $transaksi_id,
            $item['produk_id'],
            $item['tipe'],
            $item['jumlah'],
            $item['harga_satuan'],
            $subtotal
        ]);
    }

    // Hapus dari keranjang
    $delete = $pdo->prepare("DELETE FROM keranjang WHERE user_id = ? AND id IN ($placeholders)");
    $delete->execute(array_merge([$user_id], $checkout_ids));

    $pdo->commit();
    $_SESSION['success'] = 'Pesanan berhasil dikonfirmasi.';
    header('Location: pesanan.php');
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    error_log("Checkout error: " . $e->getMessage());
    $_SESSION['error'] = 'Gagal memproses pesanan.';
    header('Location: keranjang.php');
    exit;
}

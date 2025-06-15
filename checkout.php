<?php
session_start();
require_once 'config/db.php';
require_once 'includes/auth.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

if (!isset($_POST['checkout_ids']) || !is_array($_POST['checkout_ids'])) {
    $_SESSION['error'] = 'Tidak ada produk yang dipilih.';
    header('Location: keranjang.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$ids = $_POST['checkout_ids'];

$placeholders = implode(',', array_fill(0, count($ids), '?'));
$query = "
    SELECT k.id AS keranjang_id, k.tipe, k.jumlah, m.id AS produk_id, m.nama_produk,
           CASE 
               WHEN k.tipe = 'hot' THEN m.harga_hot
               WHEN k.tipe = 'cold' THEN m.harga_cold
           END AS harga_satuan
    FROM keranjang k
    JOIN menu m ON k.produk_id = m.id
    WHERE k.user_id = ? AND k.id IN ($placeholders)
";
$stmt = $pdo->prepare($query);
$stmt->execute(array_merge([$user_id], $ids));
$items = $stmt->fetchAll();

$total = 0;
foreach ($items as $item) {
    $total += $item['harga_satuan'] * $item['jumlah'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout - Cafe Shop</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #fef8f4;
            color: #3e3e3e;
            padding: 30px;
        }
        .container {
            max-width: 700px;
            margin: auto;
            background-color: #fff7f0;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #8b4513;
        }
        .produk {
            padding: 10px 0;
            border-bottom: 1px dashed #ccc;
        }
        textarea, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            margin-bottom: 15px;
        }
        button {
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            background-color: #a0522d;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover {
            background-color: #7a391f;
        }
        .back {
            display: inline-block;
            margin-bottom: 20px;
            color: #a0522d;
            text-decoration: none;
        }
        .total {
            font-weight: bold;
            color: #5a2e15;
            margin-top: 15px;
        }
    </style>
</head>
<body>
<div class="container">
    <a href="dashboard.php" class="back">&larr; Kembali ke Dashboard</a>
    <h2>Checkout Pesanan</h2>
    <form action="proses_checkout.php" method="POST">
        <?php foreach ($items as $item): ?>
            <div class="produk">
                <strong><?= htmlspecialchars($item['nama_produk']) ?></strong>
                (<?= $item['tipe'] ?>) x <?= $item['jumlah'] ?>
                <div>Rp <?= number_format($item['harga_satuan'] * $item['jumlah']) ?></div>
                <input type="hidden" name="checkout_ids[]" value="<?= $item['keranjang_id'] ?>">
            </div>
        <?php endforeach; ?>

        <div class="total">Total: Rp <?= number_format($total) ?></div>
        <input type="hidden" name="total_bayar" value="<?= $total ?>">
        <input type="hidden" name="biaya_kurir" value="0">

        <label>Alamat Pengiriman</label>
        <textarea name="alamat" required placeholder="Tulis alamat lengkap..."></textarea>

        <label>Metode Pengiriman</label>
        <select name="metode_pengiriman" required>
            <option value="">Pilih</option>
            <option value="Ambil di Tempat">Ambil di Tempat</option>
            <option value="Kurir Lokal">Kurir Lokal</option>
        </select>

        <label>Metode Pembayaran</label>
        <select name="metode_pembayaran" required>
            <option value="">Pilih</option>
            <option value="COD">COD (Bayar di Tempat)</option>
            <option value="Transfer">Transfer Bank</option>
        </select>

        <button type="submit">Konfirmasi Pesanan</button>
    </form>
</div>
</body>
</html>

<?php
session_start();
require_once 'config/db.php';

if (!isset($_GET['id'])) {
    echo "ID transaksi tidak ditemukan.";
    exit;
}

$transaksi_id = $_GET['id'];

try {
    // Ambil data transaksi dan user
    $stmt = $pdo->prepare("
        SELECT t.*, u.nama AS nama_user, u.email 
        FROM transaksi t 
        JOIN users u ON t.user_id = u.id 
        WHERE t.id = ?
    ");
    $stmt->execute([$transaksi_id]);
    $transaksi = $stmt->fetch();

    if (!$transaksi) {
        echo "Transaksi tidak ditemukan.";
        exit;
    }

    // Ambil detail transaksi dengan harga sesuai tipe
    $stmtDetail = $pdo->prepare("
        SELECT 
            td.*, 
            m.nama_produk AS nama_menu,
            CASE 
                WHEN td.tipe = 'hot' THEN m.harga_hot
                WHEN td.tipe = 'cold' THEN m.harga_cold
                ELSE 0
            END AS harga_menu
        FROM transaksi_detail td
        JOIN menu m ON td.produk_id = m.id
        WHERE td.transaksi_id = ?
    ");
    $stmtDetail->execute([$transaksi_id]);
    $detailItems = $stmtDetail->fetchAll();

} catch (PDOException $e) {
    echo "Terjadi kesalahan: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Pesanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #faf8f5;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #5a3b1a;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
        }
        th {
            background: #7b4f2a;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f9f4ee;
        }
        .total {
            text-align: right;
            font-weight: bold;
            font-size: 16px;
            padding-top: 10px;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #7b4f2a;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Detail Pesanan</h2>

    <p><strong>Nama:</strong> <?= htmlspecialchars($transaksi['nama_user']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($transaksi['email']) ?></p>
    <p><strong>Tanggal:</strong> <?= date('d M Y H:i', strtotime($transaksi['created_at'])) ?></p>

    <table>
        <thead>
            <tr>
                <th>Menu</th>
                <th>Tipe</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $total = 0;
            foreach ($detailItems as $item): 
                $subtotal = $item['jumlah'] * $item['harga_menu'];
                $total += $subtotal;
            ?>
            <tr>
                <td><?= htmlspecialchars($item['nama_menu']) ?></td>
                <td><?= htmlspecialchars($item['tipe']) ?></td>
                <td><?= $item['jumlah'] ?></td>
                <td>Rp <?= number_format($item['harga_menu'], 0, ',', '.') ?></td>
                <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="total">
        Total: Rp <?= number_format($total, 0, ',', '.') ?>
    </div>

    <a href="pesanan.php" class="back-link">← Kembali ke Riwayat</a>
</div>
</body>
</html>

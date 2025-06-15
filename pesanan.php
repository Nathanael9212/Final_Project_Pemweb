<?php
session_start();
require_once 'config/db.php';
require_once 'includes/auth.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT t.id, t.created_at, t.total_bayar, t.status, t.metode_pengiriman, t.metode_pembayaran
    FROM transaksi t
    WHERE t.user_id = ?
    ORDER BY t.created_at DESC
");
$stmt->execute([$user_id]);
$pesanan = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pesanan Saya</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #fdf8f4;
            color: #3e3e3e;
            padding: 30px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background-color: #fff;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #8b4513;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #ffe8d9;
            color: #5a2e15;
        }
        .status {
            font-weight: bold;
            color: #a0522d;
        }
        a.detail {
            color: #8b0000;
            text-decoration: none;
        }
        a.detail:hover {
            text-decoration: underline;
        }
        .back {
            display: inline-block;
            margin-bottom: 20px;
            color: #a0522d;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="container">
    <a href="index.php" class="back">&larr; Kembali ke Dashboard</a>
    <h2>Daftar Pesanan</h2>

    <?php if (count($pesanan) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tanggal</th>
                <th>Total Bayar</th>
                <th>Status</th>
                <th>Pengiriman</th>
                <th>Pembayaran</th>
                <th>Detail</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($pesanan as $row): ?>
            <tr>
                <td>#<?= $row['id'] ?></td>
                <td><?= date('d M Y H:i', strtotime($row['created_at'])) ?></td>
                <td>Rp <?= number_format($row['total_bayar']) ?></td>
                <td class="status"><?= htmlspecialchars($row['status']) ?></td>
                <td><?= htmlspecialchars($row['metode_pengiriman']) ?></td>
                <td><?= htmlspecialchars($row['metode_pembayaran']) ?></td>
                <td><a href="detail_pesanan.php?id=<?= $row['id'] ?>" class="detail">Lihat</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p>Belum ada pesanan.</p>
    <?php endif; ?>
</div>
</body>
</html>

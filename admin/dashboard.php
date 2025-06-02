<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

require_once '../includes/header.php';
require_once '../config/db.php';

// Ambil semua data menu
$stmt = $pdo->query("SELECT * FROM menu ORDER BY kategori");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Ngacup</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            padding: 30px;
        }

        h2 {
            color: #343a40;
            margin-bottom: 20px;
        }

        .btn-group {
            margin-bottom: 20px;
        }

        a.button {
            display: inline-block;
            padding: 10px 16px;
            margin-right: 10px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        a.button:hover {
            background: #0056b3;
        }

        a.logout {
            background: #dc3545;
        }

        a.logout:hover {
            background: #c82333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #dee2e6;
            text-align: left;
        }

        th {
            background-color: #343a40;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        td a {
            color: #007bff;
            text-decoration: none;
        }

        td a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<h2>Dashboard Admin - Ngacup</h2>

<div class="btn-group">
    <a href="tambah_menu.php" class="button">+ Tambah Menu</a>
    <a href="../logout.php" class="button logout">Logout</a>
</div>

<table>
    <tr>
        <th>Nama</th>
        <th>Kategori</th>
        <th>Harga Hot</th>
        <th>Harga Cold</th>
        <th>Best Seller</th>
        <th>Recommended</th>
        <th>Aksi</th>
    </tr>

    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
        <tr>
            <td><?= htmlspecialchars($row['nama_produk']) ?></td>
            <td><?= htmlspecialchars($row['kategori']) ?></td>
            <td><?= $row['harga_hot'] ? 'Rp ' . number_format($row['harga_hot']) : '-' ?></td>
            <td><?= $row['harga_cold'] ? 'Rp ' . number_format($row['harga_cold']) : '-' ?></td>
            <td><?= $row['is_best_seller'] ? '✔️' : '-' ?></td>
            <td><?= $row['is_recommended'] ? '⭐' : '-' ?></td>
            <td>
                <a href="edit_menu.php?id=<?= $row['id'] ?>">Edit</a> |
                <a href="hapus_menu.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus menu ini?')">Hapus</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<?php require_once '../includes/footer.php'; ?>

</body>
</html>

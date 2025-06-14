<?php
session_start();

if (!isset($_SESSION['jenis_user']) || $_SESSION['jenis_user'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$suppressHeader = true; // Jangan tampilkan header default
require_once '../includes/header.php';
require_once '../config/db.php';

// Ambil data produk
$stmtProduk = $pdo->query("SELECT * FROM menu ORDER BY kategori");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Manajemen Produk - Ngacup</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap');

    body {
      margin: 0;
      padding: 20px;
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #c9a66b, #7b4f2a);
      color: #3e2f1c;
      min-height: 100vh;
    }

    header {
      background-color: #7b4f2a;
      color: #fff6e3;
      padding: 20px 30px;
      font-size: 1.8em;
      font-weight: 600;
      border-radius: 12px;
      box-shadow: 0 6px 15px rgba(123,79,42,0.4);
      max-width: 1100px;
      margin: 0 auto 30px auto;
      text-align: center;
    }

    main.container {
      max-width: 1100px;
      margin: 0 auto;
      background: #fff6e3cc;
      padding: 30px 30px 40px 30px;
      border-radius: 16px;
      box-shadow: 0 8px 20px rgba(123,79,42,0.3);
    }

    .top-actions {
      display: flex;
      justify-content: space-between;
      margin-bottom: 25px;
      flex-wrap: wrap;
      gap: 12px;
    }

    .btn {
      padding: 12px 26px;
      border-radius: 12px;
      font-weight: 600;
      text-decoration: none;
      color: #fff6e3;
      box-shadow: 0 4px 12px rgba(123,79,42,0.5);
      transition: background-color 0.3s ease, transform 0.15s ease;
      display: inline-block;
      min-width: 140px;
      text-align: center;
    }

    .btn-add {
      background-color: #b58a47;
    }

    .btn-add:hover {
      background-color: #7b4f2a;
      transform: scale(1.05);
    }

    .btn-back {
      background-color: #7b4f2a;
    }

    .btn-back:hover {
      background-color: #5a3b1a;
      transform: scale(1.05);
    }

    table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0 12px;
    }

    thead th {
      background-color: #7b4f2a;
      color: #fff6e3;
      font-weight: 600;
      font-size: 15px;
      padding: 14px 18px;
      border-radius: 10px 10px 0 0;
      text-align: center;
    }

    tbody tr {
      background: #fff8e7;
      box-shadow: 0 4px 12px rgba(123,79,42,0.15);
      transition: transform 0.25s ease, box-shadow 0.25s ease;
      border-radius: 12px;
    }

    tbody tr:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 25px rgba(123,79,42,0.3);
    }

    tbody td {
      padding: 12px 18px;
      font-size: 14px;
      color: #3e2f1c;
      vertical-align: middle;
      text-align: center;
    }

    .product-image {
      width: 60px;
      height: 60px;
      border-radius: 10px;
      object-fit: cover;
      box-shadow: 0 3px 10px rgba(123,79,42,0.3);
    }

    .action-buttons a {
      padding: 7px 16px;
      margin: 0 5px;
      font-weight: 600;
      border-radius: 12px;
      color: #fff6e3;
      box-shadow: 0 3px 8px rgba(123,79,42,0.4);
      text-decoration: none;
      transition: background-color 0.3s ease, transform 0.15s ease;
      display: inline-block;
    }

    .btn-edit {
      background-color: #b58a47;
    }

    .btn-edit:hover {
      background-color: #7b4f2a;
      transform: scale(1.05);
    }

    .btn-delete {
      background-color: #a43820;
    }

    .btn-delete:hover {
      background-color: #7b1a0d;
      transform: scale(1.05);
    }

    .text-muted {
      color: #9c8f72;
      font-style: italic;
    }

    @media (max-width: 768px) {
      body {
        padding: 15px 10px;
      }

      header {
        font-size: 1.4em;
        padding: 18px 20px;
      }

      main.container {
        padding: 20px 20px 30px 20px;
      }

      thead th, tbody td {
        font-size: 13px;
        padding: 10px 12px;
      }

      .top-actions {
        flex-direction: column;
        gap: 10px;
      }

      .btn {
        min-width: 100%;
        padding: 12px 0;
      }

      .action-buttons a {
        margin: 5px 5px 0 5px;
        padding: 8px 18px;
        font-size: 14px;
      }

      .product-image {
        width: 50px;
        height: 50px;
      }
    }
  </style>
</head>
<body>

<header>Manajemen Produk</header>

<main class="container">
  <div class="top-actions">
    <a href="tambah_menu.php" class="btn btn-add">+ Tambah Produk</a>
    <a href="dashboard.php" class="btn btn-back">← Kembali ke Dashboard</a>
  </div>

  <table>
    <thead>
      <tr>
        <th>Nama</th>
        <th>Kategori</th>
        <th>Harga Hot</th>
        <th>Harga Cold</th>
        <th>Best Seller</th>
        <th>Recommended</th>
        <th>Gambar</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($stmtProduk->rowCount() > 0): ?>
        <?php while ($produk = $stmtProduk->fetch(PDO::FETCH_ASSOC)) : ?>
          <tr>
            <td><?= htmlspecialchars($produk['nama_produk']) ?></td>
            <td><?= htmlspecialchars($produk['kategori']) ?></td>
            <td><?= $produk['harga_hot'] ? 'Rp ' . number_format($produk['harga_hot'], 0, ',', '.') : '-' ?></td>
            <td><?= $produk['harga_cold'] ? 'Rp ' . number_format($produk['harga_cold'], 0, ',', '.') : '-' ?></td>
            <td><?= $produk['is_best_seller'] ? '✔️' : '-' ?></td>
            <td><?= $produk['is_recommended'] ? '⭐' : '-' ?></td>
            <td>
              <?php if (!empty($produk['gambar']) && file_exists('../uploads/' . $produk['gambar'])): ?>
                <img src="../uploads/<?= htmlspecialchars($produk['gambar']) ?>" alt="Gambar <?= htmlspecialchars($produk['nama_produk']) ?>" class="product-image" />
              <?php else: ?>
                <span class="text-muted">Tidak ada</span>
              <?php endif; ?>
            </td>
            <td class="action-buttons">
              <a href="edit_menu.php?id=<?= htmlspecialchars($produk['id']) ?>" class="btn-edit">Edit</a>
              <a href="hapus_menu.php?id=<?= htmlspecialchars($produk['id']) ?>" class="btn-delete" onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="8" class="text-muted" style="text-align:center; padding: 20px;">Belum ada produk yang ditambahkan.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</main>

</body>
</html>

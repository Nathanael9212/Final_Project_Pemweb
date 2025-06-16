<?php
// Simulasi data (bisa diganti dengan query database nanti)
$totalPendapatan = 1250000;
$totalPesanan = 325;
$persenPenjualan = 47;
$pengeluaran = 500000;
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin Cafe</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: Arial, sans-serif;
    }
    body {
      display: flex;
      min-height: 100vh;
      background-color: #f4f4f4;
    }
    .sidebar {
      width: 220px;
      background-color: #2c3e50;
      color: white;
      padding: 20px;
      height: 100vh;
    }
    .sidebar h2 {
      text-align: center;
      margin-bottom: 30px;
    }
    .sidebar a {
      display: block;
      padding: 12px 20px;
      margin-bottom: 10px;
      color: white;
      text-decoration: none;
      background-color: #34495e;
      border-radius: 5px;
    }
    .sidebar a:hover {
      background-color: #1abc9c;
    }
    .main {
      flex: 1;
      padding: 20px;
    }
    .cards {
      display: flex;
      gap: 20px;
      margin-bottom: 30px;
      flex-wrap: wrap;
    }
    .card {
      background: #3498db;
      color: white;
      flex: 1;
      padding: 20px;
      border-radius: 10px;
      min-width: 200px;
    }
    .card h3 {
      margin-bottom: 10px;
    }
    .footer {
      text-align: center;
      margin-top: 50px;
      color: #888;
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <h2>Cafe Admin</h2>
    <a href="#">Dashboard</a>
    <a href="manajemen_produk.php">Manajemen Produk</a>
    <a href="admin_pesanan.php">Pesanan</a>
    <a href="manajemen_user.php">Pengguna</a>
    <a href="logout.php">Logout</a>
  </div>

  <div class="main">
    <h1>Selamat Datang, Admin!</h1>

    <div class="cards">
      <div class="card">
        <h3>Total Pendapatan</h3>
        <p>Rp <?= number_format($totalPendapatan, 0, ',', '.') ?></p>
      </div>
      <div class="card" style="background: #1abc9c;">
        <h3>Total Pesanan</h3>
        <p><?= $totalPesanan ?> Hari Ini</p>
      </div>
      <div class="card" style="background: #f39c12;">
        <h3>Penjualan Hari Ini</h3>
        <p><?= $persenPenjualan ?>%</p>
      </div>
      <div class="card" style="background: #e74c3c;">
        <h3>Pengeluaran</h3>
        <p>Rp <?= number_format($pengeluaran, 0, ',', '.') ?></p>
      </div>
    </div>

    <div class="footer">
      &copy; <?= date('Y') ?> Cafe Shop. Semua hak dilindungi.
    </div>
  </div>
</body>
</html>

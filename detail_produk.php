<?php
session_start();
require_once 'config/db.php';
require_once 'includes/auth.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p style='text-align:center; margin-top:50px;'>Produk tidak ditemukan.</p>";
    exit;
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM menu WHERE id = :id");
$stmt->execute(['id' => $id]);
$produk = $stmt->fetch();

if (!$produk) {
    echo "<p style='text-align:center; margin-top:50px;'>Produk tidak ditemukan.</p>";
    exit;
}

$gambarPath = 'uploads/' . $produk['gambar'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Detail Produk - <?= htmlspecialchars($produk['nama_produk']) ?></title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #f7f3f0;
      color: #3e2723;
    }
    .navbar {
      background-color: #5d4037;
      padding: 15px 30px;
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .container {
      max-width: 1000px;
      margin: 40px auto;
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
      display: flex;
      flex-wrap: wrap;
      gap: 40px;
    }
    .image-box {
      flex: 1 1 300px;
      max-width: 400px;
    }
    .image-box img {
      width: 100%;
      border-radius: 10px;
    }
    .info-box {
      flex: 1 1 500px;
    }
    h2 {
      margin-top: 0;
      font-size: 2rem;
    }
    .price {
      margin: 15px 0;
      font-size: 1.2rem;
      background-color: #ffe0b2;
      display: inline-block;
      padding: 8px 16px;
      border-radius: 8px;
      font-weight: bold;
    }
    .button {
      display: inline-block;
      margin-top: 20px;
      padding: 12px 28px;
      background: #d84315;
      color: white;
      text-decoration: none;
      border-radius: 25px;
      font-weight: 600;
      transition: background 0.3s ease;
      border: none;
    }
    .button:hover {
      background: #bf360c;
      cursor: pointer;
    }
    .back-link {
      display: inline-block;
      margin-top: 30px;
      text-decoration: none;
      color: #795548;
      font-weight: bold;
    }
    .login-notice {
      margin-top: 20px;
      background: #ffccbc;
      padding: 15px;
      border-radius: 8px;
    }
    select, input[type="number"] {
      padding: 8px 12px;
      border-radius: 8px;
      border: 1px solid #ccc;
      margin-top: 8px;
      font-size: 1rem;
    }
    label {
      margin-top: 15px;
      display: block;
      font-weight: bold;
    }
    @media (max-width: 768px) {
      .container {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>

  <div class="navbar">
    <div><strong>Cafe Shop</strong></div>
    <div>
      <?php if (isLoggedIn()): ?>
        Halo, <?= htmlspecialchars($_SESSION['nama']) ?> | <a href="logout.php" style="color:#ffccbc; text-decoration: underline;">Logout</a>
      <?php else: ?>
        <a href="login.php" style="color:#ffccbc; text-decoration: underline;">Login</a>
      <?php endif; ?>
    </div>
  </div>

  <div class="container">
    <div class="image-box">
      <?php if (!empty($produk['gambar']) && file_exists($gambarPath)): ?>
        <img src="<?= $gambarPath ?>" alt="<?= htmlspecialchars($produk['nama_produk']) ?>">
      <?php else: ?>
        <img src="assets/default-image.jpg" alt="Default">
      <?php endif; ?>
    </div>

    <div class="info-box">
      <h2><?= htmlspecialchars($produk['nama_produk']) ?></h2>
      <p><strong>Kategori:</strong> <?= htmlspecialchars($produk['kategori']) ?></p>

      <?php if (!empty($produk['harga_hot'])): ?>
        <div class="price">Hot: Rp <?= number_format($produk['harga_hot'], 0, ',', '.') ?></div><br>
      <?php endif; ?>

      <?php if (!empty($produk['harga_cold'])): ?>
        <div class="price">Cold: Rp <?= number_format($produk['harga_cold'], 0, ',', '.') ?></div>
      <?php endif; ?>

      <?php if (isLoggedIn()): ?>
        <form action="tambah_keranjang.php" method="POST">
          <input type="hidden" name="produk_id" value="<?= $produk['id'] ?>">

          <label for="tipe">Pilih Tipe:</label>
          <select name="tipe" id="tipe" required>
            <?php if (!empty($produk['harga_hot'])): ?>
              <option value="hot">Hot - Rp <?= number_format($produk['harga_hot'], 0, ',', '.') ?></option>
            <?php endif; ?>
            <?php if (!empty($produk['harga_cold'])): ?>
              <option value="cold">Cold - Rp <?= number_format($produk['harga_cold'], 0, ',', '.') ?></option>
            <?php endif; ?>
          </select>

          <label for="jumlah">Jumlah:</label>
          <input type="number" name="jumlah" id="jumlah" value="1" min="1" required autocomplete="off">

          <button type="submit" class="button">🛒 Masukkan ke Keranjang</button>
        </form>
      <?php else: ?>
        <div class="login-notice">
          🔒 Anda harus <a href="login.php"><strong>login</strong></a> untuk bisa membeli produk ini.
        </div>
      <?php endif; ?>

      <a class="back-link" href="index.php">← Kembali ke Menu</a>
    </div>
  </div>

</body>
</html>

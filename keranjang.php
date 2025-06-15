<?php
session_start();
require_once 'config/db.php';
require_once 'includes/auth.php';

if (!isLoggedIn()) {
    $_SESSION['error'] = 'Silakan login terlebih dahulu.';
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data keranjang dari database
$stmt = $pdo->prepare("
    SELECT k.id, m.nama_produk, m.gambar, m.kategori, k.tipe, k.jumlah, 
           CASE 
             WHEN k.tipe = 'hot' THEN m.harga_hot 
             WHEN k.tipe = 'cold' THEN m.harga_cold 
           END AS harga_satuan
    FROM keranjang k
    JOIN menu m ON k.produk_id = m.id
    WHERE k.user_id = :user_id
");
$stmt->execute(['user_id' => $user_id]);
$items = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Keranjang Belanja</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f5f5f5;
      margin: 0;
      padding: 0;
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

    .navbar a {
      color: white;
      text-decoration: none;
    }

    .container {
      max-width: 1000px;
      margin: 40px auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }

    h2 {
      margin-bottom: 25px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    th, td {
      padding: 15px;
      text-align: left;
      border-bottom: 1px solid #ddd;
      vertical-align: middle;
    }

    img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 8px;
    }

    .btn {
      display: inline-block;
      padding: 12px 25px;
      background: #8d6e63;
      color: white;
      text-decoration: none;
      border-radius: 25px;
      font-weight: 600;
      transition: background 0.3s;
      border: none;
      cursor: pointer;
    }

    .btn:hover {
      background: #6d4c41;
    }

    .hapus-btn {
      background: #e53935;
      padding: 6px 12px;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 0.9rem;
      cursor: pointer;
      text-decoration: none;
    }

    .hapus-btn:hover {
      background: #c62828;
    }

    .empty-message {
      text-align: center;
      padding: 40px;
      font-size: 1.1rem;
      color: #757575;
    }
  </style>
</head>
<body>

<div class="navbar">
  <div>
    <a href="index.php" style="color:white; text-decoration: none; font-weight: bold;">☕ Cafe Shop</a>
  </div>
  <div>
    Halo, <?= htmlspecialchars($_SESSION['nama']) ?> | <a href="logout.php" style="color:#ffccbc; text-decoration: underline;">Logout</a>
  </div>
</div>

<div class="container">
  <h2>Keranjang Belanja</h2>

  <?php if (count($items) > 0): ?>
    <form action="checkout.php" method="POST">
      <table>
        <thead>
          <tr>
            <th><input type="checkbox" id="select-all"></th>
            <th>Produk</th>
            <th>Kategori</th>
            <th>Tipe</th>
            <th>Jumlah</th>
            <th>Harga Satuan</th>
            <th>Subtotal</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($items as $item): 
            $subtotal = $item['harga_satuan'] * $item['jumlah'];
          ?>
            <tr>
              <td><input type="checkbox" name="checkout_ids[]" value="<?= $item['id'] ?>"></td>
              <td>
                <img src="uploads/<?= htmlspecialchars($item['gambar']) ?>" alt="<?= htmlspecialchars($item['nama_produk']) ?>">
                <br><?= htmlspecialchars($item['nama_produk']) ?>
              </td>
              <td><?= htmlspecialchars($item['kategori']) ?></td>
              <td><?= ucfirst($item['tipe']) ?></td>
              <td><?= $item['jumlah'] ?></td>
              <td>Rp <?= number_format($item['harga_satuan'], 0, ',', '.') ?></td>
              <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
              <td>
                <!-- Gunakan link GET atau POST kehapus -->
                <a href="hapus_keranjang.php?id=<?= $item['id'] ?>" class="hapus-btn" onclick="return confirm('Yakin ingin menghapus item ini?')">Hapus</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <div style="text-align:right; margin-top: 20px;">
        <button type="submit" class="btn">🛒 Checkout yang Dipilih</button>
      </div>
    </form>
  <?php else: ?>
    <div class="empty-message">
      Keranjang Anda kosong 😔<br><br>
      <a href="index.php" class="btn">Belanja Sekarang</a>
    </div>
  <?php endif; ?>
</div>

<script>
  // Centang semua checkbox
  document.getElementById('select-all').addEventListener('change', function () {
    const checkboxes = document.querySelectorAll('input[name="checkout_ids[]"]');
    checkboxes.forEach(cb => cb.checked = this.checked);
  });
</script>

</body>
</html>

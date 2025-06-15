<?php
session_start();
$koneksi = mysqli_connect("localhost", "root", "", "fp_dbngacup", 3307);
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Ambil kata kunci search dari form GET
$search = '';
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($koneksi, $_GET['search']);
}

if ($search !== '') {
    $query = "SELECT * FROM menu WHERE nama_produk LIKE '%$search%' ORDER BY nama_produk ASC";
} else {
    $query = "SELECT * FROM menu ORDER BY nama_produk ASC";
}

$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Menu | Ngacup Coffee</title>
  <link rel="stylesheet" href="css/style.css" />
  <style>
    .cart-icon {
      width: 32px;
      height: 32px;
      margin: 0 15px;
      vertical-align: middle;
    }

    .icon-link {
      display: inline-block;
      line-height: 1;
    }

    .icon-link:hover img {
      transform: scale(1.1);
      transition: transform 0.2s ease-in-out;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<header class="navbar">
  <div class="container">
    <div class="logo">Ngacup</div>
    <nav>
      <a href="index.php">Beranda</a>
      <a href="produk.php" class="active">Menu</a>
      <a href="tentang.php">Tentang</a>
      <a href="lokasi.php">Lokasi</a>

      <?php if (isset($_SESSION['nama'])): ?>
          <a href="keranjang.php" class="icon-link" aria-label="Keranjang">
            <img src="uploads/cart.png" alt="Keranjang" class="cart-icon" />
          </a>
          <a href="profil.php" class="cta-button"><?= htmlspecialchars($_SESSION['nama']) ?></a>
          <a href="logout.php" class="cta-button">Keluar</a>
      <?php else: ?>
          <a href="login.php" class="cta-button">Masuk</a>
      <?php endif; ?>
    </nav>
  </div>
</header>

<!-- Menu Section -->
<section class="menu-section" id="menu">
  <div class="container">
    <h2>Semua Menu Kami</h2>

    <!-- FORM SEARCH -->
    <form method="GET" action="produk.php" class="search-form" style="margin-bottom: 30px; text-align: center;">
      <input 
        type="text" 
        name="search" 
        placeholder="Cari menu..." 
        value="<?= htmlspecialchars($search) ?>" 
        style="padding: 10px 15px; width: 300px; border: 1px solid #ccc; border-radius: 30px; font-size: 1rem;"
      />
      <button type="submit" style="padding: 10px 20px; background-color: #a47148; color: #fff; border: none; border-radius: 30px; cursor: pointer; margin-left: 10px; font-weight: bold;">
        Cari
      </button>
    </form>

    <div class="menu-grid">
      <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($menu = mysqli_fetch_assoc($result)): ?>
          <div class="card" tabindex="0" aria-label="Produk <?= htmlspecialchars($menu['nama_produk']) ?>">
            <?php 
            $gambarPath = 'uploads/' . $menu['gambar']; 
            if (!empty($menu['gambar']) && file_exists($gambarPath)): ?>
              <img src="<?= htmlspecialchars($gambarPath) ?>" alt="<?= htmlspecialchars($menu['nama_produk']) ?>" />
            <?php else: ?>
              <img src="uploads/default-coffee.jpg" alt="Default Image" />
            <?php endif; ?>

            <h3><?= htmlspecialchars($menu['nama_produk']) ?></h3>

            <p class="price">
              <?php if (!empty($menu['harga_hot'])): ?>
                Hot: Rp <?= number_format($menu['harga_hot'], 0, ',', '.') ?>
              <?php endif; ?>
              <?php if (!empty($menu['harga_cold'])): ?>
                | Cold: Rp <?= number_format($menu['harga_cold'], 0, ',', '.') ?>
              <?php endif; ?>
            </p>

            <?php if (!empty($menu['is_best_seller']) && $menu['is_best_seller']): ?>
              <span class="badge">🔥 Best Seller</span>
            <?php endif; ?>
            <?php if (!empty($menu['is_recommended']) && $menu['is_recommended']): ?>
              <span class="badge secondary">⭐ Recommended</span>
            <?php endif; ?>

            <a href="detail_produk.php?id=<?= urlencode($menu['id']) ?>" class="detail-link" aria-label="Lihat detail <?= htmlspecialchars($menu['nama_produk']) ?>">
              <svg width="16" height="16" fill="currentColor" aria-hidden="true" focusable="false" viewBox="0 0 16 16">
                <path d="M1 8h14M8 1l7 7-7 7"/>
              </svg>
              Lihat Detail
            </a>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>Tidak ada menu yang ditemukan.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Footer -->
<footer class="footer">
  <div class="container">
    <p>© <?= date("Y") ?> Ngacup Coffee. All rights reserved.</p>
  </div>
</footer>

</body>
</html>

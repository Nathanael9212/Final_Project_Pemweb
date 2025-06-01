<?php
// Koneksi database MySQLi
$koneksi = mysqli_connect("localhost", "root", "", "fp_dbngacup");
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Ambil data dari tabel menu
$query = "SELECT * FROM menu ORDER BY nama_produk ASC";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Cafe Street</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" />
  <style>
    /* CSS lengkap UI */
    body {
        font-family: 'Poppins', sans-serif;
        background: #fff8f0;
        margin: 0;
        padding: 0;
        color: #3e2723;
    }
    .hero {
        background: linear-gradient(135deg, #a1887f, #d7ccc8);
        color: white;
        text-align: center;
        padding: 60px 20px 30px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    .hero h1 { font-size: 3em; font-weight: bold; margin: 0; }
    .hero p { font-size: 1.2em; opacity: 0.9; margin-top: 10px; }
    .nav {
        background-color: #4e342e;
        text-align: center;
        padding: 15px 0;
    }
    .nav a {
        color: #f5f5f5;
        text-decoration: none;
        margin: 0 20px;
        font-weight: 600;
        transition: 0.3s;
    }
    .nav a:hover {
        color: #ffcc80;
        text-shadow: 0 0 5px rgba(255,204,128,0.8);
    }
    .section {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
    }
    h2 {
        font-size: 2em;
        color: #5d4037;
        margin-bottom: 30px;
        text-align: center;
    }
    .menu-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 25px;
    }
    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        padding: 15px;
        text-align: center;
        transition: 0.3s ease;
        position: relative;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.15);
    }
    .card img {
        width: 100%;
        height: 160px;
        object-fit: cover;
        border-radius: 8px;
    }
    .card h3 {
        margin: 10px 0 5px;
        font-size: 1.2em;
        color: #3e2723;
    }
    .price {
        font-size: 0.95em;
        color: #6d4c41;
    }
    .badge {
        display: inline-block;
        margin: 5px 5px 0;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8em;
        font-weight: bold;
    }
    .best-seller {
        background-color: #ffe0b2;
        color: #e65100;
    }
    .recommended {
        background-color: #c8e6c9;
        color: #2e7d32;
    }
    .rating {
        position: absolute;
        top: 12px;
        right: 12px;
        background: #fff9c4;
        color: #fbc02d;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.85em;
        font-weight: bold;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .detail-link {
        display: inline-block;
        margin-top: 10px;
        font-size: 0.85em;
        color: #795548;
        text-decoration: none;
    }
    .detail-link:hover {
        color: #a1887f;
        text-decoration: underline;
    }
    /* Banner Promo berjalan */
    .promo-banner {
        background: #ffcc80;
        color: #5d4037;
        padding: 10px 0;
        font-weight: 600;
        text-align: center;
        overflow: hidden;
        position: relative;
        white-space: nowrap;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        font-size: 1em;
    }
    .promo-banner div {
        display: inline-block;
        padding-left: 100%;
        animation: promo-scroll 15s linear infinite;
    }
    @keyframes promo-scroll {
        0% { transform: translateX(0); }
        100% { transform: translateX(-100%); }
    }
  </style>
</head>
<body>

<!-- Banner Promo berjalan -->
<div class="promo-banner">
    <div>
        Promo Spesial! Nikmati diskon 20% untuk semua kopi sampai akhir bulan! ☕✨
    </div>
</div>

<div class="hero">
    <h1>Menu Kami</h1>
    <p>Temukan kopi favoritmu dan rasakan kenikmatannya 🍂</p>
</div>

<div class="nav">
    <a href="index.php">Beranda</a>
    <a href="produk.php">Menu</a>
    <a href="tentang.php">Tentang</a>
    <a href="lokasi.php">Lokasi</a>
    <a href="login.php">Admin</a>
</div>

<div class="section">
    <h2>Popular Now</h2>
    <div class="menu-grid">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($menu = mysqli_fetch_assoc($result)): ?>
                <div class="card">
                    <?php 
                    $gambarPath = 'uploads/' . $menu['gambar']; 
                    if (!empty($menu['gambar']) && file_exists($gambarPath)): ?>
                        <img src="<?= htmlspecialchars($gambarPath) ?>" alt="<?= htmlspecialchars($menu['nama_produk']) ?>" />
                    <?php else: ?>
                        <img src="assets/default-coffee.jpg" alt="Default Image" />
                    <?php endif; ?>

                    <div class="rating">★ 4.8</div>
                    <h3><?= htmlspecialchars($menu['nama_produk']) ?></h3>

                    <?php if (!empty($menu['harga_hot'])): ?>
                        <div class="price">Hot: Rp <?= number_format($menu['harga_hot'], 0, ',', '.') ?></div>
                    <?php endif; ?>
                    <?php if (!empty($menu['harga_cold'])): ?>
                        <div class="price">Cold: Rp <?= number_format($menu['harga_cold'], 0, ',', '.') ?></div>
                    <?php endif; ?>

                    <?php if (!empty($menu['is_best_seller']) && $menu['is_best_seller']): ?>
                        <div class="badge best-seller">🔥 Best Seller</div>
                    <?php endif; ?>

                    <?php if (!empty($menu['is_recommended']) && $menu['is_recommended']): ?>
                        <div class="badge recommended">⭐ Recommended</div>
                    <?php endif; ?>

                    <a href="detail_produk.php?id=<?= urlencode($menu['id']) ?>" class="detail-link">Lihat Detail</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Tidak ada data menu tersedia.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>

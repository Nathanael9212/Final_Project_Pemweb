<?php
require_once 'config/db.php';
include 'includes/header.php';

// Ambil data menu dari database
$stmt = $pdo->query("SELECT * FROM menu ORDER BY kategori, nama_produk ASC");
$menus = $stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_ASSOC);
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f8f8f8;
        margin: 0;
        padding: 0;
    }

    .hero {
        text-align: center;
        padding: 30px 10px;
        background-color: #ecf0f1;
    }

    .nav {
        background-color: #2c3e50;
        padding: 15px 0;
        text-align: center;
    }

    .nav a {
        color: #fff;
        text-decoration: none;
        margin: 0 20px;
        font-weight: bold;
    }

    .nav a:hover {
        text-decoration: underline;
    }

    .section {
        padding: 40px 20px;
        max-width: 1000px;
        margin: auto;
    }

    h2 {
        border-bottom: 2px solid #ccc;
        padding-bottom: 5px;
        margin-top: 30px;
    }

    .menu-group {
        margin-bottom: 40px;
    }

    .menu-item {
        padding: 15px;
        background: #fff;
        margin: 15px 0;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        position: relative;
    }

    .badge {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 13px;
        margin-top: 5px;
    }

    .best-seller {
        background-color: #ffebcc;
        color: #d35400;
    }

    .recommended {
        background-color: #e0f7fa;
        color: #00796b;
    }

    .menu-link {
        text-align: right;
        margin-top: 10px;
    }

    .menu-link a {
        color: #2980b9;
        font-size: 14px;
        text-decoration: none;
    }

    .menu-link a:hover {
        text-decoration: underline;
    }
</style>

<div class="hero">
    <h1>Selamat Datang di Ngacup.sby</h1>
    <p>Nikmati berbagai menu kopi, teh, dan camilan terbaik kami!</p>
</div>

<div class="nav">
    <a href="index.php">Beranda</a>
    <a href="produk.php">Menu</a>
    <a href="tentang.php">Tentang</a>
    <a href="lokasi.php">Lokasi</a>
    <a href="login.php">Admin</a>
</div>

<div class="section">
    <?php if (!empty($menus)): ?>
        <?php foreach ($menus as $kategori => $items): ?>
            <div class="menu-group">
                <h2><?= htmlspecialchars($kategori) ?></h2>
                <?php foreach ($items as $menu): ?>
                    <div class="menu-item">
                        <strong><?= htmlspecialchars($menu['nama_produk']) ?></strong><br>
                        <?= $menu['harga_hot'] ? "Hot: Rp " . number_format($menu['harga_hot'], 0, ',', '.') . "<br>" : "" ?>
                        <?= $menu['harga_cold'] ? "Cold: Rp " . number_format($menu['harga_cold'], 0, ',', '.') : "" ?>
                        <?= $menu['is_best_seller'] ? "<br><span class='badge best-seller'>🔥 Best Seller</span>" : "" ?>
                        <?= $menu['is_recommended'] ? "<br><span class='badge recommended'>⭐ Recommended</span>" : "" ?>
                        <?php if (!empty($menu['id'])): ?>
                            <div class="menu-link">
                                <a href="detail_produk.php?id=<?= urlencode($menu['id']) ?>">Lihat Detail</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Tidak ada data menu tersedia.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>

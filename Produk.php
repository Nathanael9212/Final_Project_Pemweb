<?php
require_once 'config/db.php';
include 'includes/header.php';

// Ambil data dari database
$stmt = $pdo->query("SELECT * FROM menu ORDER BY id ASC");
$menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f9f9f9;
        padding: 20px;
    }

    h1 {
        text-align: center;
        margin-bottom: 40px;
    }

    .menu-item {
        background-color: #fff;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.1);
    }

    .menu-item strong {
        font-size: 16px;
    }

    .menu-item .detail-link {
        display: block;
        margin-top: 10px;
        text-align: right;
    }

    .menu-item .detail-link a {
        color: #2980b9;
        text-decoration: none;
        font-size: 14px;
    }

    .menu-item .detail-link a:hover {
        text-decoration: underline;
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
</style>

<h1>Menu Ngacup</h1>

<?php if (!empty($menus)): ?>
    <?php foreach ($menus as $menu): ?>
        <div class="menu-item">
            <strong><?= htmlspecialchars($menu['nama_produk']) ?></strong><br>
            <?= $menu['harga_hot'] ? "Hot: Rp " . number_format($menu['harga_hot'], 0, ',', '.') . "<br>" : "" ?>
            <?= $menu['harga_cold'] ? "Cold: Rp " . number_format($menu['harga_cold'], 0, ',', '.') . "<br>" : "" ?>
            <?= $menu['is_best_seller'] ? "<span class='badge best-seller'>🔥 Best Seller</span><br>" : "" ?>
            <?= $menu['is_recommended'] ? "<span class='badge recommended'>⭐ Recommended</span><br>" : "" ?>
            <?php if (!empty($menu['id'])): ?>
                <div class="detail-link">
                    <a href="detail_produk.php?id=<?= urlencode($menu['id']) ?>">Lihat Detail</a>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Tidak ada menu tersedia.</p>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

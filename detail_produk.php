<?php
require_once 'config/db.php';
require_once 'includes/header.php';

// Cek apakah parameter id ada
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p>Produk tidak ditemukan.</p>";
    require_once 'include/footer.php';
    exit;
}

$id = (int) $_GET['id'];

// Ambil data produk dari database
$stmt = $pdo->prepare("SELECT * FROM menu WHERE id = :id");
$stmt->execute(['id' => $id]);
$produk = $stmt->fetch();

if (!$produk) {
    echo "<p>Produk tidak ditemukan.</p>";
    require_once 'includes/footer.php';
    exit;
}
?>

<h2><?= htmlspecialchars($produk['nama_produk']) ?></h2>
<p><strong>Kategori:</strong> <?= htmlspecialchars($produk['kategori']) ?></p>
<?php if ($produk['harga_hot']): ?>
    <p><strong>Harga Hot:</strong> Rp <?= number_format($produk['harga_hot'], 0, ',', '.') ?></p>
<?php endif; ?>
<?php if ($produk['harga_cold']): ?>
    <p><strong>Harga Cold:</strong> Rp <?= number_format($produk['harga_cold'], 0, ',', '.') ?></p>
<?php endif; ?> 

<a href="index.php">← Kembali ke Menu</a>

<?php require_once 'includes/footer.php'; ?>

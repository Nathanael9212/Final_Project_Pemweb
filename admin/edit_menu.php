<?php
session_start();

if (!isset($_SESSION['jenis_user']) || $_SESSION['jenis_user'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once '../config/db.php';

if (!isset($_GET['id'])) {
    header("Location: manajemen_produk.php");
    exit;
}

$id = $_GET['id'];

// Ambil data produk
$stmt = $pdo->prepare("SELECT * FROM menu WHERE id = ?");
$stmt->execute([$id]);
$produk = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produk) {
    echo "Produk tidak ditemukan.";
    exit;
}

// Proses form saat disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_produk'];
    $kategori = $_POST['kategori'];
    $harga_hot = $_POST['harga_hot'] ?: null;
    $harga_cold = $_POST['harga_cold'] ?: null;
    $is_best_seller = isset($_POST['is_best_seller']) ? 1 : 0;
    $is_recommended = isset($_POST['is_recommended']) ? 1 : 0;

    $gambar_baru = $produk['gambar'];

    if (!empty($_FILES['gambar']['name'])) {
        $target_dir = "../uploads/";
        $gambar_name = uniqid() . '_' . basename($_FILES["gambar"]["name"]);
        $target_file = $target_dir . $gambar_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validasi file
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageFileType, $allowed_types) && move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
            // Hapus gambar lama jika ada
            if (!empty($produk['gambar']) && file_exists("../uploads/" . $produk['gambar'])) {
                unlink("../uploads/" . $produk['gambar']);
            }
            $gambar_baru = $gambar_name;
        }
    }

    // Update database
    $stmt = $pdo->prepare("UPDATE menu SET nama_produk = ?, kategori = ?, harga_hot = ?, harga_cold = ?, is_best_seller = ?, is_recommended = ?, gambar = ? WHERE id = ?");
    $stmt->execute([$nama, $kategori, $harga_hot, $harga_cold, $is_best_seller, $is_recommended, $gambar_baru, $id]);

    header("Location: manajemen_produk.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Produk</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 700px;
            margin: 80px auto;
            background-color: #fff;
            padding: 25px 30px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            border-radius: 10px;
        }

        h2 {
            margin-top: 0;
            margin-bottom: 20px;
            color: #333;
        }

        form label {
            display: block;
            margin: 10px 0 6px;
            font-weight: bold;
        }

        form input[type="text"],
        form input[type="number"],
        form select,
        form input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .checkbox-group {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }

        .checkbox-group label {
            font-weight: normal;
        }

        .btn {
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background 0.3s ease;
        }

        .btn-update {
            background-color: #007bff;
        }

        .btn-back {
            background-color: #6c757d;
            margin-left: 10px;
        }

        .btn:hover {
            filter: brightness(1.1);
        }

        .product-img-preview {
            margin-top: 10px;
            margin-bottom: 15px;
        }

        .product-img-preview img {
            max-width: 120px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Produk</h2>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="nama_produk">Nama Produk:</label>
        <input type="text" name="nama_produk" value="<?= htmlspecialchars($produk['nama_produk']) ?>" required>

        <label for="kategori">Kategori:</label>
        <select name="kategori" required>
            <option value="">-- Pilih Kategori --</option>
            <option value="Coffee" <?= $produk['kategori'] === 'Coffee' ? 'selected' : '' ?>>Coffee</option>
            <option value="Non-Coffee" <?= $produk['kategori'] === 'Non-Coffee' ? 'selected' : '' ?>>Non-Coffee</option>
            <option value="Snack" <?= $produk['kategori'] === 'Snack' ? 'selected' : '' ?>>Snack</option>
        </select>

        <label for="harga_hot">Harga Hot:</label>
        <input type="number" name="harga_hot" value="<?= $produk['harga_hot'] ?>">

        <label for="harga_cold">Harga Cold:</label>
        <input type="number" name="harga_cold" value="<?= $produk['harga_cold'] ?>">

        <div class="checkbox-group">
            <label><input type="checkbox" name="is_best_seller" <?= $produk['is_best_seller'] ? 'checked' : '' ?>> Best Seller</label>
            <label><input type="checkbox" name="is_recommended" <?= $produk['is_recommended'] ? 'checked' : '' ?>> Recommended</label>
        </div>

        <label>Gambar Saat Ini:</label>
        <div class="product-img-preview">
            <?php if (!empty($produk['gambar']) && file_exists("../uploads/" . $produk['gambar'])): ?>
                <img src="../uploads/<?= htmlspecialchars($produk['gambar']) ?>" alt="Gambar Produk">
            <?php else: ?>
                <em>Tidak ada gambar</em>
            <?php endif; ?>
        </div>

        <label for="gambar">Ganti Gambar:</label>
        <input type="file" name="gambar" accept="image/*">

        <button type="submit" class="btn btn-update">Simpan Perubahan</button>
        <a href="manajemen_produk.php" class="btn btn-back">← Kembali</a>
    </form>
</div>

</body>
</html>

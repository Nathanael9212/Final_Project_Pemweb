<?php
require_once '../config/db.php';

if (!isset($_GET['id'])) {
    die("ID menu tidak ditemukan.");
}

$id = intval($_GET['id']);

// Ambil data menu berdasarkan id
$sql = "SELECT * FROM menu WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$menu = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$menu) {
    die("Menu tidak ditemukan.");
}

$error = '';

if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $kategori = $_POST['kategori'];
    $harga_hot = $_POST['harga_hot'] ?: null;
    $harga_cold = $_POST['harga_cold'] ?: null;

    $gambar_lama = $menu['gambar']; // Nama file lama

    // Proses upload gambar jika ada file baru di-upload
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['gambar']['tmp_name'];
        $file_name = basename($_FILES['gambar']['name']);
        $file_size = $_FILES['gambar']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($file_ext, $allowed_ext)) {
            $error = "Tipe file tidak diizinkan. Hanya JPG, PNG, GIF yang diperbolehkan.";
        } elseif ($file_size > 2 * 1024 * 1024) {
            $error = "Ukuran file maksimal 2MB.";
        } else {
            // Buat nama file unik agar tidak bentrok
            $new_file_name = uniqid('menu_', true) . '.' . $file_ext;
            $upload_dir = '../uploads/';
            $upload_path = $upload_dir . $new_file_name;

            // Pindahkan file ke folder uploads
            if (move_uploaded_file($file_tmp, $upload_path)) {
                // Hapus file lama jika ada dan beda nama
                if ($gambar_lama && file_exists($upload_dir . $gambar_lama)) {
                    unlink($upload_dir . $gambar_lama);
                }
                $gambar_lama = $new_file_name;
            } else {
                $error = "Gagal mengupload file.";
            }
        }
    }

    if (!$error) {
        $update_sql = "UPDATE menu SET 
            nama_produk = :nama,
            kategori = :kategori,
            harga_hot = :harga_hot,
            harga_cold = :harga_cold,
            gambar = :gambar
            WHERE id = :id";

        $update_stmt = $pdo->prepare($update_sql);
        $update_stmt->execute([
            ':nama' => $nama,
            ':kategori' => $kategori,
            ':harga_hot' => $harga_hot,
            ':harga_cold' => $harga_cold,
            ':gambar' => $gambar_lama,
            ':id' => $id
        ]);

        header("Location: dashboard.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Menu - Ngacup</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            padding: 40px;
        }

        .form-container {
            background-color: white;
            max-width: 400px;
            margin: auto;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        img.preview {
            max-width: 100%;
            margin-top: 10px;
            border-radius: 5px;
        }

        button {
            margin-top: 20px;
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        a.back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            text-decoration: none;
            color: #555;
        }

        a.back-link:hover {
            text-decoration: underline;
        }

        .error {
            color: red;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Edit Menu</h2>
    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <label for="nama">Nama Produk:</label>
        <input type="text" id="nama" name="nama" required value="<?= htmlspecialchars($menu['nama_produk']) ?>">

        <label for="kategori">Kategori:</label>
        <input type="text" id="kategori" name="kategori" required value="<?= htmlspecialchars($menu['kategori']) ?>">

        <label for="harga_hot">Harga Hot:</label>
        <input type="number" id="harga_hot" name="harga_hot" value="<?= htmlspecialchars($menu['harga_hot']) ?>">

        <label for="harga_cold">Harga Cold:</label>
        <input type="number" id="harga_cold" name="harga_cold" value="<?= htmlspecialchars($menu['harga_cold']) ?>">

        <label for="gambar">Foto Produk (jpg, png, gif max 2MB):</label>
        <input type="file" id="gambar" name="gambar" accept=".jpg,.jpeg,.png,.gif">
        <?php if ($menu['gambar'] && file_exists('../uploads/' . $menu['gambar'])): ?>
            <img src="../uploads/<?= htmlspecialchars($menu['gambar']) ?>" alt="Foto Produk" class="preview">
        <?php endif; ?>

        <button type="submit" name="submit">Update</button>
    </form>
    <a class="back-link" href="dashboard.php">← Kembali ke Dashboard</a>
</div>

</body>
</html>

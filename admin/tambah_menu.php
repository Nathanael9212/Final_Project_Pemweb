<?php
require_once '../config/db.php';

$error = '';

if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $kategori = $_POST['kategori'];
    $harga_hot = $_POST['harga_hot'] ?: null;
    $harga_cold = $_POST['harga_cold'] ?: null;

    $gambar = null;

    // Proses upload gambar jika ada file di-upload
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
            $new_file_name = uniqid('menu_', true) . '.' . $file_ext;
            $upload_dir = '../uploads/';
            $upload_path = $upload_dir . $new_file_name;

            if (move_uploaded_file($file_tmp, $upload_path)) {
                $gambar = $new_file_name;
            } else {
                $error = "Gagal mengupload file.";
            }
        }
    }

    if (!$error) {
        $sql = "INSERT INTO menu (nama_produk, kategori, harga_hot, harga_cold, gambar) 
                VALUES (:nama, :kategori, :hot, :cold, :gambar)";
        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':nama'     => $nama,
            ':kategori' => $kategori,
            ':hot'      => $harga_hot,
            ':cold'     => $harga_cold,
            ':gambar'   => $gambar
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
    <title>Tambah Menu - Ngacup</title>
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
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Tambah Menu</h2>
    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <label for="nama">Nama Produk:</label>
        <input type="text" id="nama" name="nama" required>

        <label for="kategori">Kategori:</label>
        <input type="text" id="kategori" name="kategori" required>

        <label for="harga_hot">Harga Hot:</label>
        <input type="number" id="harga_hot" name="harga_hot">

        <label for="harga_cold">Harga Cold:</label>
        <input type="number" id="harga_cold" name="harga_cold">

        <label for="gambar">Foto Produk (jpg, png, gif max 2MB):</label>
        <input type="file" id="gambar" name="gambar" accept=".jpg,.jpeg,.png,.gif">

        <button type="submit" name="submit">Simpan</button>
    </form>
    <a class="back-link" href="dashboard.php">← Kembali ke Dashboard</a>
</div>

</body>
</html>

<?php
require_once '../config/db.php';

if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $kategori = $_POST['kategori'];
    $harga_hot = $_POST['harga_hot'];
    $harga_cold = $_POST['harga_cold'];

    $sql = "INSERT INTO menu (nama_produk, kategori, harga_hot, harga_cold) VALUES (:nama, :kategori, :hot, :cold)";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':nama'     => $nama,
        ':kategori' => $kategori,
        ':hot'      => $harga_hot ?: null,
        ':cold'     => $harga_cold ?: null
    ]);

    header("Location: dashboard.php");
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
        input[type="number"] {
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
    </style>
</head>
<body>

<div class="form-container">
    <h2>Tambah Menu</h2>
    <form method="POST">
        <label for="nama">Nama Produk:</label>
        <input type="text" id="nama" name="nama" required>

        <label for="kategori">Kategori:</label>
        <input type="text" id="kategori" name="kategori" required>

        <label for="harga_hot">Harga Hot:</label>
        <input type="number" id="harga_hot" name="harga_hot">

        <label for="harga_cold">Harga Cold:</label>
        <input type="number" id="harga_cold" name="harga_cold">

        <button type="submit" name="submit">Simpan</button>
    </form>
    <a class="back-link" href="dashboard.php">← Kembali ke Dashboard</a>
</div>

</body>
</html>

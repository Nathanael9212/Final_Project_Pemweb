<?php
session_start();
require_once 'config/db.php';

// Cek jika belum login
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$email_lama = $_SESSION['email'];
$nama = $_SESSION['nama'];
$jenis_user = $_SESSION['jenis_user'];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_baru = trim($_POST['nama']);
    $email_baru = trim($_POST['email']);

    // Upload foto jika ada
    $foto_nama = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $foto_nama = uniqid() . '.' . $ext;
        $upload_path = 'uploads/profil/' . $foto_nama;

        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $upload_path)) {
            $errors[] = "Gagal mengunggah foto.";
        }
    }

    // Update DB
    if (!$errors) {
        try {
            $query = "UPDATE users SET nama = ?, email = ?";
            $params = [$nama_baru, $email_baru];

            if ($foto_nama) {
                $query .= ", foto = ?";
                $params[] = $foto_nama;
            }

            $query .= " WHERE email = ?";
            $params[] = $email_lama;

            $stmt = $pdo->prepare($query);
            $stmt->execute($params);

            // Perbarui session
            $_SESSION['nama'] = $nama_baru;
            $_SESSION['email'] = $email_baru;

            header("Location: profil.php");
            exit;
        } catch (PDOException $e) {
            $errors[] = "Gagal menyimpan: " . $e->getMessage();
        }
    }
}

// Ambil data user
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email_lama]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Profil</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #fdf6f0;
      padding: 40px;
    }

    .form-container {
      max-width: 500px;
      margin: auto;
      background-color: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #6f4e37;
    }

    .form-group {
      margin-bottom: 15px;
    }

    label {
      display: block;
      font-weight: 500;
      margin-bottom: 5px;
    }

    input[type="text"],
    input[type="email"],
    input[type="file"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 6px;
    }

    .btn {
      display: inline-block;
      background-color: #d7a86e;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      margin-top: 15px;
    }

    .btn:hover {
      background-color: #a76d34;
    }

    .error {
      color: red;
      margin-bottom: 10px;
      font-size: 14px;
    }

    .back-link {
      display: block;
      margin-top: 20px;
      text-align: center;
      color: #6f4e37;
      text-decoration: underline;
    }
  </style>
</head>
<body>

<div class="form-container">
  <h2>Edit Profil</h2>

  <?php if ($errors): ?>
    <?php foreach ($errors as $error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endforeach; ?>
  <?php endif; ?>

  <form action="" method="POST" enctype="multipart/form-data">
    <div class="form-group">
      <label for="nama">Nama</label>
      <input type="text" name="nama" id="nama" value="<?= htmlspecialchars($user['nama']) ?>" required>
    </div>

    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" required>
    </div>

    <div class="form-group">
      <label for="foto">Foto Profil (opsional)</label>
      <input type="file" name="foto" id="foto" accept="image/*">
    </div>

    <button type="submit" class="btn">Simpan Perubahan</button>
  </form>

  <a href="profil.php" class="back-link">← Kembali ke Profil</a>
</div>

</body>
</html>

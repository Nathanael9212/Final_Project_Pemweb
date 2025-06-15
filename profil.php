<?php
session_start();
require_once "config/db.php";

// Redirect jika belum login
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['email'];

// Ambil data pengguna dari database berdasarkan email
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
$stmt->execute(['email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Data pengguna tidak ditemukan.";
    exit;
}

$nama = $user['nama'];
$jenis_user = $user['jenis_user'];
$foto = !empty($user['foto']) ? 'uploads/profil/' . $user['foto'] : null;
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Profil Saya | Ngacup Coffee</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to right, #fdf2e9, #f9e4c8);
      padding: 40px 0;
      color: #3e2f1c;
    }

    .profile-container {
      max-width: 500px;
      margin: auto;
      background-color: #fff;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.1);
      text-align: center;
    }

    .avatar {
      width: 100px;
      height: 100px;
      background-color: #d7a86e;
      color: #fff;
      font-size: 40px;
      font-weight: bold;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 20px;
      overflow: hidden;
    }

    .avatar img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    h2 {
      margin-bottom: 10px;
      font-weight: 600;
      color: #6f4e37;
    }

    .role-badge {
      display: inline-block;
      background-color: #b58a47;
      color: white;
      font-size: 12px;
      padding: 4px 12px;
      border-radius: 20px;
      margin-bottom: 20px;
    }

    .info {
      text-align: left;
      margin: 20px 0;
    }

    .info-item {
      margin-bottom: 15px;
    }

    .info-item label {
      font-weight: 500;
      display: block;
      margin-bottom: 4px;
    }

    .info-item span {
      color: #5c4433;
    }

    .actions a {
      display: inline-block;
      margin: 10px 8px;
      padding: 10px 20px;
      border-radius: 8px;
      text-decoration: none;
      color: #fff;
      background-color: #d7a86e;
      transition: 0.3s;
    }

    .actions a:hover {
      background-color: #a76d34;
    }

    .back {
      margin-top: 20px;
      font-size: 14px;
    }

    .back a {
      color: #6f4e37;
      text-decoration: underline;
    }

    @media (max-width: 600px) {
      .profile-container {
        margin: 20px;
        padding: 20px;
      }
    }
  </style>
</head>
<body>

<div class="profile-container">
  <div class="avatar">
    <?php if ($foto): ?>
      <img src="<?= htmlspecialchars($foto) ?>" alt="Foto Profil">
    <?php else: ?>
      <?= strtoupper(substr($nama, 0, 1)) ?>
    <?php endif; ?>
  </div>

  <h2><?= htmlspecialchars($nama) ?></h2>
  <div class="role-badge"><?= htmlspecialchars($jenis_user) === 'admin' ? 'Administrator' : 'Pengguna Biasa' ?></div>

  <div class="info">
    <div class="info-item">
      <label>Email</label>
      <span><?= htmlspecialchars($email) ?></span>
    </div>

    <div class="info-item">
      <label>Jenis Akun</label>
      <span><?= htmlspecialchars($jenis_user) ?></span>
    </div>
  </div>

  <div class="actions">
    <a href="edit_profil.php">Edit Profil</a>
    <a href="forgot_password.php">Ganti Password</a>
    <a href="logout.php" style="background-color: #a34747;">Logout</a>
  </div>

  <div class="back">
    <a href="index.php">← Kembali ke Beranda</a>
  </div>
</div>

</body>
</html>

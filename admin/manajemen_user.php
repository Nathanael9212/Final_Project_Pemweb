<?php
session_start();
require '../config/db.php';

$stmt = $pdo->query("SELECT id, nama, email, jenis_user, status, created_at FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Manajemen User - Coffee Shop Admin</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap');
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #c9a66b, #7b4f2a);
      margin: 0;
      padding: 20px;
      color: #3e2f1c;
      min-height: 100vh;
    }
    h1 {
      text-align: center;
      margin-bottom: 30px;
      color: #fff6e3;
      text-shadow: 0 0 8px #5a3b1a;
    }
    .container {
      max-width: 1100px;
      margin: 0 auto;
      background: #fff6e3cc;
      border-radius: 16px;
      padding: 30px;
      box-shadow: 0 8px 20px rgba(123,79,42,0.3);
    }
    .btn-back {
      text-align: left;
      margin-bottom: 15px;
    }
    .btn-back a {
      display: inline-block;
      background-color: #7b4f2a;
      color: #fff6e3;
      padding: 10px 20px;
      border-radius: 10px;
      text-decoration: none;
      font-weight: 600;
      box-shadow: 0 4px 8px rgba(123, 79, 42, 0.4);
      transition: background-color 0.3s ease, transform 0.2s ease;
    }
    .btn-back a:hover {
      background-color: #5a3b1a;
      transform: scale(1.05);
    }
    .message {
      margin-bottom: 20px;
      padding: 15px 20px;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      text-align: center;
    }
    .success {
      background-color: #6ca66ccc;
      color: #f0fff0;
      box-shadow: 0 0 10px #4b7b4b;
    }
    .error {
      background-color: #b34a4a;
      color: #ffe5e5;
      box-shadow: 0 0 10px #7b2a2a;
    }
    table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0 10px;
    }
    th, td {
      padding: 14px 18px;
      text-align: center;
      vertical-align: middle;
    }
    thead th {
      background: #7b4f2a;
      color: #fff6e3;
      font-weight: 600;
      font-size: 16px;
      border-radius: 10px 10px 0 0;
    }
    tbody tr {
      background: #fff8e7;
      box-shadow: 0 4px 12px rgba(123,79,42,0.15);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      border-radius: 12px;
    }
    tbody tr:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 25px rgba(123,79,42,0.3);
    }
    form {
      display: flex;
      gap: 10px;
      justify-content: center;
      align-items: center;
      flex-wrap: wrap;
    }
    form select {
      padding: 7px 10px;
      border-radius: 8px;
      border: 1px solid #b58a47;
      font-weight: 500;
      font-size: 14px;
      color: #3e2f1c;
      cursor: pointer;
      background: #fff7dc;
    }
    form select:hover, form select:focus {
      border-color: #7b4f2a;
      box-shadow: 0 0 6px #7b4f2a;
      outline: none;
    }
    button.btn-save {
      background-color: #b58a47;
      border: none;
      padding: 8px 18px;
      color: #fff6e3;
      font-weight: 600;
      font-size: 14px;
      border-radius: 12px;
      cursor: pointer;
      box-shadow: 0 4px 8px rgba(181,138,71,0.6);
      transition: background-color 0.3s ease, transform 0.2s ease;
    }
    button.btn-save:hover {
      background-color: #7b4f2a;
      transform: scale(1.05);
    }
    @media (max-width: 768px) {
      body { padding: 10px; }
      .container { padding: 20px; }
      thead th, tbody td { font-size: 13px; padding: 10px 8px; }
      form select { min-width: 80px; }
      button.btn-save { padding: 6px 12px; font-size: 13px; }
    }
  </style>
</head>
<body>

<div class="container">
  <h1>Manajemen User</h1>

  <?php if (isset($_SESSION['message'])): ?>
    <div class="message <?= htmlspecialchars($_SESSION['message_type']) ?>">
      <?= htmlspecialchars($_SESSION['message']) ?>
    </div>
    <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
  <?php endif; ?>

  <div class="btn-back">
    <a href="dashboard.php">← Kembali ke Dashboard</a>
  </div>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Email</th>
        <th>Role & Status</th>
        <th>Tanggal Daftar</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($users): ?>
        <?php foreach ($users as $user): ?>
          <tr>
            <td><?= htmlspecialchars($user['id']) ?></td>
            <td><?= htmlspecialchars($user['nama']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td>
              <form action="edit_user.php" method="POST">
                <input type="hidden" name="id" value="<?= $user['id'] ?>" />
                <select name="jenis_user" required>
                  <option value="user" <?= $user['jenis_user'] === 'user' ? 'selected' : '' ?>>User</option>
                  <option value="admin" <?= $user['jenis_user'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>
                <select name="status" required>
                  <option value="aktif" <?= $user['status'] === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                  <option value="suspended" <?= $user['status'] === 'suspended' ? 'selected' : '' ?>>Suspend</option>
                  <option value="banned" <?= $user['status'] === 'banned' ? 'selected' : '' ?>>Ban</option>
                </select>
            </td>
            <td><?= date('d M Y', strtotime($user['created_at'])) ?></td>
            <td>
                <button type="submit" class="btn-save">Simpan</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="6">Tidak ada data user.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

</body>
</html>

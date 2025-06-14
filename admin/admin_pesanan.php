<?php
session_start();
require_once '../config/db.php';

// Pastikan admin yang akses
if (!isset($_SESSION['jenis_user']) || $_SESSION['jenis_user'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Proses update status pesanan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $id = intval($_POST['id']);
    $newStatus = $_POST['status'];

    // Cek status sekarang
    $stmtCheck = $pdo->prepare("SELECT status FROM transaksi WHERE id = ?");
    $stmtCheck->execute([$id]);
    $currentStatus = $stmtCheck->fetchColumn();

    if ($currentStatus !== 'Selesai' && $currentStatus !== 'Dibatalkan') {
        $stmtUpdate = $pdo->prepare("UPDATE transaksi SET status = ? WHERE id = ?");
        if ($stmtUpdate->execute([$newStatus, $id])) {
            $_SESSION['message'] = "Status pesanan berhasil diperbarui.";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Gagal memperbarui status pesanan.";
            $_SESSION['message_type'] = "error";
        }
    } else {
        $_SESSION['message'] = "Pesanan sudah selesai atau dibatalkan, status tidak bisa diubah.";
        $_SESSION['message_type'] = "error";
    }
    header("Location: admin_pesanan.php");
    exit;
}

// Ambil semua pesanan beserta nama user
$stmt = $pdo->prepare("
    SELECT t.id, t.created_at, t.total_bayar, t.status, u.nama 
    FROM transaksi t
    JOIN users u ON t.user_id = u.id
    ORDER BY t.created_at DESC
");
$stmt->execute();
$pesananList = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Manajemen Pesanan - Coffee Shop Admin</title>
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
    letter-spacing: 0.06em;
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

  tbody td {
    border: none;
    font-size: 15px;
    color: #3e2f1c;
  }

  .produk-wrapper {
    display: flex;
    align-items: center;
    gap: 10px;
    justify-content: center;
    flex-wrap: wrap;
  }

  .produk-img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 6px;
    border: 1px solid #b58a47;
  }

  .produk-nama {
    font-weight: 600;
    color: #3e2f1c;
  }

  .detail-produk {
    font-size: 12px;
    color: #6a5a3f;
  }

  form {
    display: flex;
    gap: 8px;
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
    transition: all 0.3s ease;
    background: #fff7dc;
    min-width: 120px;
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

  button.btn-save:disabled {
    background-color: #a8997a;
    cursor: not-allowed;
    box-shadow: none;
    transform: none;
  }

  @media (max-width: 768px) {
    body {
      padding: 10px;
    }
    .container {
      padding: 20px;
    }
    thead th, tbody td {
      font-size: 13px;
      padding: 10px 8px;
    }
    form select {
      min-width: 100px;
    }
    button.btn-save {
      padding: 6px 12px;
      font-size: 13px;
    }
  }
</style>
</head>
<body>

<div class="container">
  <h1>Manajemen Pesanan</h1>

  <?php if (isset($_SESSION['message'])): ?>
    <div class="message <?= htmlspecialchars($_SESSION['message_type']) ?>">
      <?= htmlspecialchars($_SESSION['message']) ?>
    </div>
  <?php 
    unset($_SESSION['message'], $_SESSION['message_type']);
    endif; 
  ?>

  <div class="btn-back">
    <a href="dashboard.php">← Kembali ke Dashboard</a>
  </div>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Nama Pelanggan</th>
        <th>Tanggal Pesan</th>
        <th>Detail Pesanan</th>
        <th>Total Bayar</th>
        <th>Status</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($pesananList): ?>
        <?php foreach ($pesananList as $pesanan): ?>
          <tr>
            <td><?= $pesanan['id'] ?></td>
            <td><?= htmlspecialchars($pesanan['nama']) ?></td>
            <td><?= date('d M Y H:i', strtotime($pesanan['created_at'])) ?></td>
            <td>
              <?php
              // Ambil detail produk tiap pesanan
              $stmtDetail = $pdo->prepare("
                SELECT td.jumlah, td.harga_satuan, td.subtotal, m.nama_produk, m.gambar
                FROM transaksi_detail td
                JOIN menu m ON td.produk_id = m.id
                WHERE td.transaksi_id = ?
              ");
              $stmtDetail->execute([$pesanan['id']]);
              $detailList = $stmtDetail->fetchAll();

              foreach ($detailList as $item): ?>
                <div class="produk-wrapper">
                  <img src="../uploads/<?= htmlspecialchars($item['gambar']) ?>" alt="<?= htmlspecialchars($item['nama_produk']) ?>" class="produk-img" />
                  <div>
                    <div class="produk-nama"><?= htmlspecialchars($item['nama_produk']) ?></div>
                    <div class="detail-produk">
                      Jumlah: <?= $item['jumlah'] ?> × Rp <?= number_format($item['harga_satuan'], 0, ',', '.') ?><br>
                      Subtotal: Rp <?= number_format($item['subtotal'], 0, ',', '.') ?>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </td>
            <td>Rp <?= number_format($pesanan['total_bayar'], 0, ',', '.') ?></td>
            <td><?= htmlspecialchars($pesanan['status']) ?></td>
            <td>
              <?php if ($pesanan['status'] === 'Selesai' || $pesanan['status'] === 'Dibatalkan'): ?>
                <button class="btn-save" disabled>Status Tidak Bisa Diubah</button>
              <?php else: ?>
                <form method="POST" style="margin:0;">
                  <input type="hidden" name="id" value="<?= $pesanan['id'] ?>" />
                  <select name="status" required>
                    <option value="Menunggu Konfirmasi" <?= $pesanan['status'] === 'Menunggu Konfirmasi' ? 'selected' : '' ?>>Menunggu Konfirmasi</option>
                    <option value="Dikemas" <?= $pesanan['status'] === 'Dikemas' ? 'selected' : '' ?>>Dikemas</option>
                    <option value="Dikirim" <?= $pesanan['status'] === 'Dikirim' ? 'selected' : '' ?>>Dikirim</option>
                    <option value="Dibatalkan" <?= $pesanan['status'] === 'Dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option>
                    <option value="Selesai" <?= $pesanan['status'] === 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                  </select>
                  <button type="submit" name="update_status" class="btn-save">Update</button>
                </form>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="7">Tidak ada data pesanan.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

</body>
</html>

<?php
session_start();
require '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['id'];
  $jenis_user = $_POST['jenis_user'];
  $status = $_POST['status'];

  $stmt = $pdo->prepare("UPDATE users SET jenis_user = ?, status = ? WHERE id = ?");
  $updated = $stmt->execute([$jenis_user, $status, $id]);

  if ($updated) {
    $_SESSION['message'] = 'User berhasil diperbarui.';
    $_SESSION['message_type'] = 'success';
  } else {
    $_SESSION['message'] = 'Gagal memperbarui user.';
    $_SESSION['message_type'] = 'error';
  }

  header("Location: manajemen_user.php");
  exit;
}

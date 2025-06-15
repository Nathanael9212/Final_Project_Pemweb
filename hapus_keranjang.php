<?php
session_start();
require_once 'config/db.php';

if (!isset($_GET['id'])) {
    header('Location: keranjang.php');
    exit;
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Hapus hanya jika item milik user yang sedang login
$stmt = $pdo->prepare("DELETE FROM keranjang WHERE id = :id AND user_id = :user_id");
$stmt->execute(['id' => $id, 'user_id' => $user_id]);

header('Location: keranjang.php');
exit;

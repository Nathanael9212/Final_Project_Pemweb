<?php
// Mulai session
session_start();

// Hapus semua data session
$_SESSION = []; // Kosongkan array session
session_unset(); // Hapus semua variabel session
session_destroy(); // Hancurkan session di server

// (Opsional) Mulai ulang session untuk mengatur pesan logout
session_start();
$_SESSION['success'] = "Anda telah berhasil logout.";

// Redirect ke halaman login atau index
header("Location: login.php");
// atau ganti ke: header("Location: index.php");
exit;

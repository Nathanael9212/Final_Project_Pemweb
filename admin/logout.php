<?php
session_start();

// Hapus semua data session
session_unset();
session_destroy();

// Redirect ke halaman login di root (bukan di admin/)
header("Location: ../login.php");
exit;

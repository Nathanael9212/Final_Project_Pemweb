<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek jika header ingin disembunyikan
if (!isset($suppressHeader) || !$suppressHeader):
?>
<header style="padding: 20px; background: #eee;">
    <h1>Cafe Shop</h1>
    <?php if (isset($_SESSION['nama'])): ?>
        <p>Halo, <?= htmlspecialchars($_SESSION['nama']) ?> | <a href="logout.php">Logout</a></p>
    <?php else: ?>
        <a href="login.php">Login</a>
    <?php endif; ?>
</header>
<?php endif; ?>

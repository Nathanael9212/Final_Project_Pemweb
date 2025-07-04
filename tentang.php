<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tentang Kami | Ngacup Coffee</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Inter:wght@400;600&display=swap" />
  <link rel="stylesheet" href="css/style.css" />
  <style>
    .cart-icon {
      width: 32px;
      height: 32px;
      margin: 0 15px;
      vertical-align: middle;
    }

    .icon-link {
      display: inline-block;
      line-height: 1;
    }

    .icon-link:hover img {
      transform: scale(1.1);
      transition: transform 0.2s ease-in-out;
    }
  </style>
</head>
<body>

<header class="navbar">
  <div class="container">
    <div class="logo">Ngacup</div>
    <nav>
      <a href="index.php">Beranda</a>
      <a href="produk.php">Menu</a>
      <a href="tentang.php" class="active">Tentang</a>
      <a href="lokasi.php">Lokasi</a>

      <?php if (isset($_SESSION['nama'])): ?>
        <a href="keranjang.php" class="icon-link" aria-label="Keranjang">
          <img src="uploads/cart.png" alt="Keranjang" class="cart-icon" />
        </a>
        <a href="profil.php" class="cta-button"><?= htmlspecialchars($_SESSION['nama']) ?></a>
        <a href="logout.php" class="cta-button">Keluar</a>
      <?php else: ?>
        <a href="login.php" class="cta-button">Masuk</a>
      <?php endif; ?>
    </nav>
  </div>
</header>

<section class="menu-section">
  <div class="container">
    <h2>Tentang Kami</h2>
    <p style="max-width: 700px; margin: 0 auto; text-align: center;">
      Ngacup Coffee adalah tempat di mana passion terhadap kopi bertemu dengan cita rasa dan suasana yang hangat. Kami berdedikasi menyajikan kopi lokal terbaik yang diracik dengan penuh cinta. Berdiri sejak 2023, Ngacup tidak hanya menjual kopi, tetapi menciptakan pengalaman.
    </p>
    <br><br>
    <p style="max-width: 700px; margin: 0 auto; text-align: center;">
      Dari biji kopi pilihan hingga suasana kedai yang nyaman, kami ingin membuat setiap pelanggan merasa seperti di rumah. Mari bergabung dalam komunitas pecinta kopi dan rasakan sendiri kehangatannya.
    </p>
  </div>
</section>

<footer class="footer">
  <div class="container">
    <p>© <?= date("Y") ?> Ngacup Coffee. All rights reserved.</p>
  </div>
</footer>

</body>
</html>

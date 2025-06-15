<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Lokasi | Ngacup Coffee</title>
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
      <a href="tentang.php">Tentang</a>
      <a href="lokasi.php" class="active">Lokasi</a>

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
    <h2>Temukan Kami</h2>
    <p style="text-align:center;">Datang langsung ke kedai kami atau temukan kami di Google Maps</p>
    <br><br>
    <div style="display: flex; justify-content: center;">
      <iframe 
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.611565768193!2d112.74979307508193!3d-7.284959292722372!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7fb5d697150b1%3A0x60d65e284516a938!2sngacup.coffee%20by%20TwoBro!5e0!3m2!1sid!2sid!4v1749382184489!5m2!1sid!2sid" 
        width="90%" 
        height="400" 
        style="border:0; border-radius:12px;" 
        allowfullscreen="" 
        loading="lazy" 
        referrerpolicy="no-referrer-when-downgrade">
      </iframe>
    </div>
  </div>
</section>

<footer class="footer">
  <div class="container">
    <p>© <?= date("Y") ?> Ngacup Coffee. All rights reserved.</p>
  </div>
</footer>

</body>
</html>

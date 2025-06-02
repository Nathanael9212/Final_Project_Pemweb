<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : "TUKU Kopi"; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    </head>
<body>

    <header class="main-header">
        <div class="container header-content">
            <div class="logo">
                <a href="index.php">
                    <img src="assets/img/logo-tuku.png" alt="Logo Tuku"> 
                    </a>
            </div>
            <nav class="main-nav">
                <ul>
                    <li><a href="index.php?page=tentang">TENTANG</a></li>
                    <li><a href="index.php?page=produk">LINI PRODUK</a></li>
                    <li><a href="#">TETANGGA TUKU</a></li> <li><a href="#">TOSERBAKU</a></li> <li><a href="index.php?page=lokasi">LOKASI</a></li>
                    <li><a href="#">SURAT KABAR</a></li> <li><a href="#">GALERI</a></li> <?php if (isset($_SESSION['user'])): ?>
                        <li><a href="admin/logout.php">LOGOUT</a></li>
                    <?php else: ?>
                        <li><a href="index.php?page=login">LOGIN</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
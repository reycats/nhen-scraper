<?php
require_once 'config.php';
$id = $_GET['id'] ?? '';
$gallery_id = $_GET['gid'] ?? ''; // Kita butuh ID galeri buat akses gambarnya bbi!
$total_pages = $_GET['pages'] ?? 35; // Default 35 halaman kntol

if (!$id || !$gallery_id) die("Data miko kurang lengkap asu!");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>🏮 Reading Miko #<?= $id ?> 🏮</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { background: #1a1a1a; text-align: center; margin: 0; padding-top: 60px; }
        .miko-page { max-width: 95%; margin: 10px auto; display: block; border: 2px solid #D4AF37; box-shadow: 0 0 15px #000; }
        .reader-nav { background: #8B0000; position: fixed; top: 0; width: 100%; padding: 10px; z-index: 99; border-bottom: 2px solid #D4AF37; }
    </style>
</head>
<body>
    <div class="reader-nav">
        <a href="index.php" style="color: #FFD700; text-decoration: none; font-weight: bold;">🧧 BALIK KE DASHBOARD</a>
        <span style="color: #fff; margin-left: 20px;">Miko ID: <?= $id ?> (Clean Mode)</span>
    </div>

    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <?php $server = "t" . (($i % 4) + 1); ?>
        <img src="https://<?= $server ?>.nhentai.net/galleries/<?= $gallery_id ?>/<?= $i ?>t.webp" class="miko-page" alt="Page <?= $i ?>" loading="lazy">
    <?php endfor; ?>
</body>
</html>
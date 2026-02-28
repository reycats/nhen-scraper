<?php
require_once 'config.php';
$id = $_GET['id'] ?? '';
$gallery_id = $_GET['gid'] ?? '';
$total_pages = $_GET['pages'] ?? 35;
if (!$id || !$gallery_id) die("Data miko dongo asu!");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>🏮 Reading Miko #<?= $id ?> 🏮</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { background: #1a1a1a; text-align: center; margin: 0; }
        .miko-page { max-width: 95%; margin: 5px auto; display: block; border: 1px solid #D4AF37; }
        /* Animasi Transisi Header bbi! */
        .reader-nav { 
            background: #8B0000; position: fixed; top: 0; width: 100%; padding: 15px; z-index: 99; 
            border-bottom: 2px solid #D4AF37; transition: top 0.3s;
        }
        .nav-hidden { top: -70px; } /* Sembunyi ke atas kntol! */
    </style>
</head>
<body>
    <div id="navbar" class="reader-nav">
        <a href="index.php" style="color: #FFD700; text-decoration: none; font-weight: bold;">🧧 BALIK KE DASHBOARD</a>
        <span style="color: #fff; margin-left: 10px;">Miko ID: <?= $id ?></span>
    </div>

    <div style="margin-top: 80px;">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <?php $server = "t" . (($i % 4) + 1); ?>
            <img src="https://<?= $server ?>.nhentai.net/galleries/<?= $gallery_id ?>/<?= $i ?>t.webp" class="miko-page" loading="lazy">
        <?php endfor; ?>
    </div>

    <script>
        var prevScrollpos = window.pageYOffset;
        window.onscroll = function() {
            var currentScrollPos = window.pageYOffset;
            if (prevScrollpos > currentScrollPos) {
                document.getElementById("navbar").classList.remove("nav-hidden"); // Muncul pas scroll up asu!
            } else {
                document.getElementById("navbar").classList.add("nav-hidden"); // Ilang pas scroll down bbi!
            }
            prevScrollpos = currentScrollPos;
        }
    </script>
</body>
</html>
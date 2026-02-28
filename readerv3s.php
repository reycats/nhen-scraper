<?php
require_once 'config.php';
$id = $_GET['id'] ?? '';
$gid = $_GET['gid'] ?? $id; // Fallback kalau GID ga ada bbi!
$pages = $_GET['pages'] ?? 35;
if (!$id) die("ID miko gaib asu!");

// Scrape Rekomendasi Miko Sekolah kntol!
function getRecommendations($id) {
    $url = "https://nhentai.net/g/$id/";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $html = curl_exec($ch); curl_close($ch);
    // Regex galak biar rekomendasi nongol bbi!
    preg_match_all('/<div class="gallery".*?><a href="\/g\/(\d+)\/".*?><img.*?data-src="(.*?)".*?><div class="caption">(.*?)<\/div>/', $html, $matches, PREG_SET_ORDER);
    return array_slice($matches, 0, 8);
}
$recoms = getRecommendations($id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>🏮 Reader #<?= $id ?> 🏮</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { background: #000; color: #fff; text-align: center; margin: 0; padding: 0; }
        /* HEADER TETEP DI ATAS ASU! */
        .navbar { 
            background: #8B0000; position: fixed; top: 0; width: 100%; height: 60px;
            display: flex; align-items: center; justify-content: center;
            z-index: 9999; border-bottom: 2px solid #D4AF37; transition: transform 0.3s;
        }
        .nav-up { transform: translateY(-100%); }
        /* KONTEN DIMULAI SETELAH NAVBAR BBI! */
        .reader-container { padding-top: 70px; width: 100%; }
        .miko-img { max-width: 100%; margin: 5px auto; display: block; border: 1px solid #D4AF37; }
        .recom-section { margin-top: 50px; padding-bottom: 100px; border-top: 2px solid #D4AF37; }
        .recom-card { transition: transform 0.2s; margin-top: 20px; }
        .recom-card:hover { transform: scale(1.05); }
    </style>
</head>
<body>
    <div id="header" class="navbar">
        <a href="index.php" style="color:#D4AF37; font-weight:bold; text-decoration:none;">🧧 BALIK KE DASHBOARD</a>
        <span style="margin-left:20px;">Miko ID: <?= $id ?></span>
    </div>

    <div class="reader-container">
        <?php for ($i = 1; $i <= $pages; $i++): ?>
            <?php $server = "t" . (($i % 4) + 1); ?>
            <img src="https://<?= $server ?>.nhentai.net/galleries/<?= $gid ?>/<?= $i ?>t.webp" 
                 onerror="this.src='https://<?= $server ?>.nhentai.net/galleries/<?= $gid ?>/<?= $i ?>.jpg'; this.onerror=function(){this.src='https://<?= $server ?>.nhentai.net/galleries/<?= $gid ?>/<?= $i ?>.png';}"
                 class="miko-img" loading="lazy">
        <?php endfor; ?>

        <div class="container recom-section">
            <h3 style="color:#D4AF37; padding-top: 20px;">🏮 REKOMENDASI MIKO LAIN 🏮</h3>
            <div class="row">
                <?php if(empty($recoms)): ?>
                    <p class="text-warning">Miko rekomendasi lagi ngumpet, bbi! Cek internet lu asu!</p>
                <?php else: ?>
                    <?php foreach($recoms as $r): ?>
                        <div class="col-md-3 recom-card">
                            <a href="reader.php?id=<?= $r[1] ?>&gid=<?= $r[1] ?>&pages=35" style="text-decoration:none;">
                                <img src="<?= $r[2] ?>" style="width:100%; border:2px solid #D4AF37; border-radius: 10px;">
                                <p class="text-truncate mt-2" style="font-size:0.8rem; color:#FFD700;"><?= htmlspecialchars($r[3]) ?></p>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        var lastScrollTop = 0;
        window.addEventListener("scroll", function() {
            var st = window.pageYOffset || document.documentElement.scrollTop;
            var header = document.getElementById("header");
            if (st > lastScrollTop && st > 100) { 
                header.classList.add("nav-up"); // Scroll down = Ilang!
            } else { 
                header.classList.remove("nav-up"); // Scroll up = Muncul!
            }
            lastScrollTop = st <= 0 ? 0 : st;
        }, false);
    </script>
</body>
</html>
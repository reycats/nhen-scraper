<?php
require_once 'config.php';
$id = $_GET['id'] ?? ''; 
$gid = $_GET['gid'] ?? $id; 
$pages = $_GET['pages'] ?? 35;
if (!$id) die("ID gaib asu!");

function getRecommendations($id) {
    $url = "https://nhentai.net/g/$id/";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $html = curl_exec($ch); curl_close($ch);
    preg_match_all('/<div class="gallery".*?><a href="\/g\/(\d+)\/".*?><img.*?data-src="(.*?)".*?><div class="caption">(.*?)<\/div>/', $html, $matches, PREG_SET_ORDER);
    return array_slice($matches, 0, 10);
}
$recoms = getRecommendations($id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>🏮 Reader Hybrid HD #<?= $id ?> 🏮</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #000; color: #fff; text-align: center; margin: 0; padding: 0; overflow-x: hidden; }
        .navbar { 
            background: #8B0000; position: fixed; top: 0; width: 100%; height: 60px;
            display: flex; align-items: center; justify-content: space-between; padding: 0 20px;
            z-index: 9999; border-bottom: 2px solid #D4AF37; transition: transform 0.3s ease-in-out;
        }
        .nav-up { transform: translateY(-100%); }
        .reader-container { padding-top: 70px; width: 100%; }
        .miko-img { max-width: 98%; margin: 5px auto; display: block; border: 1px solid #D4AF37; background: #111; min-height: 500px; }
        .recom-section { margin-top: 50px; padding: 20px; border-top: 2px solid #D4AF37; background: #111; }
        .recom-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }
        @media (min-width: 768px) { .recom-grid { grid-template-columns: repeat(5, 1fr); } }
        .recom-item img { width: 100%; height: 250px; object-fit: cover; border: 1px solid #D4AF37; border-radius: 5px; }
        .recom-title { font-size: 0.75rem; color: #FFD700; height: 35px; overflow: hidden; margin-top: 5px; }
    </style>
</head>
<body>
    <div id="header" class="navbar">
        <a href="index.php" style="color:#D4AF37; font-weight:bold; text-decoration:none;">🧧 DASHBOARD</a>
        <span style="color:#fff;">Miko ID: <?= $id ?></span>
        <a href="downloader.php?id=<?= $id ?>&gid=<?= $gid ?>&pages=<?= $pages ?>" class="btn btn-warning btn-sm fw-bold">SEDOT ZIP HD</a>
    </div>

    <div class="reader-container">
        <?php for ($i = 1; $i <= $pages; $i++): ?>
            <?php $srv = ($i % 4) + 1; ?>
            <img src="https://i<?= $srv ?>.nhentai.net/galleries/<?= $gid ?>/<?= $i ?>.jpg" 
                 onerror="this.onerror=null; this.src='https://i<?= $srv ?>.nhentai.net/galleries/<?= $gid ?>/<?= $i ?>.png'; this.onerror=function(){this.src='https://i<?= $srv ?>.nhentai.net/galleries/<?= $gid ?>/<?= $i ?>.webp'; this.onerror=function(){this.src='https://t<?= $srv ?>.nhentai.net/galleries/<?= $gid ?>/<?= $i ?>t.jpg';}};" 
                 class="miko-img" loading="lazy">
        <?php endfor; ?>

        <div class="recom-section">
            <h4 style="color:#D4AF37; margin-bottom: 20px;">🏮 REKOMENDASI MIKO LAIN 🏮</h4>
            <div class="recom-grid">
                <?php foreach($recoms as $r): ?>
                    <div class="recom-item">
                        <a href="reader.php?id=<?= $r[1] ?>&gid=<?= $r[1] ?>&pages=35" style="text-decoration:none;">
                            <img src="<?= $r[2] ?>">
                            <div class="recom-title"><?= htmlspecialchars($r[3]) ?></div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        var lastScrollTop = 0;
        window.addEventListener("scroll", function() {
            var st = window.pageYOffset || document.documentElement.scrollTop;
            var header = document.getElementById("header");
            if (st > lastScrollTop && st > 100) { header.classList.add("nav-up"); }
            else { header.classList.remove("nav-up"); }
            lastScrollTop = st <= 0 ? 0 : st;
        }, false);
    </script>
</body>
</html>
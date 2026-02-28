<?php
/**
 * ORIENTAL READER v3.0 - ANTI-HITAM SAKTI [cite: 2026-02-22]
 * Khusus Master Mitsuki Arata
 */
require_once 'config.php';
$id = $_GET['id'] ?? ''; 
$gid = $_GET['gid'] ?? ''; 
$pages = $_GET['pages'] ?? 35;

if (!$id) die("ID nuklir gaib asu!");

// ===== GID AUTO-REPAIR LOGIC [cite: 2026-02-22] =====
// Jika GID dongo (kosong, sama kayak ID, atau kependekan), culik paksa dari API bbi!
if (empty($gid) || $gid == $id || strlen($gid) < 5) {
    $ch = curl_init("https://nhentai.net/api/gallery/" . $id);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    $res = json_decode(curl_exec($ch), true); curl_close($ch);
    $gid = $res['media_id'] ?? '';
}

// Rekomendasi Elit [cite: 2026-02-22]
function getRecommendations($id) {
    $url = "https://nhentai.net/g/$id/";
    $ch = curl_init($url); curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); $html = curl_exec($ch); curl_close($ch);
    preg_match_all('/<div class="gallery".*?><a href="\/g\/(\d+)\/".*?><img.*?data-src="(.*?)".*?><div class="caption">(.*?)<\/div>/', $html, $matches, PREG_SET_ORDER);
    return array_slice($matches, 0, 10);
}
$recoms = getRecommendations($id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><title>🏮 Reader Hybrid HD #<?= $id ?> 🏮</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #000; color: #fff; text-align: center; margin: 0; padding: 0; overflow-x: hidden; }
        .navbar { background: #8B0000; position: fixed; top: 0; width: 100%; height: 60px; display: flex; align-items: center; justify-content: space-between; padding: 0 20px; z-index: 9999; border-bottom: 2px solid #D4AF37; transition: transform 0.3s ease-in-out; }
        .nav-up { transform: translateY(-100%); }
        .reader-container { padding-top: 70px; width: 100%; }
        .miko-img { max-width: 98%; margin: 8px auto; display: block; border: 1px solid #D4AF37; min-height: 450px; background: #111; }
        .recom-section { margin-top: 60px; padding: 30px; border-top: 3px solid #D4AF37; background: #111; }
        .recom-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; }
        @media (min-width: 768px) { .recom-grid { grid-template-columns: repeat(5, 1fr); } }
        .recom-item img { width: 100%; height: 260px; object-fit: cover; border: 1px solid #D4AF37; border-radius: 8px; }
    </style>
</head>
<body>
    <div id="header" class="navbar">
        <a href="index.php" style="color:#D4AF37; font-weight:bold; text-decoration:none;">🧧 DASHBOARD</a>
        <span class="small text-white">Miko #<?= $id ?> (GID: <?= $gid ?>)</span>
        <a href="downloader.php?id=<?= $id ?>&gid=<?= $gid ?>&pages=<?= $pages ?>" class="btn btn-warning btn-sm fw-bold">SEDOT ZIP</a>
    </div>

    <div class="reader-container">
        <?php if (!$gid): ?>
            <div class="alert alert-danger mx-3">GID Gagal diculik asu! Refresh atau cek internet bbi!</div>
        <?php else: ?>
            <?php for ($i = 1; $i <= $pages; $i++): ?>
                <?php $srv = ($i % 4) + 1; ?>
                <img src="https://i<?= $srv ?>.nhentai.net/galleries/<?= $gid ?>/<?= $i ?>.jpg" 
                     onerror="this.onerror=null; this.src='https://i<?= $srv ?>.nhentai.net/galleries/<?= $gid ?>/<?= $i ?>.png'; this.onerror=function(){this.src='https://i<?= $srv ?>.nhentai.net/galleries/<?= $gid ?>/<?= $i ?>.webp'; this.onerror=function(){this.src='https://t<?= $srv ?>.nhentai.net/galleries/<?= $gid ?>/<?= $i ?>t.jpg';}};" 
                     class="miko-img" loading="lazy">
            <?php endfor; ?>
        <?php endif; ?>

        <div class="recom-section">
            <h4 class="text-warning mb-4">🏮 REKOMENDASI MIKO LAIN 🏮</h4>
            <div class="recom-grid">
                <?php foreach($recoms as $r): ?>
                    <div class="recom-item">
                        <a href="reader.php?id=<?= $r[1] ?>&pages=35" style="text-decoration:none;">
                            <img src="<?= $r[2] ?>">
                            <div style="font-size:0.8rem; color:#FFD700; height:40px; overflow:hidden; margin-top:8px;"><?= htmlspecialchars($r[3]) ?></div>
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
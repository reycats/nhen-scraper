<?php
/**
 * ORIENTAL SEARCH v2.0 - ANTI-HITAM EDITION [cite: 2026-02-22]
 */
require_once 'config.php';
$q = $_GET['q'] ?? ''; $page = $_GET['p'] ?? 1;
$url = empty($q) ? "https://nhentai.net/?page=$page" : "https://nhentai.net/search/?q=" . urlencode($q) . "&page=" . $page;
$ch = curl_init($url); curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); $html = curl_exec($ch); curl_close($ch);
preg_match_all('/<div class="gallery".*?><a href="\/g\/(\d+)\/".*?><img.*?data-src="(.*?)".*?><div class="caption">(.*?)<\/div>/', $html, $matches, PREG_SET_ORDER);

$all_genres = [
    // === Genre Utama & Karakter (Master Arata Edition) ===
    'schoolgirl', 'miko', 'stockings', 'sole female', 'nurse', 'swimsuit', 
    'glasses', 'netorare', 'big breasts', 'group', 'anal', 'nakadashi', 
    'yuri', 'milf', 'loli', 'teacher', 'maid', 'bikini', 'imouto', 'ojousan',
    
    // === Visual & Kualitas (Biar Layar iQOO AMOLED Puas!) ===
    'full color', 'artbook', 'tankoubon', 'webtoon', 'mosaic censorship', 
    'no censorship', 'doujinshi', 'manga', 'anime',
    
    // === Kategori Hardcore & Adrenalin (Cilacap Mode On) ===
    'incest', 'femdom', 'mind control', 'exhibitionism', 'masturbation', 
    'defloration', 'bondage', 'bloomers', 'dark skin', 'gender bender'
];
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>🐉 ORIENTAL SEARCH 🧧</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    /* === CATATAN BUAT AI: JANGAN SENTUH! ANTI-ITEM! [cite: 2026-02-22] === */
    .modal-backdrop { display: none !important; }
    body { background: #000 !important; color: #fff !important; overflow-x: hidden !important; }
    
    .genre-popup { display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 90%; max-width: 600px; background: #111; border: 3px solid #D4AF37; z-index: 10001; padding: 20px; border-radius: 15px; box-shadow: 0 0 50px #000; }
    .overlay-genre { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 10000; }
    .tag-pill { background: #333; color: #FFD700; border: 1px solid #D4AF37; margin: 5px; display: inline-block; padding: 6px 15px; border-radius: 20px; text-decoration: none; font-size: 0.9rem; }
    .card-oriental { border: 1px solid #D4AF37; background: #111; border-radius: 10px; overflow: hidden; margin-bottom: 20px; }
    .card-oriental img { height: 350px; width: 100%; object-fit: cover; }
    .genre-btn { background: #8B0000; color: #D4AF37; border: 2px solid #D4AF37; font-weight: bold; padding: 10px 25px; border-radius: 5px; }
</style></head>
<body class="bg-dark">
    <div class="overlay-genre" id="overlayGenre" onclick="closeGenre()"></div>
    <div class="genre-popup" id="genrePopup">
        <h4 class="text-warning text-center mb-4">🏮 DAFTAR GENRE NUKLIR 🏮</h4>
        <div class="text-center">
            <?php foreach($all_genres as $g): ?> <a href="home.php?q=<?= urlencode($g) ?>" class="tag-pill"><?= strtoupper($g) ?></a> <?php endforeach; ?>
        </div>
        <button class="btn btn-danger w-100 mt-4 fw-bold" onclick="closeGenre()">TUTUP</button>
    </div>

    <div class="dashboard-header border-bottom border-warning bg-black p-4 text-center">
        <div class="container d-flex justify-content-between align-items-center mb-3">
            <a href="index.php" class="btn btn-outline-warning btn-sm">🧧 DASHBOARD</a>
            <h1 class="text-warning">🏮 Oriental Search 🏮</h1>
            <span class="badge bg-warning text-black">Master Arata</span>
        </div>
        <button class="genre-btn mb-3" onclick="openGenre()">🔍 LIST SEMUA GENRE</button>
        <form action="home.php" method="GET" class="container"><div class="input-group">
            <input type="text" name="q" class="form-control bg-black text-warning border-warning" placeholder="Cari Genre..." value="<?= htmlspecialchars($q) ?>">
            <button class="btn btn-warning fw-bold">CARI</button>
        </div></form>
    </div>
    <div class="container mt-5"><div class="row" id="miko-list">
        <?php foreach ($matches as $m): ?>
            <?php preg_match('/galleries\/(\d+)\//', $m[2], $gm); $gid = $gm[1] ?? ''; ?>
            <div class="col-md-3 mb-4 miko-item"><div class="card-oriental h-100 text-center">
                <img src="https://i1.nhentai.net/galleries/<?= $gid ?>/1.jpg" onerror="this.src='<?= $m[2] ?>';">
                <div class="p-2">
                    <div style="font-size:0.85rem; height:65px; overflow:hidden; color:#FFD700;"><?= htmlspecialchars($m[3]) ?></div>
                    <form action="index.php" method="POST"><input type="hidden" name="id" value="<?= $m[1] ?>"><button type="submit" class="btn btn-warning btn-sm w-100 fw-bold mt-2">CULIK & SYNC</button></form>
                </div>
            </div></div>
        <?php endforeach; ?>
    </div><div id="sentinel" class="text-center py-4"><div class="spinner-border text-warning"></div></div></div>
    <script>
        function openGenre(){ document.getElementById('genrePopup').style.display='block'; document.getElementById('overlayGenre').style.display='block'; }
        function closeGenre(){ document.getElementById('genrePopup').style.display='none'; document.getElementById('overlayGenre').style.display='none'; }
        let page = <?= $page ?>; let query = "<?= urlencode($q) ?>"; let loading = false;
        const observer = new IntersectionObserver(entries => {
            if (entries[0].isIntersecting && !loading) {
                loading = true; page++;
                fetch(`home.php?q=${query}&p=${page}`).then(res => res.text()).then(data => {
                    const parser = new DOMParser(); const html = parser.parseFromString(data, 'text/html');
                    const newItems = html.querySelectorAll('.miko-item');
                    if (newItems.length > 0) { newItems.forEach(item => document.getElementById('miko-list').appendChild(item)); loading = false; }
                });
            }
        });
        observer.observe(document.getElementById('sentinel'));
    </script>
</body>
</html>
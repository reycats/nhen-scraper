<?php
/**
 * ORIENTAL SEARCH v4.0 - FINAL PENSI EDITION [cite: 2026-02-22]
 * Anti-Hitam & 120+ Genre/Characters Mastermind Mitsuki Arata
 */
require_once 'config.php';
$q = $_GET['q'] ?? ''; $page = $_GET['p'] ?? 1;
$url = empty($q) ? "https://nhentai.net/?page=$page" : "https://nhentai.net/search/?q=" . urlencode($q) . "&page=" . $page;

$ch = curl_init($url); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
$html = curl_exec($ch); curl_close($ch);

preg_match_all('/<div class="gallery".*?><a href="\/g\/(\d+)\/".*?><img.*?data-src="(.*?)".*?><div class="caption">(.*?)<\/div>/', $html, $matches, PREG_SET_ORDER);

$all_genres = [
    // === MASTER SELECTION (Karakter & Seragam Elit) ===
    'schoolgirl uniform', 'miko', 'stockings', 'sole female', 'nurse', 'swimsuit', 'glasses', 'loli', 'milf', 'teacher', 'maid', 'bikini', 'imouto', 'ojousan', 'shota', 'gyaru', 'dark skin', 'tomboy', 'crossdressing', 'cheerleader', 'catgirl', 'bunny girl', 'demon girl', 'elf', 'gender bender', 'big breasts',
    
    // === CHARACTER ARCHETYPES (Spesial Kaisar Arata) ===
    'tsundere', 'kuudere', 'yandere', 'onee-san', 'shrine maiden', 'magical girl', 'nun', 'goddess', 'vampire', 'succubus', 'monster girl', 'ghost', 'angel', 'demon', 'cyborg', 'robot', 'alien', 'mermaid', 'fox girl', 'wolf girl', 'centaur', 'harpy', 'slime girl', 'plant girl', 'insect girl',
    
    // === ANIME & SERIES TRIBUTE (Karakter Anime Komik) ===
    're zero', 'blue archive', 'genshin impact', 'hololive', 'fate series', 'kantai collection', 'azur lane', 'idolmaster', 'love live', 'granblue fantasy', 'sword art online', 'pokemon', 'naruto', 'one piece', 'dragon ball', 'bleach', 'jojo', 'my hero academia', 'demon slayer', 'chainsaw man', 'spy x family', 'k-on', 'evangelion', 'sailor moon', 'cardcaptor sakura',
    
    // === VISUAL & FORMAT (Biar AMOLED iQOO Puas!) ===
    'full color', 'artbook', 'tankoubon', 'webtoon', 'mosaic censorship', 'no censorship', 'translated', 'english', 'uncensored', 'scanlated', 'rewrite', 'original', '3d', 'cg', 'full censorship', 'unlimited height', 'high definition', 'doujinshi', 'manga', 'anime',
    
    // === HARDCORE & ADRENALINE (Cilacap Mode On) ===
    'netorare', 'group', 'anal', 'nakadashi', 'yuri', 'incest', 'femdom', 'mind control', 'exhibitionism', 'masturbation', 'defloration', 'bondage', 'bloomers', 'pregnant', 'tentacles', 'rape', 'futa', 'handjob', 'blowjob', 'creampie', 'paizuri', 'x-ray', 'oral', 'urination', 'bdsm', 'gangbang', 'ahegao', 'prostitution', 'body swap', 'possession', 'mind break', 'drugs', 'blackmail'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><title>🐉 ORIENTAL SEARCH MASTER 🧧</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* === CATATAN KRUSIAL: JANGAN DIUBAH! ANTI-ITEM! [cite: 2026-02-22] === */
        .modal-backdrop { display: none !important; }
        body { background: #000 !important; color: #fff !important; overflow-x: hidden !important; }
        
        .genre-popup { 
            display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); 
            width: 95%; max-width: 900px; max-height: 85vh; overflow-y: auto; background: #111; 
            border: 3px solid #D4AF37; z-index: 10001; padding: 25px; border-radius: 15px; box-shadow: 0 0 60px #000; 
        }
        .overlay-genre { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 10000; }
        .tag-pill { 
            background: #333; color: #FFD700; border: 1px solid #D4AF37; margin: 4px; 
            display: inline-block; padding: 8px 15px; border-radius: 20px; text-decoration: none; font-size: 0.85rem; 
        }
        .tag-pill:hover { background: #D4AF37; color: #000; transform: scale(1.05); }
        .card-oriental { border: 1px solid #D4AF37; background: #111; border-radius: 10px; overflow: hidden; margin-bottom: 25px; transition: 0.3s; }
        .card-oriental:hover { transform: translateY(-5px); box-shadow: 0 0 20px #D4AF37; }
        .genre-btn { background: #8B0000; color: #D4AF37; border: 2px solid #D4AF37; font-weight: bold; padding: 12px 30px; border-radius: 5px; }
        .card-title-full { font-size: 0.85rem; height: 65px; overflow: hidden; color: #FFD700; }
    </style>
</head>
<body class="bg-dark">
    <div class="overlay-genre" id="overlayGenre" onclick="closeGenre()"></div>
    <div class="genre-popup" id="genrePopup">
        <h3 class="text-warning text-center mb-4">🏮 GUDANG 120+ GENRE & KARAKTER 🏮</h3>
        <div class="text-center">
            <?php foreach($all_genres as $g): ?> 
                <a href="home.php?q=<?= urlencode($g) ?>" class="tag-pill"><?= strtoupper($g) ?></a> 
            <?php endforeach; ?>
        </div>
        <button class="btn btn-danger w-100 mt-5 fw-bold py-3" onclick="closeGenre()">TUTUP KOLEKSI</button>
    </div>

    <div class="dashboard-header border-bottom border-warning bg-black p-5 text-center">
        <div class="container d-flex justify-content-between align-items-center mb-4">
            <a href="index.php" class="btn btn-outline-warning btn-sm">🧧 DASHBOARD</a>
            <h1 class="text-warning" style="text-shadow: 3px 3px #000;">🏮 Oriental Search Master 🏮</h1>
            <span class="badge bg-warning text-black px-3 py-2">Master Arata</span>
        </div>
        <button class="genre-btn mb-4 shadow-lg" onclick="openGenre()">🔍 BUKA GUDANG GENRE</button>
        <form action="home.php" method="GET" class="container"><div class="input-group input-group-lg">
            <input type="text" name="q" class="form-control bg-black text-warning border-warning" placeholder="Cari Koleksi Nuklir lu bbi..." value="<?= htmlspecialchars($q) ?>">
            <button class="btn btn-warning fw-bold px-5">CARI</button>
        </div></form>
    </div>

    <div class="container mt-5"><div class="row" id="miko-list">
        <?php foreach ($matches as $m): ?>
            <?php preg_match('/galleries\/(\d+)\//', $m[2], $gm); $gid = $gm[1] ?? ''; ?>
            <div class="col-md-3 mb-4 miko-item"><div class="card-oriental h-100 text-center shadow-sm">
                <img src="https://i1.nhentai.net/galleries/<?= $gid ?>/1.jpg" onerror="this.src='<?= $m[2] ?>';" style="height:380px; width:100%; object-fit:cover;">
                <div class="p-3">
                    <div class="card-title-full fw-bold"><?= htmlspecialchars($m[3]) ?></div>
                    <form action="index.php" method="POST"><input type="hidden" name="id" value="<?= $m[1] ?>">
                        <button type="submit" class="btn btn-warning btn-sm w-100 fw-bold mt-3 py-2">CULIK & SYNC</button>
                    </form>
                </div>
            </div></div>
        <?php endforeach; ?>
    </div><div id="sentinel" class="text-center py-5"><div class="spinner-border text-warning" style="width: 3rem; height: 3rem;"></div></div></div>

    <script>
        function openGenre(){ document.getElementById('genrePopup').style.display='block'; document.getElementById('overlayGenre').style.display='block'; document.body.style.overflow='hidden'; }
        function closeGenre(){ document.getElementById('genrePopup').style.display='none'; document.getElementById('overlayGenre').style.display='none'; document.body.style.overflow='auto'; }
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
        }, { threshold: 0.1 });
        observer.observe(document.getElementById('sentinel'));
    </script>
</body>
</html>
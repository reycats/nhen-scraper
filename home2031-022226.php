<?php
/**
 * ORIENTAL SEARCH v6.1 - MASTER ARATA EDITION [cite: 2026-02-22]
 * Fitur: Multi-Select Genre, Anti-Hitam, Smooth Infinite Scroll
 */
require_once 'config.php';
$q = $_GET['q'] ?? ''; $page = $_GET['p'] ?? 1;
// Support multi-tags biar kaisar bisa panen kombinasi nuklir bbi! [cite: 2026-02-22]
$url = empty($q) ? "https://nhentai.net/?page=$page" : "https://nhentai.net/search/?q=" . urlencode($q) . "&page=" . $page;

$ch = curl_init($url); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
$html = curl_exec($ch); curl_close($ch);

preg_match_all('/<div class="gallery".*?><a href="\/g\/(\d+)\/".*?><img.*?data-src="(.*?)".*?><div class="caption">(.*?)<\/div>/', $html, $matches, PREG_SET_ORDER);

$cat_genres = ['schoolgirl uniform', 'miko', 'stockings', 'sole female', 'nurse', 'swimsuit', 'glasses', 'loli', 'milf', 'teacher', 'maid', 'bikini', 'imouto', 'ojousan', 'shota', 'gyaru', 'dark skin', 'tomboy', 'crossdressing', 'cheerleader', 'catgirl', 'bunny girl', 'demon girl', 'elf', 'gender bender', 'big breasts', 'netorare', 'group', 'anal', 'nakadashi', 'yuri', 'incest', 'femdom', 'mind control', 'exhibitionism', 'masturbation', 'defloration', 'bondage', 'bloomers', 'pregnant', 'tentacles', 'rape', 'futa', 'handjob', 'blowjob', 'creampie', 'paizuri', 'x-ray', 'oral', 'urination', 'bdsm', 'gangbang', 'ahegao', 'prostitution', 'body swap', 'possession', 'mind break', 'drugs', 'blackmail', 'vampire', 'succubus', 'monster girl'];
$cat_anime = ['re zero', 'blue archive', 'genshin impact', 'hololive', 'fate series', 'kantai collection', 'azur lane', 'idolmaster', 'love live', 'granblue fantasy', 'sword art online', 'pokemon', 'naruto', 'one piece', 'dragon ball', 'bleach', 'jojo', 'my hero academia', 'demon slayer', 'chainsaw man', 'spy x family', 'k-on', 'evangelion', 'sailor moon', 'cardcaptor sakura'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><title>🐉 ORIENTAL SEARCH MASTER 🧧</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .modal-backdrop { display: none !important; }
        body { background: #000 !important; color: #fff !important; overflow-x: hidden !important; }
        .genre-popup { display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 95%; max-width: 1000px; max-height: 85vh; overflow-y: auto; background: #111; border: 3px solid #D4AF37; z-index: 10001; padding: 30px; border-radius: 15px; box-shadow: 0 0 60px #000; }
        .overlay-genre { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 10000; }
        .multi-tag { display: none; }
        .tag-label { background: #333; color: #FFD700; border: 1px solid #D4AF37; margin: 4px; display: inline-block; padding: 8px 15px; border-radius: 20px; font-size: 0.8rem; cursor: pointer; transition: 0.2s; }
        .multi-tag:checked + .tag-label { background: #D4AF37; color: #000; font-weight: bold; transform: scale(1.1); box-shadow: 0 0 10px #D4AF37; }
        .cat-title { color: #8B0000; border-left: 5px solid #D4AF37; padding-left: 10px; margin: 25px 0 15px 0; font-weight: bold; text-transform: uppercase; }
        .card-oriental { border: 1px solid #D4AF37; background: #111; border-radius: 10px; overflow: hidden; margin-bottom: 25px; transition: 0.3s; }
        .genre-btn { background: #8B0000; color: #D4AF37; border: 2px solid #D4AF37; font-weight: bold; padding: 12px 35px; border-radius: 5px; }
    </style>
</head>
<body class="bg-dark">
    <div class="overlay-genre" id="overlayGenre" onclick="closeGenre()"></div>
    <div class="genre-popup" id="genrePopup">
        <h3 class="text-warning text-center mb-4">🏮 GUDANG MULTI-SELECT KAISAR 🏮</h3>
        <form id="multiSearchForm" action="home.php" method="GET">
            <div class="cat-title">🧧 KATEGORI GENRE NUKLIR</div>
            <div class="text-center"><?php foreach($cat_genres as $g): ?>
                <input type="checkbox" name="tags[]" value="<?= $g ?>" id="t_<?= md5($g) ?>" class="multi-tag">
                <label for="t_<?= md5($g) ?>" class="tag-label"><?= strtoupper($g) ?></label>
            <?php endforeach; ?></div>
            <div class="cat-title">🧧 SERI ANIME & KOMIK</div>
            <div class="text-center"><?php foreach($cat_anime as $g): ?>
                <input type="checkbox" name="tags[]" value="<?= $g ?>" id="t_<?= md5($g) ?>" class="multi-tag">
                <label for="t_<?= md5($g) ?>" class="tag-label"><?= strtoupper($g) ?></label>
            <?php endforeach; ?></div>
            <div class="sticky-bottom bg-dark p-3 mt-4 border-top border-warning text-center">
                <input type="hidden" name="q" id="finalQuery">
                <button type="button" class="btn btn-warning fw-bold px-5 py-3 shadow" onclick="submitMulti()">🚀 GAS CULIK</button>
            </div>
        </form>
    </div>

    <div class="dashboard-header border-bottom border-warning bg-black p-5 text-center">
        <button class="genre-btn mb-4 shadow-lg" onclick="openGenre()">🔍 PILIH BANYAK GENRE</button>
        <form action="home.php" method="GET" class="container"><div class="input-group input-group-lg">
            <input type="text" name="q" class="form-control bg-black text-warning border-warning" placeholder="Cari Koleksi..." value="<?= htmlspecialchars($q) ?>">
            <button class="btn btn-warning fw-bold px-5">CARI</button>
        </div></form>
    </div>

    <div class="container mt-5"><div class="row" id="miko-list">
        <?php foreach ($matches as $m): ?>
            <div class="col-md-3 mb-4 miko-item"><div class="card-oriental h-100 text-center">
                <img src="https://i1.nhentai.net/galleries/<?= (preg_match('/galleries\/(\d+)\//', $m[2], $gm) ? $gm[1] : '') ?>/1.jpg" onerror="this.src='<?= $m[2] ?>';" style="height:380px; width:100%; object-fit:cover;">
                <div class="p-3"><div style="font-size:0.85rem; height:65px; color:#FFD700; font-weight:bold;"><?= htmlspecialchars($m[3]) ?></div>
                <form action="index.php" method="POST"><input type="hidden" name="id" value="<?= $m[1] ?>"><button type="submit" class="btn btn-warning btn-sm w-100 mt-3 py-2 fw-bold">CULIK & SYNC</button></form></div>
            </div></div>
        <?php endforeach; ?>
    </div><div id="sentinel" class="text-center py-5"><div class="spinner-border text-warning" style="width: 3rem; height: 3rem;"></div></div></div>

    <script>
        function openGenre(){ document.getElementById('genrePopup').style.display='block'; document.getElementById('overlayGenre').style.display='block'; document.body.style.overflow='hidden'; }
        function closeGenre(){ document.getElementById('genrePopup').style.display='none'; document.getElementById('overlayGenre').style.display='none'; document.body.style.overflow='auto'; }
        function submitMulti() {
            const checked = Array.from(document.querySelectorAll('input[name="tags[]"]:checked')).map(el => `"${el.value}"`);
            if(checked.length === 0) return alert("Pilih satu aja pelit bgt bbi!");
            document.getElementById('finalQuery').value = checked.join(' ');
            document.getElementById('multiSearchForm').submit();
        }
        let page = <?= $page ?>; let query = "<?= urlencode($q) ?>"; let loading = false;
        const observer = new IntersectionObserver(entries => {
            if (entries[0].isIntersecting && !loading) {
                loading = true; page++;
                fetch(`home.php?q=${query}&p=${page}`).then(res => res.text()).then(data => {
                    const parser = new DOMParser(); const html = parser.parseFromString(data, 'text/html');
                    const newItems = html.querySelectorAll('.miko-item');
                    if (newItems.length > 0) { 
                        newItems.forEach(item => document.getElementById('miko-list').appendChild(item)); 
                        loading = false; 
                    } else { document.getElementById('sentinel').innerHTML = "<p class='text-warning'>HABIS KOLEKSINYA ASU!</p>"; }
                }).catch(() => { loading = false; });
            }
        }, { threshold: 0.1 });
        observer.observe(document.getElementById('sentinel'));
    </script>
</body>
</html>
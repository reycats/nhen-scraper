<?php
require_once 'config.php';
$q = $_GET['q'] ?? '';
$page = $_GET['p'] ?? 1;
$results = [];

// API Scraper Trending/Search [cite: 2026-02-22]
$url = empty($q) ? "https://nhentai.net/?page=$page" : "https://nhentai.net/search/?q=" . urlencode($q) . "&page=" . $page;

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$html = curl_exec($ch); curl_close($ch);

preg_match_all('/<div class="gallery".*?><a href="\/g\/(\d+)\/".*?><img.*?data-src="(.*?)".*?><div class="caption">(.*?)<\/div>/', $html, $matches, PREG_SET_ORDER);
$results = $matches;

$top_tags = ['schoolgirl', 'miko', 'stockings', 'sole female', 'nurse', 'swimsuit', 'glasses', 'netorare'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><title>🐉 ORIENTAL EXPLORE 🧧</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #000; color: #fff; }
        .card-oriental { border: 1px solid #D4AF37; background: #111; border-radius: 10px; overflow: hidden; margin-bottom: 20px; transition: 0.3s; }
        .card-oriental:hover { transform: scale(1.02); box-shadow: 0 0 15px #D4AF37; }
        .tag-pill { background: #8B0000; color: #D4AF37; border: 1px solid #D4AF37; margin: 3px; font-size: 0.75rem; text-decoration: none; display: inline-block; padding: 5px 10px; border-radius: 20px; }
        .card-title-full { font-size: 0.8rem; height: 60px; overflow: hidden; color: #FFD700; margin-bottom: 10px; }
        .dashboard-header { background: #000; border-bottom: 2px solid #D4AF37; padding: 20px; }
    </style>
</head>
<body class="bg-dark text-light">
    <div class="dashboard-header text-center">
        <div class="container d-flex justify-content-between align-items-center mb-3">
            <a href="index.php" class="btn btn-outline-warning btn-sm">🧧 DASHBOARD</a>
            <h1 class="mb-0 text-warning" style="text-shadow: 2px 2px #000;">🐉 Oriental Search 🧧</h1>
            <span class="badge bg-warning text-black"><?= $_SESSION['username'] ?></span>
        </div>
        <div class="container mt-2 text-center mb-3">
            <?php foreach($top_tags as $tag): ?>
                <a href="home.php?q=<?= urlencode($tag) ?>" class="tag-pill"><?= strtoupper($tag) ?></a>
            <?php endforeach; ?>
        </div>
        <form action="home.php" method="GET" class="container"><div class="input-group">
            <input type="text" name="q" class="form-control bg-black text-warning border-warning" placeholder="Cari Genre Apapun bbi..." value="<?= htmlspecialchars($q) ?>">
            <button class="btn btn-warning fw-bold">CARI</button>
        </div></form>
    </div>

    <div class="container mt-5">
        <div class="row" id="miko-list">
            <?php foreach ($results as $m): ?>
                <?php preg_match('/galleries\/(\d+)\//', $m[2], $gm); $gid = $gm[1] ?? ''; ?>
                <div class="col-md-3 mb-4 miko-item"><div class="card-oriental h-100 text-center">
                    <img src="https://i1.nhentai.net/galleries/<?= $gid ?>/1.jpg" onerror="this.src='<?= $m[2] ?>';" style="height:350px; width:100%; object-fit:cover;">
                    <div class="p-2">
                        <div class="card-title-full"><?= htmlspecialchars($m[3]) ?></div>
                        <form action="index.php" method="POST">
                            <input type="hidden" name="id" value="<?= $m[1] ?>">
                            <button type="submit" class="btn btn-warning btn-sm w-100 fw-bold">CULIK & SYNC</button>
                        </form>
                    </div>
                </div></div>
            <?php endforeach; ?>
        </div>
        <div id="sentinel" class="text-center py-4"><div class="spinner-border text-warning"></div></div>
    </div>

    <script>
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
                    }
                });
            }
        });
        observer.observe(document.getElementById('sentinel'));
    </script>
</body>
</html>
<?php
require_once 'config.php';
require_once 'scraper.php';
require_once 'scraper_com.php';
require_once 'discord_sync.php';

// Reset miko sekolah biar bersih kntol!
if (isset($_POST['clear_all'])) {
    $_SESSION['cards'] = [];
    header("Location: index.php"); exit;
}

// Ambil Bookmarks dari Database Sync [cite: 2026-02-22]
$stmt = $db->prepare("SELECT * FROM bookmarks WHERE username = ? ORDER BY added_at DESC");
$stmt->execute([$_SESSION['username']]);
$bookmarks = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = trim($_POST['id']);
    $src = $_POST['source'] ?? 'net';
    $data = ($src == 'com') ? fetchDataCom($id) : fetchDataById($id);
    if ($data) {
        $new_miko = [
            'id' => $id, 'title' => $data['title'], 'cover' => $data['cover'],
            'tags' => $data['tags'], 'source' => $src,
            'gid' => $data['gid'] ?? '', 'pages' => $data['pages'] ?? 35
        ];
        if (!isset($_SESSION['cards'])) $_SESSION['cards'] = [];
        $_SESSION['cards'][] = $new_miko;
        sendToDiscord($new_miko);
    }
    header("Location: index.php"); exit;
}
$cards = $_SESSION['cards'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>🐉 <?= SITE_NAME ?> 🧧</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        /* FIX ANTI-HITAM GAIB ASU! [cite: 2026-02-22] */
        .modal-backdrop { display: none !important; }
        body { background: #000 !important; color: #D4AF37 !important; overflow: auto !important; padding-right: 0 !important; }
        .card-oriental img { height: 400px; object-fit: cover; border-bottom: 2px solid #D4AF37; }
        .bookmark-section { margin-bottom: 50px; padding: 20px; border: 1px solid #D4AF37; border-radius: 10px; background: #111; }
    </style>
</head>
<body class="bg-black text-warning">
    <div class="dashboard-header text-center p-4">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="home.php" class="btn btn-outline-warning btn-sm">🔍 CARI MIKO</a>
            <h1>🐉 Oriental Dashboard 🧧</h1>
            <form method="post"><button name="clear_all" class="btn btn-outline-danger btn-sm">RESET</button></form>
        </div>
    </div>
    <div class="container mt-4">
        <div class="bookmark-section">
            <h3 class="mb-4">🏮 MY BOOKMARKS (CLOUD SYNCED)</h3>
            <div class="row">
                <?php if (empty($bookmarks)): ?> <p class="text-center text-muted">Belum ada miko bbi!</p> <?php endif; ?>
                <?php foreach ($bookmarks as $b): ?>
                    <div class="col-md-3 mb-4"><div class="card-oriental h-100">
                        <img src="https://i1.nhentai.net/galleries/<?= $b['media_id'] ?>/1.jpg" onerror="this.src='<?= $b['cover'] ?>';" style="height:250px; object-fit:cover;">
                        <div class="p-2 text-center">
                            <h6 class="small text-truncate"><?= htmlspecialchars($b['title']) ?></h6>
                            <a href="reader.php?id=<?= $b['miko_id'] ?>&gid=<?= $b['media_id'] ?>&pages=<?= $b['pages'] ?>" class="btn btn-warning btn-sm w-100 fw-bold">BACA HD</a>
                        </div>
                    </div></div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="row justify-content-center mb-5"><div class="col-md-6 card-oriental p-4"><form method="post">
            <div class="input-group">
                <select name="source" class="form-select bg-dark text-warning"><option value="net">.NET</option><option value="com">.COM</option></select>
                <input type="text" name="id" class="form-control" placeholder="6 Digit Kode..." required>
                <button type="submit" class="btn btn-oriental">CULIK</button>
            </div>
        </form></div></div>
        <div class="row">
            <?php foreach (array_reverse($cards) as $card): ?>
                <div class="col-md-4 mb-4"><div class="card-oriental">
                    <img src="<?= htmlspecialchars($card['cover']) ?>" style="height:400px; object-fit:cover;">
                    <div class="card-body">
                        <h6 class="card-title text-truncate"><?= htmlspecialchars($card['title']) ?></h6>
                        <a href="reader.php?id=<?= $card['id'] ?>&gid=<?= $card['gid'] ?>&pages=<?= $card['pages'] ?>" class="btn btn-oriental w-100">BACA</a>
                    </div>
                </div></div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
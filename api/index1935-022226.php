<?php
/**
 * ORIENTAL DASHBOARD v2.5 [cite: 2026-02-22]
 * Fitur: Anti-Hitam, Auto-Shuffle, Scraper Sync
 * JANGAN UBAH CSS !important ATAU LAYAR JADI HITAM GAIB ASU!
 */
require_once 'config.php';
require_once 'scraper.php';

// FITUR SHUFFLE MIKO [cite: 2026-02-22]
if (isset($_GET['shuffle'])) {
    $random_id = rand(100000, 650000); // Culik ID acak kaisar!
    header("Location: reader.php?id=$random_id"); exit;
}

if (isset($_GET['delete'])) {
    $stmt = $db->prepare("DELETE FROM bookmarks WHERE id = ? AND username = ?");
    $stmt->execute([$_GET['delete'], $_SESSION['username']]);
    header("Location: index.php"); exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = trim($_POST['id']); $data = fetchDataById($id);
    if ($data) {
        $stmt = $db->prepare("INSERT INTO bookmarks (username, miko_id, media_id, title, cover, pages) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$_SESSION['username'], $id, $data['gid'], $data['title'], $data['cover'], $data['pages']]);
    }
    header("Location: index.php"); exit;
}

$stmt = $db->prepare("SELECT * FROM bookmarks WHERE username = ? ORDER BY added_at DESC");
$stmt->execute([$_SESSION['username']]);
$bookmarks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><title>🐉 <?= SITE_NAME ?> 🧧</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* === CATATAN KRUSIAL: JANGAN DIUBAH! [cite: 2026-02-22] === */
        .modal-backdrop { display: none !important; }
        body { background: #000 !important; color: #D4AF37 !important; overflow-x: hidden !important; }
        .card-oriental { border: 1px solid #D4AF37; background: #111; border-radius: 10px; overflow: hidden; margin-bottom: 20px; transition: 0.3s; }
        .card-oriental img { height: 380px; width: 100%; object-fit: cover; border-bottom: 1px solid #D4AF37; }
        .dashboard-header { background: linear-gradient(to bottom, #8B0000, #000); padding: 20px; border-bottom: 2px solid #D4AF37; }
        .btn-gold { background: #D4AF37; color: #000; font-weight: bold; border: none; }
    </style>
</head>
<body class="bg-black">
    <div class="dashboard-header text-center">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex gap-2">
                <a href="home.php" class="btn btn-outline-warning btn-sm">🔍 CARI MIKO</a>
                <a href="index.php?shuffle=1" class="btn btn-info btn-sm fw-bold text-black">🎲 ACAK MIKO</a>
            </div>
            <h1 style="color:#D4AF37; text-shadow: 2px 2px #000;">🏮 Oriental Dashboard 🏮</h1>
            <a href="logout.php" class="btn btn-outline-danger btn-sm">KELUAR</a>
        </div>
    </div>
    <div class="container mt-4">
        <div class="row justify-content-center mb-5"><div class="col-md-6 card-oriental p-4"><form method="post">
            <div class="input-group">
                <input type="text" name="id" class="form-control bg-dark text-warning border-warning" placeholder="6 Digit Kode Nuklir..." required>
                <button type="submit" class="btn btn-gold">CULIK & SYNC</button>
            </div>
        </form></div></div>
        <h3 class="mb-4">🏮 MY BOOKMARKS (CLOUD SYNCED)</h3>
        <div class="row">
            <?php foreach ($bookmarks as $b): ?>
                <div class="col-md-3 mb-4"><div class="card-oriental h-100">
                    <img src="https://i1.nhentai.net/galleries/<?= $b['media_id'] ?>/1.jpg" onerror="this.src='<?= $b['cover'] ?>';">
                    <div class="p-2 text-center">
                        <h6 class="small text-truncate text-warning"><?= htmlspecialchars($b['title']) ?></h6>
                        <div class="d-flex gap-1 mt-2">
                            <a href="reader.php?id=<?= $b['miko_id'] ?>&gid=<?= $b['media_id'] ?>&pages=<?= $b['pages'] ?>" class="btn btn-gold btn-sm w-100">BACA</a>
                            <a href="downloader.php?id=<?= $b['miko_id'] ?>&gid=<?= $b['media_id'] ?>&pages=<?= $b['pages'] ?>" class="btn btn-danger btn-sm w-100">ZIP</a>
                            <a href="index.php?delete=<?= $b['id'] ?>" class="btn btn-dark btn-sm">🗑️</a>
                        </div>
                    </div>
                </div></div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
<?php
require_once 'config.php';
$user = $_GET['user'] ?? '';
if (isset($_POST['verify'])) {
    $stmt = $db->prepare("SELECT * FROM users_nuklir WHERE username = ? AND v_code = ?");
    $stmt->execute([$user, $_POST['code']]);
    if ($stmt->fetch()) {
        $db->prepare("UPDATE users_nuklir SET status = 'active' WHERE username = ?")->execute([$user]);
        header("Location: login.php?msg=Sukses!"); exit;
    } else { $error = "Kodenya salah asu!"; }
}
?>
<!DOCTYPE html>
<html lang="en"><head><title>Verify</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-black text-warning d-flex align-items-center justify-content-center" style="height:100vh;">
    <div class="p-4 border border-warning rounded bg-dark text-center" style="width:350px;">
        <h4>🧧 VERIFIKASI 🧧</h4>
        <form method="post"><input type="text" name="code" class="form-control mb-3 bg-black text-warning text-center" placeholder="KODE DISCORD" required><button name="verify" class="btn btn-warning w-100">AKTIFKAN</button></form>
    </div>
</body></html>
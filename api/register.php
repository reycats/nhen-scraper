<?php
require_once 'config.php';
if (isset($_POST['register'])) {
    $user = $_POST['username']; $pass = password_hash($_POST['password'], PASSWORD_DEFAULT); $code = strtoupper(substr(md5(time()), 0, 6));
    try {
        $stmt = $db->prepare("INSERT INTO users_nuklir (username, password, v_code) VALUES (?, ?, ?)"); $stmt->execute([$user, $pass, $code]);
        $msg = ["content" => "🏮 **KAISAR BARU DAFTAR BBI!**\nUsername: `$user`\nKode: `$code`"];
        $ch = curl_init(DISCORD_WEBHOOK_URL); curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, 1); curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($msg)); curl_exec($ch); curl_close($ch);
        header("Location: verify.php?user=$user"); exit;
    } catch(Exception $e) { $error = "Username udah ada dongo!"; }
}
?>
<!DOCTYPE html>
<html lang="en"><head><title>Register</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="bg-black text-warning d-flex align-items-center justify-content-center" style="height:100vh;">
    <div class="p-4 border border-warning rounded bg-dark" style="width:350px;">
        <h3 class="text-center mb-4">🏮 DAFTAR KAISAR 🏮</h3>
        <form method="post">
            <input type="text" name="username" class="form-control mb-3 bg-black text-warning" placeholder="Username" required>
            <input type="password" name="password" class="form-control mb-3 bg-black text-warning" placeholder="Password" required>
            <button name="register" class="btn btn-warning w-100 fw-bold">DAFTAR & KIRIM KODE</button>
        </form>
    </div>
</body></html>
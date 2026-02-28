<?php
require_once 'config.php';

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (isset($users[$username]) && password_verify($password, $users[$username])) {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        
        // BIKIN LOGIN ABADI 30 HARI DI BROWSER HP! [cite: 2026-02-21]
        setcookie('remember_mitsuki', md5($username . "NUKLIR_ELIT"), time() + (86400 * 30), "/");
        
        header('Location: index.php');
        exit;
    } else {
        $error = 'Username atau password salah, dongo!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Oriental Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1a0000 0%, #4d0000 100%); display: flex; align-items: center; justify-content: center; height: 100vh; margin:0; }
        .login-container { background: rgba(0,0,0,0.85); padding: 40px; border-radius: 20px; border: 2px solid #D4AF37; box-shadow: 0 0 20px #D4AF37; width: 100%; max-width: 400px; }
        .form-control { background: #111; border: 1px solid #D4AF37; color: #FFD700; }
        .form-control:focus { background: #222; color: #FFD700; border-color: #FF4500; box-shadow: none; }
        .btn-oriental { background: linear-gradient(45deg, #8B0000, #D4AF37); border: none; font-weight: bold; color: white; padding: 10px; border-radius: 5px; cursor: pointer; }
        .btn-oriental:hover { transform: scale(1.05); filter: brightness(1.2); }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 class="text-center mb-4" style="color:#D4AF37; font-family:serif;">🏮 MASUK GERBANG 🏮</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger" style="background: #8B0000; border: none; color: white; padding: 10px; margin-bottom: 20px; border-radius: 5px;"><?= $error ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-3">
                <label style="color:#D4AF37; display:block; margin-bottom:5px;">Username</label>
                <input type="text" name="username" class="form-control w-100" required placeholder="Nama kaisar...">
            </div>
            <div class="mb-3" style="margin-top:15px;">
                <label style="color:#D4AF37; display:block; margin-bottom:5px;">Password</label>
                <input type="password" name="password" class="form-control w-100" required placeholder="Kunci nuklir...">
            </div>
            <button type="submit" class="btn-oriental w-100" style="margin-top:20px;">BUKA SEGEL</button>
        </form>
    </div>
</body>
</html>
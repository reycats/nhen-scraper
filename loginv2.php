<?php
require_once 'config.php';

// Kalau udah login, langsung lempar ke dashboard asu!
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // CEK MULTI-AKUN BBI! [cite: 2026-02-21]
    if (isset($users[$username]) && password_verify($password, $users[$username])) {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
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
    <link rel="stylesheet" href="style.css">
    <style>
        body { background: linear-gradient(135deg, #1a0000 0%, #4d0000 100%); display: flex; align-items: center; justify-content: center; height: 100vh; }
        .login-container { background: rgba(0,0,0,0.85); padding: 40px; border-radius: 20px; border: 2px solid #D4AF37; box-shadow: 0 0 20px #D4AF37; width: 100%; max-width: 400px; }
        .form-control { background: #111; border: 1px solid #D4AF37; color: #FFD700; }
        .form-control:focus { background: #222; color: #FFD700; border-color: #FF4500; box-shadow: none; }
        .btn-oriental { background: linear-gradient(45deg, #8B0000, #D4AF37); border: none; font-weight: bold; color: white; transition: 0.3s; }
        .btn-oriental:hover { transform: scale(1.05); filter: brightness(1.2); }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 class="text-center mb-4" style="color:#D4AF37; text-shadow: 2px 2px #000;">🏮 MASUK GERBANG 🏮</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger" style="background: #8B0000; border: none; color: white;"><?= $error ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-3">
                <label class="form-label" style="color:#D4AF37;">Username</label>
                <input type="text" name="username" class="form-control" required placeholder="Nama kaisar...">
            </div>
            <div class="mb-3">
                <label class="form-label" style="color:#D4AF37;">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="Kunci nuklir...">
            </div>
            <button type="submit" class="btn btn-oriental w-100 py-2">BUKA SEGEL</button>
        </form>
    </div>
</body>
</html>
<?php
session_start();
session_destroy();
// Hapus cookie login abadi bbi!
setcookie('remember_mitsuki', '', time() - 3600, "/");
header("Location: login.php");
exit;
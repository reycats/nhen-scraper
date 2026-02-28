<?php
require_once 'config.php';
set_time_limit(0); // Biar laptop kentang lu gak timeout asu!
$id = $_GET['id'] ?? '';
$gid = $_GET['gid'] ?? '';
$pages = $_GET['pages'] ?? 35;

if (!$id || !$gid) die("Data miko dongo asu!");

$zip = new ZipArchive();
$zip_name = "Miko_$id.zip";

if ($zip->open($zip_name, ZipArchive::CREATE) === TRUE) {
    for ($i = 1; $i <= $pages; $i++) {
        $server = "t" . (($i % 4) + 1);
        // Coba webp dulu bbi!
        $img_url = "https://$server.nhentai.net/galleries/$gid/{$i}t.webp";
        $img_data = file_get_contents($img_url);
        
        // Kalau pingsan, hajar pake jpg mampus!
        if (!$img_data) {
            $img_url = "https://$server.nhentai.net/galleries/$gid/{$i}.jpg";
            $img_data = file_get_contents($img_url);
        }
        
        if ($img_data) {
            $zip->addFromString("page_$i.jpg", $img_data);
        }
    }
    $zip->close();

    // KIRIM KE BROWSER ASU!
    header('Content-Type: application/zip');
    header('Content-disposition: attachment; filename='.$zip_name);
    header('Content-Length: ' . filesize($zip_name));
    readfile($zip_name);
    unlink($zip_name); // Hapus sampahnya biar RAM 2GB lu gak meledak kntol!
    exit;
}
<?php
error_reporting(1);
ob_start();
date_default_timezone_set('Europe/Istanbul');

// Dosyaya doğrudan erişimi engellemek için
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    exit('Erişim Engellendi.');
}

// Veritabanı bağlantı bilgileri
$host = "sql110.infinityfree.com"; // Host adınızı girin, genellikle "localhost" olur
$veritabani_ismi = "if0_37164226_buketkafe"; // Veritabanı ismi
$kullanici_adi = "if0_37164226"; // Veritabanı kullanıcı adı
$sifre = "RQpo6QSZsj"; // Kullanıcı şifreniz, şifre yoksa boş bırakın

try {
    $conn = new PDO("mysql:host=$host;dbname=$veritabani_ismi;charset=utf8", $kullanici_adi, $sifre);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Veritabanı bağlantı hatası: " . $e->getMessage();
    $conn = null;
}
?>

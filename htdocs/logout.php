<?php
session_start(); // Oturumu başlat

// Oturum verilerini sil
session_unset();

// Oturumu yok et
session_destroy();

// Ana sayfaya yönlendir
header("Location: index.php");
exit();
?>
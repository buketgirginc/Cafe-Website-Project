<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'baglan.php';  // Veritabanı bağlantınızı sağlayan dosya

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $urun_id = isset($_POST['urun_id']) ? (int)$_POST['urun_id'] : 0;
    $adet = isset($_POST['adet']) ? (int)$_POST['adet'] : 0;
    $adisyon_id = 1;  // Bu örnekte adisyon_id sabit olarak 1 alındı, uygun şekilde değiştirilmeli

    if ($urun_id > 0 && $adet > 0) {
        // Ürün bilgilerini almak için sorgu
        $sql = "SELECT fiyat FROM urunler WHERE urun_id = :urun_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':urun_id', $urun_id, PDO::PARAM_INT);
        $stmt->execute();
        $urun = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($urun) {
            $fiyat = $urun['fiyat'];
            $toplam_fiyat = $fiyat * $adet;

            // Adisyon_urunleri tablosuna veri ekleme
            $sql = "INSERT INTO adisyon_urunleri (adisyon_id, urun_id, adet, fiyat, toplam_fiyat) VALUES (:adisyon_id, :urun_id, :adet, :fiyat, :toplam_fiyat)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':adisyon_id', $adisyon_id, PDO::PARAM_INT);
            $stmt->bindParam(':urun_id', $urun_id, PDO::PARAM_INT);
            $stmt->bindParam(':adet', $adet, PDO::PARAM_INT);
            $stmt->bindParam(':fiyat', $fiyat, PDO::PARAM_STR);
            $stmt->bindParam(':toplam_fiyat', $toplam_fiyat, PDO::PARAM_STR);

            if ($stmt->execute()) {
                echo "Sipariş başarıyla kaydedildi!";
            } else {
                echo "Sipariş kaydedilirken bir hata oluştu.";
            }
        } else {
            echo "Ürün bulunamadı.";
        }
    } else {
        echo "Geçersiz ürün ID veya adet.";
    }
} else {
    echo "Geçersiz istek.";
}
?>

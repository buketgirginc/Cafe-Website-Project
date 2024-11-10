<?php
// Hata bildirimlerini aç
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'baglan.php'; // Veritabanı bağlantısını yap

// Adisyon ID'sini al
$adisyon_id = isset($_GET['adisyon_id']) ? intval($_GET['adisyon_id']) : 0;

if ($adisyon_id > 0) {
    // SQL sorgusu: Adisyon detaylarını al
    $sql = "SELECT adisyon_urunleri.*, urunler.urun_adi 
            FROM adisyon_urunleri 
            LEFT JOIN urunler ON adisyon_urunleri.urun_id = urunler.urun_id 
            WHERE adisyon_urunleri.adisyon_id = :adisyon_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':adisyon_id', $adisyon_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Veriyi JSON formatında döndür
    echo json_encode($result);
} else {
    echo json_encode([]);
}

$conn = null;
?>

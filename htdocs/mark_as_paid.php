<?php
// Hata bildirimlerini aç
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'baglan.php'; // Veritabanı bağlantısını yap

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $adisyon_id = $_POST['adisyon_id'];

    // Adisyonu ödemesi alındı olarak işaretle
    $sql = "UPDATE adisyonlar SET odeme_durumu = 'paid' WHERE adisyon_id = :adisyon_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':adisyon_id', $adisyon_id, PDO::PARAM_INT);
    $stmt->execute();

    // Masayı boş olarak işaretle
    $sql = "UPDATE masalar SET adisyon_id = NULL, status = 'empty' WHERE adisyon_id = :adisyon_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':adisyon_id', $adisyon_id, PDO::PARAM_INT);
    $stmt->execute();

    // Başarılı işlem mesajını göstermek için bir yönlendirme yap
    header("Location: adisyonliste.php?message=Ödeme alındı ve masa boş olarak işaretlendi.");
    exit();
} else {
    echo "Geçersiz istek.";
}

$conn = null;
?>

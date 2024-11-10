<?php
// Hata bildirimlerini aç
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'baglan.php'; // Veritabanı bağlantısını yap

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adisyon_id = $_POST['adisyon_id'];
    $masa_id = $_POST['masa_id'];

    if (empty($adisyon_id) || empty($masa_id)) {
        header("Location: adisyonliste.php?message=error_empty_fields");
        exit;
    }

    try {
        // Masayı güncelle
        $sql_update_masa = "UPDATE masalar SET status = 'occupied', adisyon_id = :adisyon_id WHERE id = :masa_id";
        $stmt_update_masa = $conn->prepare($sql_update_masa);
        $stmt_update_masa->bindParam(':adisyon_id', $adisyon_id);
        $stmt_update_masa->bindParam(':masa_id', $masa_id);
        $stmt_update_masa->execute();

        // Adisyonu güncelle
        $sql_update_adisyon = "UPDATE adisyonlar SET odeme_durumu = 'not_paid' WHERE adisyon_id = :adisyon_id";
        $stmt_update_adisyon = $conn->prepare($sql_update_adisyon);
        $stmt_update_adisyon->bindParam(':adisyon_id', $adisyon_id);
        $stmt_update_adisyon->execute();

        header("Location: adisyonliste.php?message=Adisyon masaya başarıyla atandı.");
        exit;
    } catch (PDOException $e) {
        header("Location: adisyonliste.php?message=error&error_message=" . urlencode($e->getMessage()));
        exit;
    }

    // Veritabanı bağlantısını kapat
    $conn = null;
}
?>

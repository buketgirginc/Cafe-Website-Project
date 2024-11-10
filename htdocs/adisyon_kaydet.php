<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'baglan.php';  // Veritabanı bağlantınızı sağlayan dosya

// POST verilerini al
$data = json_decode($_POST['orderData'], true);

if ($data && is_array($data)) {
    // Adisyon ID'sini oluşturun (örn: rastgele veya oturum tabanlı)
    $adisyon_id = time() . '-' . rand(1000, 9999);

    // Veritabanı işlemi için başla
    $conn->beginTransaction();

    try {
        foreach ($data as $order) {
            if (isset($order['id'], $order['quantity'], $order['price'], $order['total'])) {
                $urun_id = $order['id'];
                $adet = $order['quantity'];
                $fiyat = $order['price'];
                $toplam_fiyat = $order['total'];
                $tarih = date('Y-m-d H:i:s');  // Şu anki tarihi alır

                // Ürün var mı kontrol et ve varsa miktarı güncelle
                $checkSql = "SELECT * FROM adisyon_urunleri WHERE adisyon_id = :adisyon_id AND urun_id = :urun_id";
                $checkStmt = $conn->prepare($checkSql);
                $checkStmt->bindParam(':adisyon_id', $adisyon_id);
                $checkStmt->bindParam(':urun_id', $urun_id);
                $checkStmt->execute();

                if ($checkStmt->rowCount() > 0) {
                    // Eğer ürün varsa, mevcut miktarı güncelle
                    $updateSql = "UPDATE adisyon_urunleri SET adet = adet + :adet, toplam_fiyat = toplam_fiyat + :toplam_fiyat WHERE adisyon_id = :adisyon_id AND urun_id = :urun_id";
                    $updateStmt = $conn->prepare($updateSql);
                    $updateStmt->bindParam(':adisyon_id', $adisyon_id);
                    $updateStmt->bindParam(':urun_id', $urun_id);
                    $updateStmt->bindParam(':adet', $adet);
                    $updateStmt->bindParam(':toplam_fiyat', $toplam_fiyat);
                    if (!$updateStmt->execute()) {
                        throw new Exception('Sipariş güncellenemedi: ' . $updateStmt->errorInfo()[2]);
                    }
                } else {
                    // Eğer ürün yoksa, yeni sipariş ekle
                    $sql = "INSERT INTO adisyon_urunleri (adisyon_id, urun_id, adet, fiyat, toplam_fiyat, tarih) VALUES (:adisyon_id, :urun_id, :adet, :fiyat, :toplam_fiyat, :tarih)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':adisyon_id', $adisyon_id);
                    $stmt->bindParam(':urun_id', $urun_id);
                    $stmt->bindParam(':adet', $adet);
                    $stmt->bindParam(':fiyat', $fiyat);
                    $stmt->bindParam(':toplam_fiyat', $toplam_fiyat);
                    $stmt->bindParam(':tarih', $tarih);
                    if (!$stmt->execute()) {
                        throw new Exception('Sipariş kaydedilemedi: ' . $stmt->errorInfo()[2]);
                    }
                }
            } else {
                throw new Exception('Geçersiz veri formatı.');
            }
        }

        // Transaction başarılı
        $conn->commit();
        echo 'Sipariş başarıyla kaydedildi.';
    } catch (Exception $e) {
        // Transaction başarısız, geri al
        $conn->rollBack();
        echo 'Hata: ' . $e->getMessage();
    }
} else {
    echo 'Geçersiz veri.';
}

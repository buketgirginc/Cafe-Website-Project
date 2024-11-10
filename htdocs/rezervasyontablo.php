<?php
// Hata bildirimlerini aç
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'baglan.php'; // Veritabanı bağlantısını yap

// Rezervasyonları veritabanından çek
$sql = "SELECT * FROM rezervasyonlar ORDER BY id ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$rezervasyonlar = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rezervasyonlar</title>
    <!-- Font Awesome'ı ekleyin -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

       <style>
       @import url('https://fonts.googleapis.com/css2?family=Arima:wght@100..700&family=Kaisei+Opti:wght@400;500;700&display=swap');
        body {
            font-family: 'Kaisei Opti', sans-serif;
            background-color: #f5f5f5;
            color: #333;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

           h2 {
            margin-top: 0;
            font-size: 2rem;
            text-align: center;
            color: #333;
        }
        
        table {
            width: 100%;
            max-width: 1200px;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: white;
            border-radius: 10px;
        }

        table, th, td {
            border: 1px solid #ddd;
            text-align: center;
            padding: 12px;
        }

        th {
            background-color: #d16e6e;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

    </style>
    
<body>
    <h2>Rezervasyonlar</h2>
    
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Ad Soyad</th>
                <th>Telefon</th>
                <th>Kişi Sayısı</th>
                <th>Saat</th>
                <th>Mesaj</th>
                <th>Tarih</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rezervasyonlar as $rezervasyon): ?>
    <tr>
     <td><?php echo htmlspecialchars($rezervasyon['id']); ?></td>
        <td><?php echo htmlspecialchars($rezervasyon['ad_soyad']); ?></td>
        <td><?php echo htmlspecialchars($rezervasyon['telefon']); ?></td>
        <td><?php echo htmlspecialchars($rezervasyon['kisi_sayisi']); ?></td>
        <td><?php echo htmlspecialchars($rezervasyon['saat']); ?></td>
        <td><?php echo htmlspecialchars($rezervasyon['mesaj']); ?></td>
        <td><?php echo htmlspecialchars($rezervasyon['tarih']); ?></td>
        
    </tr>
    <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>


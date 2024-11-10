<?php
// Formdan gelen verileri al

$vt_sunucu="ftpupload.net";
$vt_kullanici="if0_37164226";
$vt_sifre="RQpo6QSZsj";
$vt_adi="if0_37164226_buketkafe";



$baglan=mysqli_connect($vt_sunucu,$vt_kullanici,$vt_sifre,$vt_adi);


if(!$baglan)
{
    die("Veri Tabanı Bağlantı İşlemi Başarısız".mysqli_connect_error());
}
$database = new mysqli($vt_sunucu, $vt_kullanici, $vt_sifre,$vt_adi);


// Bağlantıyı kontrol et
if ($database->connect_error) {
    die("Bağlantı hatası: " . $database->connect_error);
}


    
 

    $sql = "INSERT INTO rezervasyonlar (ad_soyad, telefon, kisi_sayisi, tarih,saat, mesaj) VALUES (?, ?, ?, ?, ?,?)";
    $stmt = $database->prepare($sql);
    if ($stmt) {
        // Veriyi bağla ve sorguyu çalıştır
        $stmt->bind_param("ssisss", $ad_soyad, $telefon, $kisi_sayisi, $tarih,$saat, $mesaj);
        if ($stmt->execute()) {
            echo "Randevu başarıyla oluşturuldu.";
        } else {
            echo "Oluşturma hatası: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Sorgu hatası: " . $database->error;
    }


    // Bağlantıyı kapat
    $database->close();

    
    
// ID'yi kontrol et

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Randevu Durumu - COFFY</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: black;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Yüksekliği tam sayfa yap */
        }

        .message-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            font-size: 2em; /* Başlık boyutu */
            margin: 0;
            color:white;
        }
        @media screen and (max-width: 1200px) {
            .menu-item {
                width: 200px;
            }

            h1 {
                font-size: 3em;
            }
        }

        @media screen and (max-width: 768px) {
            .menu-item {
                width: 100%;
                margin: 10px 0;
            }

            .menu-container {
                padding: 10px;
            }

            h1 {
                font-size: 2.5em;
            }

            #totalPrice {
                font-size: 1.5em;
            }
        }

        @media screen and (max-width: 480px) {
            h1 {
                font-size: 2em;
            }

            .btn-custom {
                padding: 5px 10px;
                font-size: 0.9em;
            }

            #totalPrice {
                font-size: 1.2em;
            }
        }
    </style>
</head>
<body>

<div class="message-container">
    <h1>Rezervasyonunuz yapılmıştır.</h1>
</div>
<div>

    
</body>
</html>

<!DOCTYPE html>

<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'baglan.php';
?>

<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">



<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>  
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>



    <title>Rezervasyon Oluştur</title>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Arima:wght@100..700&family=Kaisei+Opti:wght@400;500;700&display=swap');

        body {
            font-family: 'Kaisei Opti', sans-serif;
            backgraund:black;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 90vh; /* Yüksekliği tam sayfa yap */
        }
        .background-image {
    background-image: url('buketkafe.jpg'); /* Arka plan resmi URL'si */
    background-size: cover;
    background-position: center;
    height: 100vh; /* Sayfanın yarısına kadar */
    width: 100%;
    position: absolute;
    top: 0;
    left: 0;
    opacity: 1.2; /* Saydamlık oranı */
    z-index: -1; /* İçeriğin arkasında kalması için */
    filter: brightness(0.7); /* Parlaklığı kısar (0.5 = %50 parlaklık) */
}

        .randevu-container {
            background-color: #FFFEF2;
             font-family: "Arima", sans-serif;
             text-color: white;
            padding: 20px;
            border-radius: 20px;
            text-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 450px; /* Genişlik ayarı */
            text-align: center;
        }


.swal-title {
    color: black;,
     font-family: "Arima", sans-serif;
 
}

.swal-text {
    font-size: 16px;
 
    text-align: center;
}




        h1 {
            font-size: 2.1em; /* Başlık boyutu */
            margin-bottom: 20px;
           
             font-family: Kaisei Opti;
           
             color: #d87373; /* Yazı rengi */
        }

        input[type="text"],
        input[type="date"],
        input[type="time"],
         input[type="tel"],
        input[type="number"],
        textarea {
            width: 95%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
             font-family: "Arima", sans-serif;
              background-color: lightgray;
        }

        textarea {
            resize: none; /* Boyutlandırmayı kaldır */
            height: 90px; /* Yükseklik ayarı */
             background-color: lightgray;
        }

        input[type="submit"] {
            background-color: #d87373;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
             font-family: "Kaisei Opti", sans-serif;
            font-size: 1.2em;
            text-shadow: 2px 2px 5px rgba(255, 255, 255, 0.7); 
        }

        input[type="submit"]:hover {
            background-color: #62acca;
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

<div class="background-image"></div>





 <?php
if (isset($_POST['yorumkaydet'])) {


   function post($par, $st = false){

        if($st){

            return htmlspecialchars(addslashes(trim($_POST[$par])));

        } else {

            return htmlspecialchars(trim($_POST[$par]));

        }

    } 

$ad_soyad = post('ad_soyad');
$telefon = post('telefon');
$kisi_sayisi  = post('kisi_sayisi');
$saat  = post('saat');
$mesaj  = post('mesaj');
$tarih  = post('tarih');

   

   if (!$ad_soyad || !$kisi_sayisi ||  !$telefon  || !$saat  || !$tarih) {
        echo '<script>
            swal({
                title: "Hata",
                text: "Tüm alanlar dolu olmak zorundadır.",
                icon: "error",
                button: false
            });
        </script>';
    } elseif (strlen($ad_soyad) > 90) {
        echo '<script>
            swal({
                title: "Hata",
                text: "İsim ve soyisim 90 karakterden büyük olamaz.",
                icon: "error",
                button: false
            });
        </script>';
    } elseif (!preg_match('/^[a-zA-ZığüşöçĞÜŞİÖÇ\s]+$/u', $ad_soyad)) {
        echo '<script>
            swal({
                title: "Hata",
                text: "Ad ve soyad sadece Türkçe ve İngilizce harfler ve boşluk içerebilir.",
                icon: "error",
                button: false
            });
        </script>';
    } 
  elseif(strlen($telefon) != 10) {
    echo '<script>
    swal({
        title: "Hata",
        text: "Telefon Numarası 10 haneli olmalı.",
        icon: "error",
        button: false
    });
    </script>';
}







    else {
   $ayarekle = $conn->prepare("INSERT INTO rezervasyonlar SET
            ad_soyad = :ad_soyad, 
            telefon = :telefon, 
            saat = :saat, 
            kisi_sayisi = :kisi_sayisi,
            tarih = :tarih,
            mesaj = :mesaj"
        );

        $insert = $ayarekle->execute(array(
            'ad_soyad' => $ad_soyad,
            'telefon' => $telefon,
            'kisi_sayisi' => $kisi_sayisi,
            'saat' => $saat,
            'tarih' => $tarih,
            'mesaj' => $mesaj
        ));

        if ($insert) {
            echo '<script>
                swal({
                    title: "Başarılı",
                    text: "Rezervasyonunuz başarıyla oluşturulmuştur. İyi günler dileriz.",
                    icon: "success",
                }).then(function() {
                    // Başarılı olduğunda yapılacak işlemler buraya gelecek
                });
            </script>';
            
            echo '<script>
                if (window.history.replaceState) {
                    window.history.replaceState(null, null, window.location.href);
                }
            </script>';
        } else {
            echo '<script>
                swal({
                    title: "Hata",
                    text: "Sistemsel Bir Hata Mevcut. Lütfen daha sonra tekrar deneyiniz.",
                    icon: "error",
                    button: false
                }).then(function() {
                    // Hata olduğunda yapılacak işlemler buraya gelecek
                });
            </script>';
        }
    }
}
?>
















<div class="randevu-container">



    <h1>Rezervasyon Olustur</h1>








    <form  method="post" action="" >
    
    <input type="text" id="ad_soyad" name="ad_soyad" placeholder="Ad Soyad" required>
    
    
    <input type="number" id="telefon" name="telefon" pattern="\d{10}" placeholder="(5xx) xxx xx xx" required>
    
    
    <input type="number" id="kisi_sayisi" name="kisi_sayisi" placeholder="Kişi Sayısı" min="1" required>
    
    
    <input type="date" id="tarih" name="tarih" required>

  
    <input type="time" id="saat" name="saat" required>
    
    
    
    <textarea id="mesaj" name="mesaj" placeholder="Mesaj (isteğe bağlı)"></textarea>
    
    <input type="submit" name="yorumkaydet" value="Gönder">
    
</form>
    </form>
</div>

</body>
</html>

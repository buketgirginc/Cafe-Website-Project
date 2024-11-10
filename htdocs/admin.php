<?php
session_start();
// Oturum süresi (saniye cinsinden)
$timeout_duration = 1800;

// Son etkinlik zamanını kontrol et
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    // Oturum süresi doldu, oturumu bitir ve logout yapsın
    session_unset();
    session_destroy();
    header("Location: logout.php");
    exit();
}

// Son etkinlik zamanını güncelle
$_SESSION['last_activity'] = time();

// Eğer admin oturumu açılmamışsa login sayfasına yönlendir
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Örnek profil resmi ve admin ismi
$adminName = "Admin Sayfası";
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
     @import url('https://fonts.googleapis.com/css2?family=Arima:wght@100..700&family=Kaisei+Opti:wght@400;500;700&display=swap');

body {
    margin: 0;
    padding: 0;
    font-family: Arima, sans-serif;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background-image: url('buketkafe3.jpg'); /* Arka plan resmi */
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}

.sidebar {
    background-color: rgba(255, 255, 255, 0.9);
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    width: 300px;
    text-align: center;
}

.sidebar h3 {
    margin-bottom: 20px;
    color: dark blue;
    opacity: 1.5;
}

.sidebar a {
    text-decoration: none;
    color: rgba(255, 255, 255, 1);
    display: block;
    margin: 10px 0;
    padding: 10px;
    background-color: #d16e6e; /* Yeni arka plan rengi */
    border-radius: 5px;
    font-weight: bold;
    transition: background-color 0.3s;
}

.sidebar a:hover {
    background-color: #a64646; /* Hover rengi */
}

.sidebar .btn-logout {
    background-color: transparent; /* Buton görünümünü kaldırmak için */
    color: #e74c3c; /* Sadece kırmızı renkli yazı */
    padding: 0; /* İç boşlukları kaldır */
    margin: 10px 0;
    border-radius: 0;
    text-align: center;
    font-weight: bold;
}

.sidebar .btn-logout:hover {
    text-decoration: underline; /* Hover'da altını çiz */
    background-color: transparent; /* Arka plan rengini kaldır */
}

.timer {
    margin-top: 20px;
    font-size: 18px;
    font-weight: bold;
    color: green; /* Başlangıçta yeşil */
    background-color: #f0f0f0;
    padding: 10px;
    border-radius: 8px;
    position: relative;
    bottom: 0;
    width: 100%;
    box-sizing: border-box;
}


        /* Yanıp sönen efekt için */
        .blink {
            animation: blinker 1s linear infinite;
        }

        @keyframes blinker {
            50% {
                opacity: 0;
            }
        }
    </style>
    <script>
        var countdown = 1800;

        function startTimer() {
            var timer = setInterval(function() {
                countdown--;
                var minutes = Math.floor(countdown / 60);
                var seconds = countdown % 60;

                // Dakika ve saniye formatlarını 2 haneli yapmak için
                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                // Saati göster
                var timerElement = document.getElementById("timer");
                timerElement.innerHTML = "Otomatik Çıkış: " + minutes + ":" + seconds;

                // Son 1 dakika kaldığında rengi kırmızı yap ve yanıp sönme efektini ekle
                if (countdown <= 300) {
                    timerElement.style.color = "red";
                    timerElement.classList.add("blink");
                }

                if (countdown <= 0) {
                    clearInterval(timer);
                    // Otomatik çıkış
                    window.location.href = "logout.php";
                }
            }, 1000); // Her 1 saniyede bir sayaç güncellenir
        }

        window.onload = function() {
            startTimer();
        };
    </script>
</head>
<body>
    <div class="sidebar">
        <h3><?= $adminName ?></h3>
        <a href="masa.php">Masalara Eriş</a>
        <a href="urunler.php">Ürünlere Git</a>
        <a href="adisyonliste.php">Adisyonlara Git</a>
        <a href="rezervasyontablo.php">Rezervasyonlar</a>
        <a href="logout.php" class="btn-logout">Çıkış Yap</a>
        <div class="timer" id="timer">Otomatik Çıkış: 30:00</div>
    </div>
</body>
</html>
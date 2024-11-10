<?php
session_start(); // Oturumu başlat
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'baglan.php'; // Veritabanı bağlantısı

// Eğer oturumda 'adisyon_id' yoksa veya oturum süresi geçmişse, yeni bir adisyon oluştur
if (!isset($_SESSION['adisyon_id']) || (isset($_SESSION['adisyon_expire']) && time() > $_SESSION['adisyon_expire'])) {
    // Yeni bir adisyon kaydı oluştur
    $sql = "INSERT INTO adisyonlar (siparis_tarihi, toplam_tutar, odeme_durumu) VALUES (NOW(), 0, 0)";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Son eklenen adisyon_id'yi al ve oturuma ata
    $_SESSION['adisyon_id'] = $conn->lastInsertId();

    // Oturumun bitiş zamanını belirle (Örneğin 30 dakika sonra)
    $_SESSION['adisyon_expire'] = time() + 1800;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
    integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

  <title>KAFE</title>
  <link rel="stylesheet" href="css/style.css"> <!--css klasörümle htl arasında bağ kurmak için kullandım-->


  <link rel="stylesheet" href="owl/owl.carousel.min.css">
  <link rel="stylesheet" href="owl/owl.theme.default.min.css">
  <style>
   @import url('https://fonts.googleapis.com/css2?family=Kaisei+Opti:wght@400;500;700&display=swap');


    :root {
      /* css değişkenleri*/
      --main-color: #e6e6e6;
      --black-color: rgba(160, 128, 128);
      --border: 0.1rem solid rgba(214, 205, 205);
      /* 0.1 rem 1 pixel demek*/


    }

    * {
      /* '*' tüm html etiketlerine ulaşmayı sağlar*/
      font-family: 'Kaisei Opti', sans-serif;
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      outline: none;
      border: none;
      text-decoration: none;
      text-transform: capitalize;
      transition: 0, 2s ease;
      /* geçişlerin süresi*/
    }

    /*! base html codes*/

    html {
      font-size: 62.5%;
      /* normalde 1 rem 16 pixeldir burda 1 remi10px e eşitliyoruz*/
      overflow-x: hidden;
      scroll-padding-top: 9rem;
      scroll-behavior: smooth;
    }
    

    body {
      background-color: #fffef2;
    }

textarea {
    resize: none;
}

.text {
  color: white;
  font-size: 20px;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  -ms-transform: translate(-50%, -50%);
  white-space: nowrap;
}

@charset "UTF-8";

.svg-inline--fa {
  vertical-align: -0.200em;
}

.rounded-social-buttons {
padding: 30px;
  text-align: center;
  background:#d16e6e;
}

.rounded-social-buttons .social-button {
  display: inline-block;
  position: relative;
  cursor: pointer;
  width: 3.125rem;
  height: 3.125rem;
  border: 0.125rem solid transparent;
  padding: 0;
  text-decoration: none;
  text-align: center;
  color: #fefefe;
  font-size: 1.5625rem;
  font-weight: normal;
  line-height: 2em;
  border-radius: 1.6875rem;
  transition: all 0.5s ease;
  margin-right: 0.25rem;
  margin-bottom: 0.25rem;
}

.rounded-social-buttons .social-button:hover, .rounded-social-buttons .social-button:focus {
  -webkit-transform: rotate(360deg);
      -ms-transform: rotate(360deg);
          transform: rotate(360deg);
}

.rounded-social-buttons .fa-twitter, .fa-facebook-f, .fa-linkedin, .fa-tiktok, .fa-youtube, .fa-instagram {
  font-size: 25px;
}

.rounded-social-buttons .social-button.facebook {
  background: #3b5998;
}

.rounded-social-buttons .social-button.facebook:hover, .rounded-social-buttons .social-button.facebook:focus {
  color: #3b5998;
  background: #fefefe;
  border-color: #3b5998;
}

.rounded-social-buttons .social-button.twitter {
  background: #55acee;
}

.rounded-social-buttons .social-button.twitter:hover, .rounded-social-buttons .social-button.twitter:focus {
  color: #55acee;
  background: #fefefe;
  border-color: #55acee;
}

.rounded-social-buttons .social-button.linkedin {
  background: #007bb5;
}

.rounded-social-buttons .social-button.linkedin:hover, .rounded-social-buttons .social-button.linkedin:focus {
  color: #007bb5;
  background: #fefefe;
  border-color: #007bb5;
}

.rounded-social-buttons .social-button.tiktok {
  background: #000000;
}

.rounded-social-buttons .social-button.tiktok:hover, .rounded-social-buttons .social-button.tiktok:focus {
  color: #000000;
  background: #fefefe;
  border-color: #000000;
}

.rounded-social-buttons .social-button.youtube {
  background: #bb0000;
}

.rounded-social-buttons .social-button.youtube:hover, .rounded-social-buttons .social-button.youtube:focus {
  color: #bb0000;
  background: #fefefe;
  border-color: #bb0000;
}

.rounded-social-buttons .social-button.instagram {
  background: #125688;
}

.rounded-social-buttons .social-button.instagram:hover, .rounded-social-buttons .social-button.instagram:focus {
  color: #125688;
  background: #fefefe;
  border-color: #125688;
}
    

    section {
      padding: 3.5rem 7%;
      /*ana menü kısmındaki ortadaki yazıyı ortalamak  için kullandık*/
    }

    .btn {
      margin: 1rem;
      display: inline-block;
      padding: 2rem 3.75rem;
      /*ana menüdeki buton */
      border-radius: 30rem;
      font-size: 1.7rem;
      color: #fff;
      background-color: var(--black-color);
    }


    .logo img {
      height: 9rem;
    }

    #content {
      position: absolute;
      top: 50%;
      left: 40%;
      transform: translate(-50% -50%);
      
    }

    */ .search-input {
      font-size: 1.6rem;
      color: var(--black-color);
      /*FOOTER KISMI*/
      padding: 1rem;
      text-transform: none;
      border-radius: 7rem;
    }


    /* header start */
    .header.logo img {
      height: 10rem;
    }

    .header {
      background-color: #fff;
      display: flex;
      /*menü kısmını ortaladık */
      align-items: center;
      /* dikeyde hizaladık */
      justify-content: space-between;
      padding: 0 7%;
      margin: 2rem 7%;
      border-radius: 30rem;
      /* kenarları oval olması için */
      box-shadow: 0px 0px 17px -2px rgba(0, 0, 0, 0.75);
      /* gölgelik ekledim */
      position: sticky;
      /* aşağı inerken menü kkısmınında beraber inmesi için */
      top: 0;
      /* top 0 vermezsen ekran kaymaz */
      z-index: 1000;
      /* menü kısmının önünü bir şey kapatmaması için */
    }

    .header .navbar a {
      margin: 0 1rem;
      font-size: 1.8rem;
      color: #d16e6e;
      border-bottom: 0.1rem solid transparent;
    }

    .header .navbar .active,
    /* araya , koyup yazarsan iki koda da aşağıdakileri uygula anlamına gelir*/
    .header .navbar a:hover {
      /* her bir a etiketine hover yazmaktansa kısayol  */
      border-color: var(--main-color);
      padding-bottom: o.5rem;
    }



    /*home start */
    .home {
      min-height: 110vh;
      /* tarayıcıda gözüken yerlerin ölçüsü */
      background: url(buketkafe4.jpg) no-repeat;
      background-size: cover;
      background-position: center;
      margin-top: -14.5rem;
      display: flex;
      align-items: center;
    }

    .home .content {
      max-width: 60rem;
        position: relative;
  top: -45px; /* Yukarı kaydırma */
    display: inline-block;
    }

.home .content h3 {
      font-size: 6rem;
      color: #d16e6e;
    
    }

    .home .content p {
      font-size: 2rem;
      font-weight: 300;
      line-height: 1.8;
      color: black;
      background-color: #ebdfeb;
    padding: 2rem;
    border-radius: 10px;
    opacity: 0.9;
    }




    

    .container {
      width: 750px;
      height: auto;
      margin: auto;

    }

    .containerhk {
      width: auto;
      margin: auto;
    }


    .card {

      width: auto;
      height: auto;
      display: inline-block;
      border: 2px solid #fff;
      border-radius: 13px;
      box-shadow: 4px 7px 7px 0px;
      cursor: pointer;
      margin: 10px;
      margin-left: 50px;
      margin-top: 30px;
      transition: 400ms;
      text-align: center;

    }


    .baslikcard {
      font-size: 20px;
      color: #d87373;
    }

   

    .card:hover {
      border: 2px solid black;
    }

    .btn {
      background-color: #0e0e0e;
      color: #fff;
      text-decoration: none;
      font-size: 19px;
      margin: 100x 50px;
      display: inline-block;
      padding: 15px 30px;
    }

    .btn-btn {
      background-color: #0e0e0e;
      margin: 15px;
      color: #fff;
      text-decoration: none;
      font-size: 15px;
      margin: 100x 50px;
      display: inline-block;
      padding: 15px 30px;
      border-radius: 30rem;

    }

    .btn:hover {
      opacity: 0.7;
      color: #fff;
    }

    #kenarlik {
      border: 2px solid gray;
    }


  </style>

</head>

<body>
  <!--!  header section start-->
  <header class="header">
    <a href="#" class="logo">
      <img src="kafelogo.png" alt="logo">
    </a>
    <nav class="navbar">
      <a href="#" class="active">Ana Sayfa</a>
      <a href="menu.php" class="active">Menü</a>
      <a href="rezervasyon.php" class="active">Rezervasyon</a>

    </nav>



  </header>
  <!--!  header section end-->



<section class="home">
  <div class="content">
    <h3>KAFE</h3>
    <p>En Lezzetli İçecekler İçin Buradayız</p>
  </div>
</section>



<footer>
  <div class="rounded-social-buttons">
                    <a class="social-button facebook" href="https://www.facebook.com/" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a class="social-button twitter" href="https://www.twitter.com/" target="_blank"><i class="fab fa-twitter"></i></a>
                    <a class="social-button linkedin" href="https://www.linkedin.com/" target="_blank"><i class="fab fa-linkedin"></i></a>
                    <a class="social-button tiktok" href="https://www.tiktok.com/" target="_blank"><i class="fab fa-tiktok"></i></a>
                    <a class="social-button youtube" href="https://www.youtube.com/" target="_blank"><i class="fab fa-youtube"></i></a>
                    <a class="social-button instagram" href="https://www.instagram.com/" target="_blank"><i class="fab fa-instagram"></i></a>
                </div>
</footer>

</body>

</html>

   
   





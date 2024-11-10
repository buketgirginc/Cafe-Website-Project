
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'baglan.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kullanici_adi = $_POST['kullanici_adi'];
    $sifre = $_POST['sifre'];

    // SQL sorgusu
   $stmt = $conn->prepare("SELECT * FROM admin WHERE kullanici_adi = :kullanici_adi AND sifre = :sifre");
    $stmt->bindParam(':kullanici_adi', $kullanici_adi);
    $stmt->bindParam(':sifre', $sifre);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Başarılı giriş
        session_start();
        $_SESSION['admin'] = $kullanici_adi;
        header("Location: admin.php");
        exit();
    } else {
        // Başarısız giriş
        $hata = "Kullanıcı adı veya şifre yanlış!";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Admin Giriş</title>
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

.login-container {
    background-color: rgba(255, 255, 255, 0.9); /* Beyaz arka plan, biraz şeffaf */
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    width: 300px;
    text-align: center;
    opacity: 0.9;
}

.login-container h2 {
    margin-bottom: 20px;
    color: dark blue;
    opacity: 1.5;
}

.login-container input[type="text"],
.login-container input[type="password"] {
    width: 100%;
    padding: 10px;
    margin: 10px -10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-family: Arima, sans-serif;
}

.login-container input[type="submit"] {
    font-family: Arima, sans-serif;
    background-color: #5cb85c;
    color: #fff;
    border: none;
    padding: 10px;
    border-radius: 5px;
    cursor: pointer;
    width: 100%;
}

.login-container input[type="submit"]:hover {
    background-color: #4cae4c;
}

.error {
    color: red;
}
@media screen and (max-width: 600px) {
    .login-container {
        width: 90%; /* Küçük ekranlarda genişliği daha fazla doldurur */
        padding: 15px;
    }

    .login-container h2 {
        font-size: 1.5rem;
    }

    .login-container input[type="text"],
    .login-container input[type="password"] {
        padding: 8px;
        margin: 8px 0;
    }

    .login-container input[type="submit"] {
        padding: 8px;
    }
}
    </style>
</head>
<body>
  <div class="login-container">
    <h2>Admin Giriş</h2>
    <?php if(isset($hata)): ?>
        <p style="color:red;"><?php echo $hata; ?></p>
    <?php endif; ?>
    <form action="login.php" method="POST">
        <label for="kullanici_adi">Kullanıcı Adı:</label><br>
        <input type="text" id="kullanici_adi" name="kullanici_adi" required><br><br>
        <label for="sifre">Şifre:</label><br>
        <input type="password" id="sifre" name="sifre" required><br><br>
        <input type="submit" value="Giriş">
    </form>
     </div>
</body>
</html>

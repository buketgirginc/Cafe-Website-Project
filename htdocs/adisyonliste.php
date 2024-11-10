<?php
// Hata bildirimlerini aç
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'baglan.php'; // Veritabanı bağlantısını yap

// SQL sorgusu: Adisyon ürünleri ve ürün adları
$sql = "SELECT adisyon_urunleri.*, urunler.urun_adi 
        FROM adisyon_urunleri 
        LEFT JOIN urunler ON adisyon_urunleri.urun_id = urunler.urun_id
        ORDER BY adisyon_urunleri.adisyon_id ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Adisyonları organize etmek için bir dizi oluştur
$orders = array();
if (count($result) > 0) {
    foreach ($result as $row) {
        $orderId = $row['adisyon_id'];
        if (!isset($orders[$orderId])) {
            $orders[$orderId] = array();
        }
        $orders[$orderId][] = $row;
    }
} else {
    echo "Tablo boş.";
}

// Boş masaları al
$masalar_sql = "SELECT id FROM masalar WHERE status = 'empty'";
$masalar_stmt = $conn->prepare($masalar_sql);
$masalar_stmt->execute();
$bos_masalar = $masalar_stmt->fetchAll(PDO::FETCH_ASSOC);

// Adisyonların masaya atanıp atanmadığını kontrol etmek için bir dizi oluştur
$masaya_atanmis_adisyonlar = array();
$adisyonlar_sql = "SELECT DISTINCT adisyon_id FROM masalar WHERE adisyon_id IS NOT NULL";
$adisyonlar_stmt = $conn->prepare($adisyonlar_sql);
$adisyonlar_stmt->execute();
$masaya_atanmis_adisyonlar = $adisyonlar_stmt->fetchAll(PDO::FETCH_COLUMN);

// Veritabanı bağlantısını kapat
$conn = null;

// Mesajları yönet
$message = isset($_GET['message']) ? $_GET['message'] : '';
$error_message = isset($_GET['error_message']) ? $_GET['error_message'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adisyon Listesi</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Arima:wght@100..700&family=Kaisei+Opti:wght@400;500;700&display=swap');

        body {
            font-family: 'Kaisei Opti', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            line-height: 1.6;
            color: #333;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h2 {
            margin-top: 0;
            font-size: 2rem;
            text-align: center;
            color: #333;
        }

        .accordion-item {
            margin-bottom: 1rem;
        }

        .accordion {
            background-color: #d16e6e;
            color: #fff;
            cursor: pointer;
            padding: 1rem;
            border: none;
            border-radius: 8px;
            text-align: left;
            font-size: 1rem;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .accordion::after {
            content: '\002B'; /* Artı işareti */
            font-size: 1.25rem;
            transition: transform 0.3s ease;
        }

        .accordion.active::after {
            content: '\2212'; /* Eksi işareti */
            transform: rotate(180deg);
        }

        .accordion:hover {
            background-color: #a64646;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .panel {
            padding: 1rem;
            display: none;
            overflow: hidden;
            background-color: #f9f9f9;
            border-radius: 0 0 8px 8px;
            transition: max-height 0.3s ease;
            box-shadow: inset 0 -2px 4px rgba(0, 0, 0, 0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: #d16e6e;
            color: #fff;
        }

        th, td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        tbody tr:hover {
            background-color: #f1f1f1;
        }

        .dropdown {
            margin-top: 1rem;
        }

        .dropdown label {
            display: block;
            margin-bottom: 0.5rem;
        }

        .dropdown select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .mark-paid-btn {
            background-color: #4caf50;
            color: #fff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: bold;
            margin-top: 1rem;
            display: block;
            text-align: center;
        }

        .mark-paid-btn:hover {
            background-color: #45a049;
        }

        /* Modal CSS */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0, 0, 0, 0.4); /* Black background with opacity */
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Could be more or less, depending on screen size */
            border-radius: 8px;
            text-align: center;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .message {
            font-size: 1rem;
            margin: 0;
        }

        .error-message {
            color: #ff4d4d;
        }

        .success-message {
            color: #4caf50;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Adisyon Listesi</h2>

    <?php if (!empty($orders)): ?>
        <?php foreach ($orders as $adisyon_id => $items): ?>
            <div class="accordion-item">
                <button class="accordion">Adisyon Numarası: <?php echo htmlspecialchars($adisyon_id); ?></button>
                <div class="panel">
                    <table>
                        <thead>
                            <tr>
                                <th>Numara</th>
                                <th>Ürün Adı</th>
                                <th>Adet</th>
                                <th>Fiyat</th>
                                <th>Toplam Fiyat</th>
                                <th>Tarih</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['adisyon_id']); ?></td>
                                    <td><?php echo htmlspecialchars($item['urun_adi']); ?></td>
                                    <td><?php echo htmlspecialchars($item['adet']); ?></td>
                                    <td>₺<?php echo htmlspecialchars($item['fiyat']); ?></td>
                                    <td>₺<?php echo htmlspecialchars($item['toplam_fiyat']); ?></td>
                                    <td><?php echo htmlspecialchars($item['tarih']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <!-- Masa Ata Formu -->
                    <form method="POST" action="assign_table.php">
                        <input type="hidden" name="adisyon_id" value="<?php echo htmlspecialchars($adisyon_id); ?>">
                        <div class="dropdown">
                            <label for="masa">Masa Ata:</label>
                            <select name="masa_id" id="masa">
                                <option value="">Seçiniz</option>
                                <?php foreach ($bos_masalar as $masa): ?>
                                    <option value="<?php echo htmlspecialchars($masa['id']); ?>">
                                        Masa <?php echo htmlspecialchars($masa['id']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit">Ata</button>
                    </form>
                    <!-- Ödeme Yap Butonu -->
                    <?php if (in_array($adisyon_id, $masaya_atanmis_adisyonlar)): ?>
                        <form method="POST" action="mark_as_paid.php">
                            <input type="hidden" name="adisyon_id" value="<?php echo htmlspecialchars($adisyon_id); ?>">
                            <button type="submit" class="mark-paid-btn">Ödemeyi Al</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Adisyon bulunamadı.</p>
    <?php endif; ?>
</div>

<!-- Modal for messages -->
<div id="messageModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <p id="messageText" class="message"></p>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var accordions = document.getElementsByClassName("accordion");
        for (var i = 0; i < accordions.length; i++) {
            accordions[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var panel = this.nextElementSibling;
                if (panel.style.display === "block") {
                    panel.style.display = "none";
                } else {
                    panel.style.display = "block";
                }
            });
        }

        // Show message if present
        var message = "<?php echo addslashes($message); ?>";
        var error_message = "<?php echo addslashes($error_message); ?>";
        if (message || error_message) {
            var modal = document.getElementById("messageModal");
            var span = document.getElementsByClassName("close")[0];
            var messageText = document.getElementById("messageText");

            if (message) {
                messageText.textContent = message;
                messageText.classList.add("success-message");
            } else if (error_message) {
                messageText.textContent = error_message;
                messageText.classList.add("error-message");
            }

            modal.style.display = "block";

            span.onclick = function() {
                modal.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        }
    });
</script>

</body>
</html>

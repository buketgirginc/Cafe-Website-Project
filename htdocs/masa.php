<?php
// Hata bildirimlerini aç
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'baglan.php'; // Veritabanı bağlantısını yap

// Masaları çekme
$sql_masalar = "SELECT * FROM masalar";
$stmt_masalar = $conn->prepare($sql_masalar);
$stmt_masalar->execute();
$masalar = $stmt_masalar->fetchAll(PDO::FETCH_ASSOC);

// Adisyonları çekme
$sql_adisyonlar = "SELECT * FROM adisyonlar";
$stmt_adisyonlar = $conn->prepare($sql_adisyonlar);
$stmt_adisyonlar->execute();
$adisyonlar = $stmt_adisyonlar->fetchAll(PDO::FETCH_ASSOC);

// Veritabanı bağlantısını kapat
$conn = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masa Yönetimi</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Arima:wght@100..700&family=Kaisei+Opti:wght@400;500;700&display=swap');

        body {
            font-family: Kaisei Opti, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

 h2 {
            margin-top: 0;
            font-size: 2rem;
            text-align: center;
            color: #333;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

.table-box {
    display: flex;
    flex-wrap: wrap;
    gap: 10px; /* Kutucuklar arasındaki boşluk */
}

.table-box div {
    flex: 1 0 18%; /* Esnekliği ve genişlik oranını ayarlar */
    height: 100px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: #fff;
    border-radius: 5px;
    cursor: pointer;
    box-sizing: border-box; /* Padding ve border kutunun genişliğine dahil edilir */
    min-width: 150px; /* Minimum genişlik, kutucukların çok küçük olmamasını sağlar */
}

        .empty {
            background-color: #4caf50; /* Yeşil */
        }

        .occupied {
            background-color: #f44336; /* Kırmızı */
        }

        /* Modal stil */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
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

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #d16e6e;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Masa Yönetimi</h2>

    <div class="table-box">
        <?php foreach ($masalar as $masa): ?>
            <div 
                class="<?php echo $masa['status'] == 'empty' ? 'empty' : 'occupied'; ?>" 
                onclick="<?php echo $masa['status'] == 'occupied' ? 'showOrderDetails(' . $masa['adisyon_id'] . ')' : ''; ?>">
                Masa <?php echo $masa['id']; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modal -->
<div id="orderModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Adisyon Detayları</h2>
        <div id="orderDetails"></div>
    </div>
</div>

<script>
    // Modal elementleri
    var modal = document.getElementById("orderModal");
    var span = document.getElementsByClassName("close")[0];

    // Modal'ı göster
    function showOrderDetails(adisyon_id) {
        fetch('get_order_details.php?adisyon_id=' + adisyon_id)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    var details = '<table><thead><tr><th>Numara</th><th>Ürün Adı</th><th>Adet</th><th>Fiyat</th><th>Toplam Fiyat</th><th>Tarih</th></tr></thead><tbody>';
                    data.forEach(item => {
                        details += '<tr><td>' + item.adisyon_id + '</td><td>' + item.urun_adi + '</td><td>' + item.adet + '</td><td>₺' + item.fiyat + '</td><td>₺' + item.toplam_fiyat + '</td><td>' + item.tarih + '</td></tr>';
                    });
                    details += '</tbody></table>';
                    document.getElementById('orderDetails').innerHTML = details;
                    modal.style.display = "block";
                } else {
                    document.getElementById('orderDetails').innerHTML = 'Bu adisyonla ilgili bilgi bulunamadı.';
                    modal.style.display = "block";
                }
            })
            .catch(error => {
                document.getElementById('orderDetails').innerHTML = 'Bir hata oluştu: ' + error;
                modal.style.display = "block";
            });
    }

    // Modal'ı kapat
    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>

</body>
</html>

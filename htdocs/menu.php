<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'baglan.php';  // Veritabanı bağlantınızı sağlayan dosya

// Kategori 1'e ait ürünleri çekme sorgusu (Sıcak İçecekler)
$sql_hot = "SELECT urun_id, urun_adi, fiyat, kategori, resim_yolu FROM urunler WHERE kategori = '1' ORDER BY urun_id ASC";
$result_hot = $conn->query($sql_hot);

// Kategori 2'ye ait ürünleri çekme sorgusu (Soğuk İçecekler)
$sql_cold = "SELECT urun_id, urun_adi, fiyat, kategori, resim_yolu FROM urunler WHERE kategori = '2' ORDER BY urun_id ASC";
$result_cold = $conn->query($sql_cold);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS bağlantı linki-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="css/menustyle.css">
    <script src="menujavascript.js" defer></script>
    <!-- Font Awesome bağlantı linki-->
    <script src="https://kit.fontawesome.com/7c04c97d59.js" crossorigin="anonymous"></script>
    <title>Menü</title>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="header">
                <h1>Menü</h1>
                <p>Hoş geldiniz!</p>
            </div>
            
            <h2>Sıcak İçecekler</h2>
            <div class="row content">
                <?php
                if ($result_hot && $result_hot->rowCount() > 0) {
                    while ($urun = $result_hot->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                            <div class="card">
                                <img src="<?php echo $urun['resim_yolu']; ?>" alt="<?php echo $urun['urun_adi']; ?>" class="card-img-top">
                                <div class="card-body">
                                    <h3><?php echo $urun['urun_adi']; ?></h3>
                                    <ul>
                                        <li><?php echo $urun['fiyat']; ?>₺</li>
                                    </ul>
                                    <input type="checkbox" id="<?php echo $urun['urun_id']; ?>Checkbox" name="items[<?php echo $urun['urun_adi']; ?>][selected]" style="display: none;">
                                    <div class="quantity-control" style="display: none;">
                                        <button type="button" class="btn btn-secondary" onclick="decreaseQuantity('<?php echo $urun['urun_id']; ?>Qty')">-</button>
                                        <span id="<?php echo $urun['urun_id']; ?>Qty">0</span>
                                        <button type="button" class="btn btn-secondary" onclick="increaseQuantity('<?php echo $urun['urun_id']; ?>Qty')">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p>Kategori 1'e ait ürün bulunamadı.</p>";
                }
                ?>
            </div>

            <hr>

            <h2>Soğuk İçecekler</h2>
            <div class="row content">
                <?php
                if ($result_cold && $result_cold->rowCount() > 0) {
                    while ($urun = $result_cold->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                            <div class="card">
                                <img src="<?php echo $urun['resim_yolu']; ?>" alt="<?php echo $urun['urun_adi']; ?>" class="card-img-top">
                                <div class="card-body">
                                    <h3><?php echo $urun['urun_adi']; ?></h3>
                                    <ul>
                                        <li><?php echo $urun['fiyat']; ?>₺</li>
                                    </ul>
                                    <input type="checkbox" id="<?php echo $urun['urun_id']; ?>Checkbox" name="items[<?php echo $urun['urun_adi']; ?>][selected]" style="display: none;">
                                    <div class="quantity-control" style="display: none;">
                                        <button type="button" class="btn btn-secondary" onclick="decreaseQuantity('<?php echo $urun['urun_id']; ?>Qty')">-</button>
                                        <span id="<?php echo $urun['urun_id']; ?>Qty">0</span>
                                        <button type="button" class="btn btn-secondary" onclick="increaseQuantity('<?php echo $urun['urun_id']; ?>Qty')">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p>Kategori 2'ye ait ürün bulunamadı.</p>";
                }
                ?>
            </div>

            <div id="totalPrice" class="mt-3">
                Toplam: <span id="totalAmount">0</span> ₺
            </div>
            <div class="content">
            <button type="button" class="btn btn-custom" type="submit" onclick="submitOrder()">Siparişi Gönder</button>
            </div>
        </div>
    </div>

    <script>
        let cart = [];

        document.addEventListener('DOMContentLoaded', function() {
            calculateTotal();
        });

        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('click', function (event) {
                if (event.target.tagName === 'BUTTON' || event.target.tagName === 'SPAN') {
                    return;
                }

                const checkbox = this.querySelector('input[type="checkbox"]');
                checkbox.checked = !checkbox.checked;
                this.classList.toggle('selected', checkbox.checked);

                const quantityControl = this.querySelector('.quantity-control');
                const quantityElement = this.querySelector('.quantity-control span');

                if (checkbox.checked) {
                    quantityControl.style.display = 'flex';
                    quantityElement.innerText = '1';
                } else {
                    quantityControl.style.display = 'none';
                    quantityElement.innerText = '0';
                }

                calculateTotal();
            });
        });

        function decreaseQuantity(id) {
            const qtyElement = document.getElementById(id);
            let qty = parseInt(qtyElement.innerText);
            if (qty > 0) qty--;
            qtyElement.innerText = qty;
            calculateTotal();
        }

        function increaseQuantity(id) {
            const qtyElement = document.getElementById(id);
            let qty = parseInt(qtyElement.innerText);
            qty++;
            qtyElement.innerText = qty;
            calculateTotal();
        }

        function calculateTotal() {
            let total = 0;
            cart = [];
            document.querySelectorAll('.card').forEach(card => {
                const checkbox = card.querySelector('input[type="checkbox"]');
                const qtyElement = card.querySelector('.quantity-control span');
                if (checkbox.checked) {
                    const price = parseFloat(card.querySelector('li').innerText.replace('₺', ''));
                    const qty = parseInt(qtyElement.innerText);
                    total += price * qty;
                    cart.push({
                        id: card.querySelector('input[type="checkbox"]').id.replace('Checkbox', ''),
                        quantity: qty,
                        price: price,
                        total: (price * qty).toFixed(2)
                    });
                }
            });
            document.getElementById('totalAmount').innerText = total.toFixed(2);
        }

      // Sepeti sunucuya gönder
function submitOrder() {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'adisyon_ekle.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.status === 'success') {
                alert('Sipariş başarıyla eklendi!');
                // Seçilen ürünleri temizle
                document.querySelectorAll('.card').forEach(card => {
                    card.querySelector('input[type="checkbox"]').checked = false;
                    card.querySelector('.quantity-control').style.display = 'none';
                    card.querySelector('.quantity-control span').innerText = '0';
                });
                document.getElementById('totalAmount').innerText = '0';
            } else {
                alert('Bir hata oluştu: ' + response.message);
            }
        } else {
            alert('Bir hata oluştu.');
        }
    };
    xhr.send(JSON.stringify(getOrderData()));
}

function getOrderData() {
    let orders = [];
    document.querySelectorAll('.card').forEach(card => {
        const checkbox = card.querySelector('input[type="checkbox"]');
        if (checkbox.checked) {
            const qtyElement = card.querySelector('.quantity-control span');
            const qty = parseInt(qtyElement.innerText);
            const price = parseFloat(card.querySelector('li').innerText.replace('₺', ''));
            const id = checkbox.id.replace('Checkbox', '');
            orders.push({
                id: id,
                quantity: qty,
                price: price,
                total: price * qty
            });
        }
    });
    return orders;
}
    </script>
    <a href="#" class="to-top">
        <i class="fas fa-chevron-up"></i>
    </a>
</body>
</html>

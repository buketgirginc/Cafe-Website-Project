<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'baglan.php';  // Veritabanı bağlantısını sağlayan dosya

// Ürünleri çekme sorgusu
$sql = "SELECT * FROM urunler ORDER BY urun_id ASC";
$result = $conn->query($sql);
// Ürün ekleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addProduct'])) {
    $urun_adi = $_POST['urun_adi'];
    $fiyat = $_POST['fiyat'];
    $kategori = $_POST['kategori'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["resim_yolu"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Dosya tipi kontrolü
    if ($imageFileType === "jpg") {
        if (move_uploaded_file($_FILES["resim_yolu"]["tmp_name"], $target_file)) {
            $sqlInsert = "INSERT INTO urunler (urun_adi, fiyat, kategori, resim_yolu) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sqlInsert);
            $stmt->execute([$urun_adi, $fiyat, $kategori, $target_file]);
        } else {
            echo "Dosya yüklenirken bir hata oluştu.";
        }
    } else {
        echo "Sadece JPG formatında resim yükleyebilirsiniz.";
    }
    header("Location: " . $_SERVER['PHP_SELF']);  // Sayfayı yenile
}
// Ürün düzenleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateProduct'])) {
    $urun_id = $_POST['urun_id'];
    $urun_adi = $_POST['urun_adi'];
    $fiyat = $_POST['fiyat'];
    $kategori = $_POST['kategori'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["resim_yolu"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Dosya tipi kontrolü
    if ($imageFileType === "jpg" || empty($_FILES["resim_yolu"]["name"])) {
        if (empty($_FILES["resim_yolu"]["name"])) {
            // Resim seçilmemişse sadece diğer alanlar güncellenecek
            $sqlUpdate = "UPDATE urunler SET urun_adi = ?, fiyat = ?, kategori = ? WHERE urun_id = ?";
            $stmt = $conn->prepare($sqlUpdate);
            $stmt->execute([$urun_adi, $fiyat, $kategori, $urun_id]);
        } else {
            // Resim seçilmişse
            if (move_uploaded_file($_FILES["resim_yolu"]["tmp_name"], $target_file)) {
                $sqlUpdate = "UPDATE urunler SET urun_adi = ?, fiyat = ?, kategori = ?, resim_yolu = ? WHERE urun_id = ?";
                $stmt = $conn->prepare($sqlUpdate);
                $stmt->execute([$urun_adi, $fiyat, $kategori, $target_file, $urun_id]);
            } else {
                echo "Dosya yüklenirken bir hata oluştu.";
            }
        }
        header("Location: " . $_SERVER['PHP_SELF']);  // Sayfayı yenile
    } else {
        echo "Sadece JPG formatında resim yükleyebilirsiniz.";
    }
}

// Ürün silme işlemi
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sqlDelete = "DELETE FROM urunler WHERE urun_id = ?";
    $stmt = $conn->prepare($sqlDelete);
    $stmt->execute([$delete_id]);
    header("Location: " . $_SERVER['PHP_SELF']);  // Sayfayı yenile
}

// Ürün bilgilerini çekme
$editProduct = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $sqlEdit = "SELECT urun_id, urun_adi, fiyat, kategori, resim_yolu FROM urunler WHERE urun_id = ?";
    $stmt = $conn->prepare($sqlEdit);
    $stmt->execute([$edit_id]);
    $editProduct = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürün Tablosu</title>
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

        button {
            margin: 10px;
            font-family: Kaisei Opti;
            padding: 10px 20px;
            background-color: #d16e6e;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #a64646;
        }
.modal {
    display: none; /* Varsayılan olarak gizli */
    position: fixed;
    z-index: 1000; /* Modal pencerelerinin diğer içeriklerin üzerinde görünmesini sağlar */
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto; /* İçeriğin taşmasını önler */
    background-color: rgba(0, 0, 0, 0.4); /* Arka planın yarı şeffaf olması */
}

/* Modal içeriği */
.modal-content {
    background-color: #fefefe;
    margin: 5% auto; /* Ortalanmış modal içeriği */
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 500px;
    border-radius: 10px;
    position: relative; /* İçerikteki kapatma düğmesinin konumlandırılması için */
}

/* Kapatma düğmesi */
.close, .close-edit {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

        .close:hover, .close-edit:hover,
        .close:focus, .close-edit:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-delete {
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 8px 12px;
            cursor: pointer;
        }

        .btn-delete:hover {
            background-color: #c0392b;
        }

        .btn-edit {
            background-color: #2ecc71;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 8px 12px;
            cursor: pointer;
        }

        .btn-edit:hover {
            background-color: #27ae60;
        }

        .btn-icon {
            margin: 0 5px;
            display: inline-flex;
            align-items: center;
        }
    </style>
</head>
<body>
<h2>Ürün Tablosu</h2>
    <!-- Ekle Butonu -->
    <button id="openModalBtn">Ürün Ekle</button>

   <!-- Ürün Ekle Modal -->
<div id="addProductModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="urun_adi">Ürün Adı:</label>
            <input type="text" name="urun_adi" required><br><br>
            <label for="fiyat">Fiyat:</label>
            <input type="number" step="0.01" name="fiyat" required><br><br>
            <label for="kategori">Kategori Seç:</label>
            <select name="kategori" required>
                <option value="1">Sıcak İçecekler</option>
                <option value="2">Soğuk İçecekler</option>
            </select><br><br>
            <label for="resim_yolu">Resim Yükle (JPG):</label>
            <input type="file" name="resim_yolu" accept=".jpg" required><br><br>
            <button type="submit" name="addProduct">Ürünü Ekle</button>
        </form>
    </div>
</div>

    <!-- Düzenleme Modal -->
    <div id="editProductModal" class="modal">
        <div class="modal-content">
            <span class="close-edit">&times;</span>
            <?php if ($editProduct): ?>
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="urun_id" value="<?php echo htmlspecialchars($editProduct['urun_id']); ?>">
                    <label for="urun_adi_edit">Ürün Adı:</label>
                    <input type="text" id="urun_adi_edit" name="urun_adi" value="<?php echo htmlspecialchars($editProduct['urun_adi']); ?>" required><br><br>
                    <label for="fiyat_edit">Fiyat:</label>
                    <input type="number" id="fiyat_edit" name="fiyat" value="<?php echo htmlspecialchars($editProduct['fiyat']); ?>" step="0.01" required><br><br>
                    <label for="kategori_edit">Kategori Seç:</label>
                    <select id="kategori_edit" name="kategori" required>
                        <option value="1" <?php if ($editProduct['kategori'] == 1) echo 'selected'; ?>>Sıcak İçecekler</option>
                        <option value="2" <?php if ($editProduct['kategori'] == 2) echo 'selected'; ?>>Soğuk İçecekler</option>
                    </select><br><br>
                    <label for="resim_yolu_edit">Resim Yükle (JPG):</label>
                    <input type="file" id="resim_yolu_edit" name="resim_yolu" accept=".jpg"><br><br>
                    <button type="submit" name="updateProduct">Güncelle</button>
                </form>
            <?php else: ?>
                <p>Ürün bilgileri yüklenirken bir hata oluştu.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Ürün Listesi -->
    <table>
        <tr>
            <th>Ürün ID</th>
            <th>Ürün Adı</th>
            <th>Fiyat</th>
            <th>Kategori</th>
            <th>Resim</th>
            <th>İşlemler</th>
        </tr>
        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['urun_id']); ?></td>
            <td><?php echo htmlspecialchars($row['urun_adi']); ?></td>
            <td><?php echo htmlspecialchars($row['fiyat']); ?> TL</td>
            <td><?php echo htmlspecialchars($row['kategori']); ?></td>
            <td>
                <?php if (!empty($row['resim_yolu'])): ?>
                    <img src="<?php echo htmlspecialchars($row['resim_yolu']); ?>" alt="Resim" width="100">
                <?php endif; ?>
            </td>
            <td>
                <a href="?edit_id=<?php echo htmlspecialchars($row['urun_id']); ?>" class="btn-edit btn-icon"><i class="fas fa-edit"></i></a>
                <a href="?delete_id=<?php echo htmlspecialchars($row['urun_id']); ?>" class="btn-delete btn-icon"><i class="fas fa-trash"></i></a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <script>
        // Modal Açma/Kapama
        var addProductModal = document.getElementById("addProductModal");
        var openModalBtn = document.getElementById("openModalBtn");
        var closeModalSpanAdd = document.getElementsByClassName("close")[0];
        var editProductModal = document.getElementById("editProductModal");
        var closeModalSpanEdit = document.getElementsByClassName("close-edit")[0];

        // Ekle Modalını Açma
        openModalBtn.onclick = function() {
            addProductModal.style.display = "block";
        }

        // Ekle Modalını Kapatma
        closeModalSpanAdd.onclick = function() {
            addProductModal.style.display = "none";
        }

        // Modalı Kapatma (Ekranın herhangi bir yerine tıklanırsa)
        window.onclick = function(event) {
            if (event.target === addProductModal) {
                addProductModal.style.display = "none";
            }
            if (event.target === editProductModal) {
                editProductModal.style.display = "none";
            }
        }

        // Düzenleme Modalını Açma
        <?php if ($editProduct): ?>
            window.onload = function() {
                editProductModal.style.display = "block";
            }
        <?php endif; ?>

        // Düzenleme Modalını Kapatma
        closeModalSpanEdit.onclick = function() {
            editProductModal.style.display = "none";
            window.location.href = window.location.pathname; // Edit işlemi sonrasında sayfayı yenile
        }
    </script>
</body>
</html>

<?php 
    require "koneksi.php";

    // Query untuk mendapatkan kategori
    $queryKategori = mysqli_query($con, "SELECT * FROM kategori");

    // Get produk berdasarkan keyword
    if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
        $keyword = mysqli_real_escape_string($con, $_GET['keyword']); // Sanitasi input
        $queryProduk = mysqli_query($con, "SELECT * FROM produk WHERE nama LIKE '%$keyword%'");
    }
    // Get produk berdasarkan kategori
    else if (isset($_GET['kategori']) && !empty($_GET['kategori'])) {
        $kategori = mysqli_real_escape_string($con, $_GET['kategori']); // Sanitasi input
        $queryGetKategoriId = mysqli_query($con, "SELECT id FROM kategori WHERE Nama = '$kategori'");

        if (mysqli_num_rows($queryGetKategoriId) > 0) {
            $kategoriId = mysqli_fetch_array($queryGetKategoriId);
            $queryProduk = mysqli_query($con, "SELECT * FROM produk WHERE kategori_id = '$kategoriId[id]'");
        } else {
            $queryProduk = mysqli_query($con, "SELECT * FROM produk WHERE 0"); // Jika kategori tidak ditemukan
        }
    }
    // Get produk default
    else {
        $queryProduk = mysqli_query($con, "SELECT * FROM produk");
    }

    $countData = mysqli_num_rows($queryProduk);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Online | Produk</title>
    <link rel="stylesheet" href="bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php require "navbar.php"; ?>

    <div class="container-fluid banner-produk d-flex align-items-center">
        <div class="container">
            <h1 class="text-white text-center">Produk</h1>
        </div>
    </div>

    <!-- Body -->
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-3 mb-5">
                <h3>Kategori</h3>
                <ul class="list-group">
                    <?php while ($kategori = mysqli_fetch_array($queryKategori)) { ?>
                        <a class="no-decoration" href="produk.php?kategori=<?php echo $kategori['Nama']; ?>">
                            <li class="list-group-item"><?php echo $kategori['Nama']; ?></li>
                        </a>
                    <?php } ?>
                </ul>
            </div>
            <div class="col-lg-9">
                <h3 class="text-center mb-3">Produk</h3>
                <div class="row">
                        <?php 
                            if($countData<1){
                        ?>
                            <h4 class="text-center">Produk yang anda cari tidak tersedia</h4>
                        <?php 
                            }
                        ?>
                    <?php if (mysqli_num_rows($queryProduk) > 0) { ?>
                        <?php while ($produk = mysqli_fetch_array($queryProduk)) { ?>
                            <div class="col-md-4 mb-4">
                                <div class="card align-items-center">
                                    <div class="image-box">
                                        <img src="image/<?php echo $produk['foto']; ?>" class="card-img-top" alt="<?php echo $produk['nama']; ?>">
                                    </div>
                                    <div class="card-body text-center">
                                        <h5 class="card-title"><?php echo $produk['nama']; ?></h5>
                                        <p class="card-text"><?php echo $produk['detail']; ?></p>
                                        <p class="card-text text-harga">Rp <?php echo number_format($produk['harga'], 0, ',', '.'); ?></p>
                                        <a href="detail-produk.php?nama=<?php echo urlencode($produk['nama']); ?>" class="btn warna2 text-purple">Lihat Detail</a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <p class="text-center">Produk tidak ditemukan.</p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <?php require "footer.php"; ?>

    <script src="bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
    <script src="fontawesome/js/all.min.js"></script>
</body>
</html>

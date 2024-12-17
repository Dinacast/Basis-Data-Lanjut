<?php 
    require "session.php"; 
    require "../koneksi.php";

    $queryKategori = mysqli_query($con, "SELECT * FROM kategori");
    $jumlahKategori = mysqli_num_rows($queryKategori);

    $queryProduk = mysqli_query($con, "SELECT * FROM produk");
    $jumlahProduk = mysqli_num_rows($queryProduk);
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="../bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome/css/fontawesome.min.css">
</head>

<style>
    .kotak {
        border: solid;
    }

    .summary-kategory {
        background-color: pink;
        border-radius: 15px;
    }

    .summary-produk {
        background-color: skyblue;
        border-radius: 15px;
    }

</style>

<body>
    <?php require "navbar.php"; ?>
    <div class="container mt-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <i class="fas fa-home"></i> Home
                </li>
            </ol>
    </nav>
    <h2>Halo <?php echo $_SESSION['username']; ?></h2>
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-4 col-mt-6 col-12 mb-3">
                <div class="summary-kategory p-3">
                    <div class="row">
                        <div class="col-6">
                        <i class="fa-solid fa-align-justify fa-5x text-white"></i>
                        </div>
                        <div class="col-6 text-white">
                            <h3 class="fs-2">Kategori</h3>
                            <p class="fs-4"><?php echo $jumlahKategori; ?> Kategori</p>
                            <p><a href="kategori.php" class="text-white">Lihat Detail</a></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-mt-6 col-12 mb-3">
                <div class="summary-produk p-3">
                    <div class="row">
                        <div class="col-6">
                        <i class="fa-brands fa-shopify fa-5x"></i>
                        </div>
                        <div class="col-6 text-black">
                            <h3 class="fs-2">Produk</h3>
                            <p class="fs-4"><?php echo $jumlahProduk; ?> Produk</p>
                            <p><a href="produk.php" class="text-black">Lihat Detail</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script src="../bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
    <script src="../fontawesome/js/all.min.js"></script>
</body>
</html>


<?php 
    require "session.php"; 
    require "../koneksi.php";

    $query = mysqli_query($con, "SELECT a.*, b.nama AS nama_kategori FROM produk a JOIN kategori b ON a. kategori_id=b.id");
    $jumlahProduk = mysqli_num_rows($query);

    $queryKategori = mysqli_query($con, "SELECT * FROM kategori");

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk</title>
    <link rel="stylesheet" href="../bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome/css/fontawesome.min.css">
</head>

<style>
    .no-decoration {
        text-decoration: none;
    }
    form div {
        margin-bottom: 10px;
    }
</style>

<body>
    <?php require "navbar.php"; ?>

    <div class="container mt-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../adminpanel" class="no-decoration text-muted"><i class="fas fa-home"></i> Home</a></li>
                <li class="breadcrumb-item active" aria-current="page"><i class="fa-brands fa-shopify"></i> Produk</li>
            </ol>
        </nav>

        <!-- Form Tambah Produk -->
        <div class="row mt-5 col-12 col-md-6">
            <h3>Tambah Produk</h3>
            <form action="" method="post" enctype="multipart/form-data">
                <div>
                    <label for="nama">Nama</label>
                    <input type="text" name="nama" id="nama" class="form-control" autocomplete="off">
                </div>
                <div>
                    <label for="kategori">Kategori</label>
                    <select name="kategori" id="kategori" class="form-control">
                        <option value="">Pilih</option>
                        <?php 
                            while ($data = mysqli_fetch_array($queryKategori)) {
                        ?>
                            <option value="<?php echo $data['id']; ?>"><?php echo $data['Nama']; ?></option>
                        <?php
                            }
                        ?>
                    </select>
                </div>
                <div>
                    <label for="harga">Harga</label>
                    <input type="number" name="harga" id="harga" class="form-control">
                </div>
                <div>
                    <label for="foto">Foto</label>
                    <input type="file" name="foto" id="foto" class="form-control">
                </div>
                <div>
                    <label for="detail">Detail</label>
                    <textarea name="detail" id="detail" cols="30" rows="10" class="form-control"></textarea>
                </div>
                <div>
                    <label for="ketersediaan_stok">Ketersediaan Stok</label>
                    <select name="ketersediaan_stok" id="ketersediaan_stok" class="form-control">
                        <option value="tersedia">Tersedia</option>
                        <option value="habis">Habis</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary" name="simpan">Simpan</button>
                </div>
            </form>

            <?php
if (isset($_POST['simpan'])) {
    $nama = htmlspecialchars($_POST['nama']);
    $kategori = htmlspecialchars($_POST['kategori']);
    $harga = htmlspecialchars($_POST['harga']);
    $detail = htmlspecialchars($_POST['detail']);
    $ketersediaan_stok = htmlspecialchars($_POST['ketersediaan_stok']);

    $target_dir = "../image/";
    $nama_file = basename($_FILES["foto"]["name"]);
    $imageFileType = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
    $image_size = $_FILES["foto"]["size"];

    if ($nama == "" || $kategori == "" || $harga == "") {
        echo '<div class="alert alert-warning mt-3">Nama, Kategori, dan Harga wajib diisi!</div>';
    } else {
        if ($nama_file != '') {
            if ($image_size > 500000) {
                echo '<div class="alert alert-warning mt-3">File tidak boleh lebih dari 500KB!</div>';
            } elseif (!in_array($imageFileType, ['jpg', 'png', 'gif', 'jpeg'])) {
                echo '<div class="alert alert-warning mt-3">File wajib bertipe jpg, png, jpeg atau gif!</div>';
            } else {
                // Tetapkan path lengkap dengan nama asli
                $target_file = $target_dir . $nama_file;

                // Cek apakah file dengan nama yang sama sudah ada
                if (file_exists($target_file)) {
                    echo '<div class="alert alert-warning mt-3">File dengan nama yang sama sudah ada!</div>';
                } else {
                    if (!move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                        echo '<div class="alert alert-danger mt-3">Gagal mengupload file!</div>';
                    } else {
                        $queryTambah = mysqli_query($con, "INSERT INTO produk (kategori_id, nama, harga, foto, detail, ketersediaan_stok) VALUES ('$kategori', '$nama', '$harga', '$nama_file', '$detail', '$ketersediaan_stok')");

                        if ($queryTambah) {
                            echo '<div class="alert alert-success mt-3">Produk berhasil tersimpan!</div>';
                            echo '<meta http-equiv="refresh" content="2; url=produk.php">';
                        } else {
                            echo '<div class="alert alert-danger mt-3">Gagal menyimpan produk: ' . mysqli_error($con) . '</div>';
                        }
                    }
                }
            }
        } else {
            echo '<div class="alert alert-warning mt-3">Foto wajib diupload!</div>';
        }
    }
}
?>

        </div>

        <!-- List Produk -->
        <div class="mt-3 mb-5">
            <h2>List Produk</h2>
            <div class="table-responsive mt-5">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Ketersediaan Stok</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            if ($jumlahProduk == 0) {
                        ?>
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data</td>
                            </tr> 
                        <?php
                            } else {
                                $jumlah = 1;
                                while ($data = mysqli_fetch_array($query)) {
                        ?>
                            <tr>
                                <td><?php echo $jumlah; ?></td>
                                <td><?php echo $data['nama']; ?></td>
                                <td><?php echo $data['nama_kategori']; ?></td>
                                <td><?php echo $data['harga']; ?></td>
                                <td><?php echo $data['ketersediaan_stok']; ?></td>
                                <td>
                                    <a href="detail-produk.php?p=<?php echo $data['id']; ?>" 
                                    class="btn btn-info"><i class="fa-solid fa-circle-info"></i></a>
                                </td>
                            </tr>
                        <?php
                                    $jumlah++;
                                }
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="../bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
    <script src="../fontawesome/js/all.min.js"></script>
</body>
</html>

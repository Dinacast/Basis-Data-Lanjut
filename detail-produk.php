<?php 
    require "session.php"; 
    require "../koneksi.php";

    $id = htmlspecialchars($_GET['p']); 

    $query = mysqli_query($con, "SELECT a.*, b.nama AS nama_kategori 
        FROM produk a 
        JOIN kategori b ON a.kategori_id = b.id 
        WHERE a.id = '$id'");

    if (!$query || mysqli_num_rows($query) == 0) {
        die('<div class="alert alert-danger mt-3" role="alert">Data produk tidak ditemukan!</div>');
    }

    $data = mysqli_fetch_array($query);

    $queryKategori = mysqli_query($con, "SELECT * FROM kategori WHERE id != '$data[kategori_id]'");

    if (!$queryKategori) {
        die('<div class="alert alert-danger mt-3" role="alert">Gagal mendapatkan data kategori!</div>');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk</title>
    <link rel="stylesheet" href="../bootstrap-5.3.3-dist/css/bootstrap.min.css">
</head>
<style>
    form div {
        margin-bottom: 10px;
    }
</style>
<body>
    <?php require "navbar.php"; ?>
    <div class="container mt-5">
        <h2>Detail Produk</h2>
        <div class="col-12 col-md-6 mb-5">
            <form action="" method="post" enctype="multipart/form-data">
                <div>
                    <label for="nama">Nama</label>
                    <input type="text" name="nama" id="nama" value="<?php echo htmlspecialchars($data['nama']); ?>" class="form-control" autocomplete="off">
                </div>
                <div>
                    <label for="kategori">Kategori</label>
                    <select name="kategori" id="kategori" class="form-control">
                        <option value="<?php echo $data['kategori_id']; ?>"><?php echo $data['nama_kategori']; ?></option>
                        <?php while ($dataKategori = mysqli_fetch_array($queryKategori)) { ?>
                            <option value="<?php echo $dataKategori['id']; ?>"><?php echo $dataKategori['Nama']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div>
                    <label for="harga">Harga</label>
                    <input type="number" class="form-control" value="<?php echo $data['harga']; ?>" name="harga" required>
                </div>
                <div>
                    <label for="currentFoto">Foto Produk</label>
                    <img src="../image/<?php echo $data['foto']; ?>" alt="" width="200px">
                </div>
                <div>
                    <label for="foto">Foto</label>
                    <input type="file" name="foto" id="foto" class="form-control">
                </div>
                <div>
                    <label for="detail">Detail</label>
                    <textarea name="detail" id="detail" class="form-control" cols="30"><?php echo htmlspecialchars($data['detail']); ?></textarea>
                </div>
                <div>
                    <label for="ketersediaan_stok">Ketersediaan Stok</label>
                    <select name="ketersediaan_stok" id="ketersediaan_stok" class="form-control">
                        <option value="<?php echo $data['ketersediaan_stok']; ?>"><?php echo $data['ketersediaan_stok']; ?></option>
                        <?php if ($data['ketersediaan_stok'] == 'tersedia') { ?>
                            <option value="habis">Habis</option>
                        <?php } else { ?>
                            <option value="tersedia">Tersedia</option>
                        <?php } ?>
                    </select>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary" name="simpan">Simpan</button>
                    <button type="submit" class="btn btn-danger" name="hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">Hapus</button>
                </div>
            </form>

            <?php
                // Logika untuk simpan perubahan
                if (isset($_POST['simpan'])) {
                    // Simpan perubahan produk (sama seperti kode Anda sebelumnya)
                }

                // Logika untuk hapus produk
                if (isset($_POST['hapus'])) {
                    $fotoPath = "../image/" . $data['foto'];

                    // Hapus file foto jika ada
                    if (file_exists($fotoPath)) {
                        unlink($fotoPath);
                    }

                    // Hapus data produk dari database
                    $queryHapus = mysqli_query($con, "DELETE FROM produk WHERE id = '$id'");

                    if ($queryHapus) {
                        echo '<div class="alert alert-success mt-3">Produk berhasil dihapus!</div>';
                        echo '<meta http-equiv="refresh" content="2; url=produk.php">';
                    } else {
                        echo '<div class="alert alert-danger mt-3">Gagal menghapus produk: ' . mysqli_error($con) . '</div>';
                    }
                }
            ?>
        </div>
    </div>
    <script src="../bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
</body>
</html>

<?php 
    require "session.php"; 
    require "../koneksi.php";

    $id = $_GET['p'];
    $query = mysqli_query($con, "SELECT * FROM kategori WHERE id = '$id'");
    $data = mysqli_fetch_array($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kategori</title>
    <link rel="stylesheet" href="../bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome/css/fontawesome.min.css">
</head>
<body>
    <?php require "navbar.php"; ?>
    <div class="container mt-5">
        <h2>Detail Kategori</h2>
        <div class="col-12 col-md-6">
            <form action="" method="post">
                <div>
                    <label for="kategori">Kategori</label>
                    <input type="text" id="kategori" name="kategori" class="form-control" value="<?php echo htmlspecialchars($data['Nama']); ?>">
                </div>
                <div class="mt-5 d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary" name="editBtn">Edit</button>
                    <button type="submit" class="btn btn-danger" name="deleteBtn">Delete</button>
                </div>
            </form>

            <?php 
                if (isset($_POST['editBtn'])) {
                    $kategori = htmlspecialchars($_POST['kategori']);

                    if ($data['Nama'] == $kategori) {
                        echo "<div class='alert alert-info'>Kategori tidak diubah.</div>";
                        echo "<meta http-equiv='refresh' content='2; url=kategori.php' />";
                    } else {
                        // Cek apakah kategori sudah ada
                        $cekKategori = mysqli_query($con, "SELECT * FROM kategori WHERE Nama = '$kategori'");
                        $jumlahData = mysqli_num_rows($cekKategori);

                        if ($jumlahData > 0) {
                            echo "<div class='alert alert-warning'>Kategori sudah ada!</div>";
                        } else {
                            $stmt = $con->prepare("UPDATE kategori SET Nama = ? WHERE id = ?");
                            $stmt->bind_param("si", $kategori, $id);
                            $stmt->execute();

                            if ($stmt) {
                                echo "<div class='alert alert-success'>Kategori berhasil diupdate!</div>";
                                echo "<meta http-equiv='refresh' content='2; url=kategori.php' />";
                            } else {
                                echo "<div class='alert alert-danger'>Terjadi kesalahan: " . $con->error . "</div>";
                            }
                        }
                    }
                }
                if(isset($_POST['deleteBtn'])){
                    $queryCheck = mysqli_query($con, "SELECT * FROM produk WHERE kategori_id = '$id'");
                    $dataCount = mysqli_num_rows($queryCheck);
                    if ($dataCount > 0) {
                        ?>
                       <div class="alert alert-warning mt-3" role="alert">
                                Kategori tidak dapat dihapus karena sudah digunakan diproduk
                            </div>
                        <?php 
                        die();
                    }
                    $queryDelete = mysqli_query($con, "DELETE FROM kategori WHERE id='$id'");

                    if($queryDelete){
                        ?>
                            <div class="alert alert-primary mt-3" role="alert">
                                Kategori Berhasil diHapus
                            </div>
                            <meta http-equiv="refresh" content="0; url=kategori.php" />
                        <?php
                    }
                    else{
                        echo mysqli_error($con);
                    }
                }
            ?>
        </div>
    </div>
    <script src="../bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
</body>
</html>
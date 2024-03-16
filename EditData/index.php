<?php 
require '../DatabaseConn/DatabaseConn.php';

$id = $_GET["id"];

// Data yang mau diedit kembalikan value
$profil = query("SELECT * FROM profil WHERE id = $id")[0];

function upload() {
    $namafile = $_FILES['photo_profile']['name'];
    $errorfile = $_FILES['photo_profile']['error'];
    $ukuranfile = $_FILES['photo_profile']['size'];
    $tmpfile = $_FILES['photo_profile']['tmp_name'];

    // Cek apakah file sudah diupload dengan benar
    if ($errorfile === 4) {
        echo "<script>alert('File belum diunggah.');</script>";
        return false;
    }

    // Cek ekstensi file
    $valid_extensions = array('jpg', 'jpeg', 'png', 'gif');
    $file_extension = strtolower(pathinfo($namafile, PATHINFO_EXTENSION));
    if (!in_array($file_extension, $valid_extensions)) {
        echo "<script>alert('Hanya file gambar yang diperbolehkan.');</script>";
        return false;
    }

    // Cek ukuran file (maksimum 2 MB)
    $max_file_size = 2 * 1024 * 1024; // 2 MB dalam bytes
    if ($ukuranfile > $max_file_size) {
        echo "<script>alert('Ukuran file melebihi batas maksimum 2 MB.');</script>";
        return false;
    }

    // Generate nama unik untuk file baru
    $random_filename = uniqid() . '.' . $file_extension;

    // Lokasi penyimpanan file
    // $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/msib/pasien_php/img/";
    $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/img/";
    $target_file = $target_dir . $random_filename;

    // Pindahkan file ke lokasi penyimpanan
    if (move_uploaded_file($tmpfile, $target_file)) {
        // Jika berhasil upload, kembalikan nama file baru
        return $random_filename;
    } else {
        // Jika gagal upload, tampilkan pesan kesalahan
        echo "<script>alert('Gagal mengunggah file.');</script>";
        return false;
    }
}

function editData($post) {
    global $conn;

    // Ambil data dari setiap form 
    $id = $post["idPasien"];
    $nama = htmlspecialchars($post["nama"]);
    $tanggal_lahir = htmlspecialchars($post["tanggal_lahir"]);
    $alamat = htmlspecialchars($post["alamat"]);
    $keluhan = htmlspecialchars($post["keluhan"]);

    // Dapatkan nama file gambar lama
    $profil = query("SELECT * FROM profil WHERE id = $id")[0];
    $namafile_lama = $profil["photo_profile"];

    // Jika ada file yang diunggah, lakukan proses upload
    $photo_profile = $_FILES['photo_profile']['name'] ? upload() : null;

    // Jika upload gagal, atau pengguna tidak mengunggah file baru, gunakan foto profil yang lama
    if ($photo_profile === false) {
        return 0;
    } elseif ($photo_profile === null) {
        $query = "UPDATE profil SET nama ='$nama', tanggal_lahir='$tanggal_lahir',alamat='$alamat',keluhan='$keluhan' WHERE id = $id ";
    } else {
        // Hapus gambar lama jika ada
        if (!empty($namafile_lama)) {
            // $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/msib/pasien_php/img/";
            $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/img/";
            $path_gambar_lama = $target_dir . $namafile_lama;
            if (file_exists($path_gambar_lama)) {
                unlink($path_gambar_lama);
            }
        }

        $query = "UPDATE profil SET nama ='$nama', tanggal_lahir='$tanggal_lahir',alamat='$alamat',keluhan='$keluhan',photo_profile='$photo_profile' WHERE id = $id ";
    }

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}


if (isset($_POST["edit"])) {
    // Cek apakah data berhasil diubah atau tidak
    if (editData($_POST) > 0) {
        echo "
            <script>
                alert('Data berhasil diubah!');
                document.location.href='../';
            </script>
        ";  
    } else {
        echo "
            <script>
                alert('Data gagal diubah!');
                document.location.href='../index.php';
            </script>
        ";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Halaman Edit Data</title>

    <!-- ico -->
    <link rel="icon" href="../img/rumahsakit.ico" type="image/x-icon">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md6">
                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="../">DATA PASIEN</a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="../">Pasien</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" aria-current="page" href="../AddData/">Tambah Data</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row mt-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <strong>EDIT DATA PASIEN</strong>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="idPasien" value="<?= $profil["id"] ?>">

                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" id="nama" name="nama" class="form-control mb-3" required value="<?= $profil["nama"] ?>">

                            <div class="row">
                                <div class="col-md-6">
                                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-control mb-3" value="<?= $profil["tanggal_lahir"] ?>">
                                </div>
                                
                                <div class="col-md-6">
                                    <img src="../img/<?= $profil["photo_profile"] ?>" alt="" width="200">
                                    <div class="form-group mb-3">
                                        <label for="photoProfile" class="form-label">Upload Foto Profile</label>
                                        <input class="form-control" type="file" id="photoProfile" name="photo_profile">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="alamat">Alamat</label>
                                <textarea class="form-control" name="alamat" id="alamat" rows="3" required><?= $profil["alamat"] ?></textarea>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="keluhan">Keluhan</label>
                                <textarea class="form-control" name="keluhan" id="keluhan" rows="3" required><?= $profil["keluhan"] ?></textarea>
                            </div>

                            <button type="submit" name="edit" class="btn btn-primary btn-lg">Edit Data</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>

<?php 
require 'DatabaseConn/DatabaseConn.php';

$pasiens = query("SELECT * FROM profil");
$hasil = query("SELECT COUNT(*) AS total FROM profil");
$jumlah_pasien = count($pasiens);
// tombol cari
function cariData($keyword){
  
    $query= "SELECT * FROM profil WHERE
  
        nama LIKE '%$keyword%'
         
      ";
  
  return query($query);
}

// mengecek ketika tombol cari sudah ditekan
if (isset($_GET["cari"])) {
    
    $pasiens = cariData($_GET["keyword"]);
    $jumlah_pasien = count($pasiens);
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <title>Halaman Pasien</title>

    <!-- ico -->
    <link rel="icon" href="img/rumahsakit.ico" type="image/x-icon">
  </head>
  <body>
    <div class="container">
        <div class="row">
            <div class="col-md6">
                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="">DATA PASIEN</a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="">Pasien</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" aria-current="page" href="AddData/">Tambah Data</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>

    <div class="container">
    <div class="row col-md-4">
    </div>
    <h3 class="mt-3" >TABEL PASIEN</h3>
        
    <div class="row mt-3">
        <div class="col-md">
            <h5>Hasil : <?= $jumlah_pasien ?></h5>
            <table class="table table-hover" id="example">
                <thead>
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                        <?php $i=0; foreach($pasiens as $pasien): ?>
                            <tr>
                                <th scope="row"><?= ++$i ?></th>
                                <th scope="row"><?= $pasien["nama"] ?></th>
                                <td>
                                    <a href="Detail/?id=<?= $pasien["id"] ?>"><span class="badge bg-success text-light">Detail</span></a>
                                    <a href="EditData/?id=<?= $pasien["id"] ?>"><span class="badge bg-warning text-light">Edit</span></a>
                                    <a href="Delete/?id=<?= $pasien["id"] ?>" class="tombol-hapus"><span class="badge bg-danger text-light" onclick="return confirm('Yakin hapus ?')">Hapus</span></a>
                                </td>
                            </tr>
                        <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
</div>




    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
</body>
</html>
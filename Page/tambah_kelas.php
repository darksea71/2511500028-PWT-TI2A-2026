<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Data Kelas</h1>
                </div>
            </div>
        </div>
    </div>
    <?php
    //kode otomatis - menggunakan int increment
    $carikode = mysqli_query($koneksi, "select max(id_kelas) from kelas") or die (mysqli_error());
    $datakode = mysqli_fetch_array($carikode);
    if($datakode[0]) {
        $kode = (int) $datakode[0];
        $kode = $kode + 1;
        $hasilkode = $kode;
    } else { $hasilkode = 1; }
    $_SESSION["KODE"] = $hasilkode;

    if(isset($_POST['tambah'])){
        $id_kelas = $_POST['id_kelas'];
        $nm_kelas = $_POST['nm_kelas'];

        if (empty($nm_kelas)) {
            echo '<div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-warning"></i> Peringatan </h5>
            <h4>Nama Kelas tidak boleh kosong!</h4></div>';
        } else {
            $insert = mysqli_query($koneksi, "INSERT INTO kelas (id_kelas, nm_kelas) values ($id_kelas,'$nm_kelas')");
            if ($insert) {
                echo '<div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-info"></i> Info </h5>
                <h4>Berhasil Disimpan</h4></div>';
                echo '<meta http-equiv="refresh" content="1;url=index.php?page=kelas">';
            }else{
                echo '<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-error"></i> Error </h5>
                <h4>Gagal Disimpan: ' . mysqli_error($koneksi) . '</h4></div>';
            }
        }
    }
    ?>
    <section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="card-body p-2">
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="id_kelas">ID Kelas</label>
                            <input type="text" name="id_kelas" value="<?= htmlspecialchars($hasilkode); ?>" placeholder="Id Kelas" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for="nm_kelas">Nama Kelas</label>
                            <input type="text" name="nm_kelas" id="nm_kelas" placeholder="Nama Kelas" class="form-control">
                        </div>
                        <div class="card-footer">
                            <input type="submit" class="btn btn-primary" name="tambah" value="Simpan">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
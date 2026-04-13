<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Edit Mata Pelajaran</h1>
            </div>
        </div>
    </div>
</div>

    <?php
    $kd = $_GET['kd'];
    $edit = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM mapel WHERE kd_mapel='$kd' "));

    if(isset($_POST['tambah'])){
        $kd_mapel = $_POST['kd_mapel'];
        $nm_mapel = $_POST['nm_mapel'];
        $kkm = $_POST['kkm'];

        $insert = mysqli_query($koneksi, "UPDATE mapel SET nm_mapel='$nm_mapel', kkm='$kkm' WHERE kd_mapel='$kd_mapel' ");
        
        if ($insert) {
            echo '<div class="alert alert-info alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-info"></i> Info </h5>
            <h4>Berhasil Disimpan</h4></div>';
            echo '<meta http-equiv="refresh" content="1;url=index.php?page=mapel">';
        } else {
            echo '<div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-info"></i> Info </h5>
            <h4>Gagal Disimpan</h4></div>';
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
                                <label for="kd_mapel">Kode Mapel</label>
                                <input type="text" name="kd_mapel" value="<?= $edit['kd_mapel']; ?>" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="nm_mapel">Nama Mapel</label>
                                <input type="text" name="nm_mapel" value="<?= $edit['nm_mapel']; ?>" id="nm_mapel" placeholder="Nama mapel" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="kkm">KKM</label>
                                <input type="text" name="kkm" value="<?= $edit['kkm']; ?>" id="kkm" placeholder="KKM" class="form-control">
                            </div>
                            <div class="card-footer">
                                <input type="submit" class="btn btn-primary" name="tambah" value="simpan">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
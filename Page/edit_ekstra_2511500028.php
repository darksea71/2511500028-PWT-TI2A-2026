<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Edit Data Ekstrakurikuler</h1>
            </div>
        </div>
    </div>
</div>

    <?php
    $id = $_GET['id'];
    $edit = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM ekstra_2511500028 WHERE id_ekstra028=$id "));

    if(isset($_POST['tambah'])){
        $id_ekstra = $_POST['id_ekstra'];
        $nama_ekstra = $_POST['nama_ekstra'];
        $ket = $_POST['ket'];
        $semester = $_POST['semester'];
        $thn_ajaran = $_POST['thn_ajaran'];

        if (empty($nama_ekstra)) {
            echo '<div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-warning"></i> Peringatan </h5>
            <h4>Nama Ekstrakurikuler tidak boleh kosong!</h4></div>';
        } else {
            $insert = mysqli_query($koneksi, "UPDATE ekstra_2511500028 SET nama_ekstra028='$nama_ekstra', ket028='$ket', semester028='$semester', thn_ajaran028='$thn_ajaran' WHERE id_ekstra028=$id_ekstra ");
            
            if ($insert) {
                echo '<div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-info"></i> Info </h5>
                <h4>Berhasil Disimpan</h4></div>';
                echo '<meta http-equiv="refresh" content="1;url=index.php?page=ekstra_2511500028">';
            } else {
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
                                <label for="id_ekstra028">Kode Ekstrakurikuler</label>
                                <input type="text" name="id_ekstra" value="<?= $edit['id_ekstra028']; ?>" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="nama_ekstra028">Nama Ekstrakurikuler</label>
                                <input type="text" name="nama_ekstra" value="<?= $edit['nama_ekstra028']; ?>" id="nama_ekstra028" placeholder="Nama Ekstrakurikuler" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="ket028">Keterangan</label>
                                <input type="text" name="ket" value="<?= $edit['ket028']; ?>" id="ket028" placeholder="Keterangan" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="semester028">Semester</label>
                                <select name="semester" value="<?= $edit['semester028']; ?>" id="semester028" class="form-control">
                                    <option value="">Semester</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="thn_ajaran028">Tahun Ajaran</label>
                                <select name="thn_ajaran" value="<?= $edit['thn_ajaran028']; ?>" id="thn_ajaran028" class="form-control">
                                    <option value="">Tahun Ajaran</option>
                                    <option value="2025">2025</option>  
                                    <option value="2026">2026</option>
                                </select>
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
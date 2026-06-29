<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Edit Data Siswa</h1>
            </div>
        </div>
    </div>
</div>

    <?php
    $id = $_GET['id'];
    $edit = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa='$id' "));

    if(isset($_POST['tambah'])){
        $id_siswa = $_POST['id_siswa'];
        $nama_siswa = $_POST['nama_siswa'];
        $jenkel = $_POST['jenkel'];
        $kelas = $_POST['kelas'];
        $alamat = $_POST['alamat'];

        if (empty($nama_siswa)) {
            echo '<div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-warning"></i> Peringatan </h5>
            <h4>Nama Siswa tidak boleh kosong!</h4></div>';
        } else {
            $insert = mysqli_query($koneksi, "UPDATE siswa SET nama_siswa='$nama_siswa', jenkel='$jenkel', kelas='$kelas', alamat='$alamat' WHERE id_siswa='$id_siswa' ");
            
            if ($insert) {
                echo '<div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-info"></i> Info </h5>
                <h4>Berhasil Disimpan</h4></div>';
                echo '<meta http-equiv="refresh" content="1;url=index.php?page=siswa">';
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
                                <label for="id_siswa">ID Siswa</label>
                                <input type="text" name="id_siswa" value="<?= $edit['id_siswa']; ?>" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="nama_siswa">Nama Siswa</label>
                                <input type="text" name="nama_siswa" value="<?= $edit['nama_siswa']; ?>" id="nama_siswa" placeholder="Nama Siswa" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="kelas">Kelas</label>
                                <input type="text" name="kelas" value="<?= $edit['kelas']; ?>" id="kelas" placeholder="Kelas" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="jenkel">Jenis Kelamin</label>
                                <select name="jenkel" id="jenkel" class="form-control">
                                    <option selected disabled>
                                        -- Pilih Jenis Kelamin --
                                    </option>
                                    <option value="Laki-laki" <?php if($edit['jenkel'] == 'Laki-laki') echo 'selected'; ?>>Laki-laki</option>
                                    <option value="Perempuan" <?php if($edit['jenkel'] == 'Perempuan') echo 'selected'; ?>>Perempuan</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="kelas">Kelas</label>
                                <input type="text" name="kelas" value="<?= $edit['kelas']; ?>" id="kelas" placeholder="Kelas" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea name="alamat" id="alamat" placeholder="Alamat" class="form-control" rows="3"><?= $edit['alamat']; ?></textarea>
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

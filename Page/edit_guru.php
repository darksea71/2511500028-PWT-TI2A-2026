<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Edit Data Guru</h1>
            </div>
        </div>
    </div>
</div>

    <?php
    $kd = $_GET['kd'];
    $edit = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM guru WHERE kd_guru='$kd' "));

    if(isset($_POST['tambah'])){
        $kd_guru = $_POST['kd_guru'];
        $id_user = $_POST['id_user'];
        $nm_guru = $_POST['nm_guru'];
        $jenkel = $_POST['jenkel'];
        $pend_terakhir = $_POST['pend_terakhir'];
        $hp = $_POST['hp'];
        $alamat = $_POST['alamat'];

        if (empty($nm_guru)) {
            echo '<div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-warning"></i> Peringatan </h5>
            <h4>Nama Guru tidak boleh kosong!</h4></div>';
        } else {
            $insert = mysqli_query($koneksi, "UPDATE guru SET id_user='$id_user', nm_guru='$nm_guru', jenkel='$jenkel', pend_terakhir='$pend_terakhir', hp='$hp', alamat='$alamat' WHERE kd_guru='$kd_guru' ");
            
            if ($insert) {
                echo '<div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-info"></i> Info </h5>
                <h4>Berhasil Disimpan</h4></div>';
                echo '<meta http-equiv="refresh" content="1;url=index.php?page=guru">';
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
                                <label for="kd_guru">Kode Guru</label>
                                <input type="text" name="kd_guru" value="<?= $edit['kd_guru']; ?>" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="id_user">ID User</label>
                                <input type="text" name="id_user" value="<?= $edit['id_user']; ?>" id="id_user" placeholder="ID User" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="nm_guru">Nama Guru</label>
                                <input type="text" name="nm_guru" value="<?= $edit['nm_guru']; ?>" id="nm_guru" placeholder="Nama Guru" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="jenkel">Jenis Kelamin</label>
                                <select name="jenkel" id="jenkel" class="form-control">
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="Laki-laki" <?php if($edit['jenkel'] == 'Laki-laki') echo 'selected'; ?>>Laki-laki</option>
                                    <option value="Perempuan" <?php if($edit['jenkel'] == 'Perempuan') echo 'selected'; ?>>Perempuan</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="pend_terakhir">Pendidikan Terakhir</label>
                                <input type="text" name="pend_terakhir" value="<?= $edit['pend_terakhir']; ?>" id="pend_terakhir" placeholder="Pendidikan Terakhir" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="hp">No HP</label>
                                <input type="text" name="hp" value="<?= $edit['hp']; ?>" id="hp" placeholder="No HP" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea name="alamat" id="alamat" placeholder="Alamat" class="form-control" rows="3"><?= $edit['alamat']; ?></textarea>
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

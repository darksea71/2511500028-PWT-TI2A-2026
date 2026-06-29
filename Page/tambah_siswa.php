<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Tambah Data Siswa</h1>
                </div>
            </div>
        </div>
    </div>
    <?php
    //kode otomatis
    $carikode = mysqli_query($koneksi, "select max(id_siswa) from siswa") or die (mysqli_error());
    $datakode = mysqli_fetch_array($carikode);
    if($datakode[0]) {
        $nilaikode = substr($datakode[0], 3);
        $kode = (int) $nilaikode;
        $kode = $kode + 1;
        $hasilkode = "201".str_pad($kode, 3, "0", STR_PAD_LEFT);
    } else { $hasilkode ="201001"; }
    $_SESSION["KODE"] = $hasilkode;

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
            $insert = mysqli_query($koneksi, "INSERT INTO siswa (id_siswa, nama_siswa, jenkel, kelas, alamat) values ('$id_siswa','$nama_siswa','$jenkel','$kelas','$alamat')");
            if ($insert) {
                echo '<div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-info"></i> Info </h5>
                <h4>Berhasil Disimpan</h4></div>';
                echo '<meta http-equiv="refresh" content="1;url=index.php?page=siswa">';
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
                            <label for="id_siswa">ID Siswa</label>
                            <input type="text" name="id_siswa" value="<?= htmlspecialchars($hasilkode); ?>" placeholder="ID Siswa" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for="nama_siswa">Nama Siswa</label>
                            <input type="text" name="nama_siswa" id="nama_siswa" placeholder="Nama Siswa" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="kelas">Kelas</label>
                            <select name="kelas" id="kelas" class="form-control">
                                <option selected disabled>
                                    --Pilih Kelas--
                                </option>
                                <option value="7A">7A</option>
                                <option value="7B">7B</option>
                                <option value="7C">7C</option>
                                <option value="8A">8A</option>
                                <option value="8B">8B</option>
                                <option value="8C">8C</option>
                                <option value="9A">9A</option>
                                <option value="9B">9B</option>
                                <option value="9C">9C</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="jenkel">Jenis Kelamin</label>
                            <select name="jenkel" id="jenkel" class="form-control">
                                <option selected disabled>
                                    --Pilih Jenis Kelamin--
                                </option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea name="alamat" id="alamat" placeholder="Alamat" class="form-control" rows="2"></textarea>
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

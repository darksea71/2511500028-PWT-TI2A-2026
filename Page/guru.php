<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Data Guru</h1>
            </div>
        </div>
    </div>
</div>

<?php
if(isset($_GET['action'])) {
    if($_GET['action'] == "hapus") {
        $id = $_GET['id'];
        $query = mysqli_query($koneksi, "DELETE FROM guru where id_guru = '$id' ");
        if ($query) {
            echo '
            <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            Berhasil Di Hapus</div>';
            echo '<meta http-equiv="refresh" content="1;url=index.php?page=guru">';
        }
    }
}
?>
<div class="content">
    <div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <a href="index.php?page=tambah_guru" class="btn btn-primary btn-sm">Tambah Guru</a>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Guru</th>
                        <th>Nama Guru</th>
                        <th>Mapel</th>
                        <th>Jenis Kelamin</th>
                        <th>Pendidikan Terakhir</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <?php
                $no = 0;
                $query = mysqli_query($koneksi, "SELECT * FROM guru");
                while ($result = mysqli_fetch_array($query)) {
                    $no++;
                ?>
                <tbody>
                    <tr>
                        <td><?= $no; ?></td>
                        <td><?= $result['id_guru']; ?></td>
                        <td><?= $result['nama_guru']; ?></td>
                        <td><?= $result['mapel']; ?></td>
                        <td><?= $result['jenkel']; ?></td>
                        <td><?= $result['pend_terakhir']; ?></td>
                        <td><?= $result['alamat']; ?></td>
                        <td>
                            <a href="index.php?page=guru&action=hapus&id=<?= $result['id_guru'] ?>" title="">
                                <span class="badge badge-danger">Hapus</span>
                            </a>
                            <a href="index.php?page=edit_guru&id=<?= $result['id_guru'] ?>" title="">
                                <span class="badge badge-warning">Edit</span>
                            </a>
                        </td>
                    </tr>
                </tbody>
                <?php } ?>
            </table>
        </div>
    </div>
</div>

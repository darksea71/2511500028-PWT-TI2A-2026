<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Data Ekstrakurikuler</h1>
            </div>
        </div>
    </div>
</div>

<?php
if(isset($_GET['action'])) {
    if($_GET['action'] == "hapus") {
        $id = $_GET['id'];
        $query = mysqli_query($koneksi, "DELETE FROM ekstra_2511500028 where id_ekstra028 = $id ");
        if ($query) {
            echo '
            <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            Berhasil Di Hapus</div>';
            echo '<meta http-equiv="refresh" content="1;url=index.php?page=ekstra_2511500028">';
        }
    }
}
?>
<div class="content">
    <div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <a href="index.php?page=tambah_ekstra_2511500028" class="btn btn-primary btn-sm">Tambah Ekstrakurikuler</a>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>ID Ekstrakurikuler</th>
                        <th>Nama Ekstrakurikuler</th>
                        <th>Keterangan</th>
                        <th>Semester</th>
                        <th>Tahun Ajaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <?php
                $no = 0;
                $query = mysqli_query($koneksi, "SELECT * FROM ekstra_2511500028");
                while ($result = mysqli_fetch_array($query)) {
                    $no++;
                ?>
                <tbody>
                    <tr>
                        <td><?= $no; ?></td>
                        <td><?= $result['id_ekstra028']; ?></td>
                        <td><?= $result['nama_ekstra028']; ?></td>
                        <td><?= $result['ket028']; ?></td>
                        <td><?= $result['semester028']; ?></td>
                        <td><?= $result['thn_ajaran028']; ?></td>
                        <td>
                            <a href="index.php?page=ekstra_2511500028&action=hapus&id=<?= $result['id_ekstra028'] ?>" title="">
                                <span class="badge badge-danger">Hapus</span>
                            </a>
                            <a href="index.php?page=edit_ekstra_2511500028&id=<?= $result['id_ekstra028'] ?>" title="">
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
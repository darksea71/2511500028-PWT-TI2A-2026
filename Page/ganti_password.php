<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Ganti Password</h1>
            </div>
        </div>
    </div>
</div>

<?php
if (isset($_POST['ganti'])) {
    $password_lama = $_POST['password_lama'];
    $password_baru = $_POST['password_baru'];
    $konfirmasi = $_POST['konfirmasi'];

    $username = $_SESSION['Username'];

    // Ambil password lama dari database
    $query = mysqli_query($koneksi, "SELECT Password FROM users WHERE Username='$username'");
    $user = mysqli_fetch_array($query);

    if ($user) {
        if ($password_lama == $user['Password']) {
            if ($password_baru == $konfirmasi) {
                // Update password
                $update = mysqli_query($koneksi, "UPDATE users SET Password='$password_baru' WHERE Username='$username'");
                if ($update) {
                    echo '<div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-check"></i> Sukses</h5>
                        Password berhasil diubah.
                    </div>';
                } else {
                    echo '<div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-ban"></i> Gagal</h5>
                        Gagal mengubah password.
                    </div>';
                }
            } else {
                echo '<div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Peringatan</h5>
                    Password baru dan konfirmasi tidak cocok.
                </div>';
            }
        } else {
            echo '<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-ban"></i> Gagal</h5>
                Password lama salah.
            </div>';
        }
    } else {
        echo '<div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-ban"></i> Gagal</h5>
            User tidak ditemukan.
        </div>';
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
                            <label for="password_lama">Password Lama</label>
                            <input type="password" name="password_lama" id="password_lama" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="password_baru">Password Baru</label>
                            <input type="password" name="password_baru" id="password_baru" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="konfirmasi">Konfirmasi Password Baru</label>
                            <input type="password" name="konfirmasi" id="konfirmasi" class="form-control" required>
                        </div>
                        <div class="card-footer">
                            <input type="submit" class="btn btn-primary" name="ganti" value="Ganti Password">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
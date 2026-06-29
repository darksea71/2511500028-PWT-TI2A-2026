<?php
    include "config/koneksi.php";
    session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page dark-mode">
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>Sistem</b> Absensi</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session</p>

      <form action="#" method="post">
        <div class="input-group mb-3">
          <input type="text" name="username" id="username" class="form-control" placeholder="Username">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" id="password" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          
          <!-- /.col -->
          <div class="col-12">
            <input type="submit" name="Login" value="Login" class="btn btn-primary btn-block">
          </div>
          <!-- /.col -->
        </div>
      </form>
    </div>
    <!-- /.login-card-body -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>
<?php
if (isset($_POST['username'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username == true) {
        $userquery = mysqli_fetch_array(mysqli_query($koneksi, 
            "SELECT * FROM users WHERE username = '$username'"
        ));

        // var_dump($userquery); die;
        if ($userquery) {
            if ($password == $userquery['password']) {
                $_SESSION['level'] = $userquery['role'];
                $_SESSION['Username'] = $userquery['username'];

                if ($userquery['role'] == 'admin') {
                    header("location:index.php");
                } else if ($userquery['role'] == 'guru' || $userquery['role'] == 'siswa') {
                    if ($userquery['password'] == '1234') {
                        header("Location: index.php?page=ganti_password");
                    } else {
                        header("location:index.php");
                    }
                }
            }
        } else {
            echo '<div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                    <h5><i class="icon fas fa-ban"></i> Alert!</h5>
                    Login gagal
                  </div>';
        }
    }
}
?>
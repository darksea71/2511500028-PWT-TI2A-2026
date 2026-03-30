<?php
session_start();
include "config/koneksi.php";

if(isset($_POST['login'])){

    $Username = $_POST['Username'];
    $Password = $_POST['Password'];

    if(empty($Username) || empty($Password)){
        $error = "Data tidak boleh kosong!";
    } else {

        $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$Username' AND password='$Password'");

        if(!$query){
            die("Query Error: " . mysqli_error($koneksi));
        }

        if(mysqli_num_rows($query) > 0){

            $data = mysqli_fetch_array($query);

            $_SESSION['Username'] = $data['username'];
            $_SESSION['role'] = $data['role'];

            header("location:index.php");
            exit;

        } else {
            $error = "Username atau Password salah!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>

  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">

<div class="login-box">
  <div class="login-logo">
    <b>LOGIN</b>
  </div>

  <div class="card">
    <div class="card-body login-card-body">

      <p class="login-box-msg">Silakan Login</p>

      <?php if(isset($error)){ ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
      <?php } ?>

      <form method="post">
        <div class="input-group mb-3">
          <input type="text" name="Username" class="form-control" placeholder="Username">
        </div>

        <div class="input-group mb-3">
          <input type="password" name="Password" class="form-control" placeholder="Password">
        </div>

        <button type="submit" name="login" class="btn btn-primary btn-block">
          Login
        </button>
      </form>

    </div>
  </div>
</div>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>

</body>
</html>
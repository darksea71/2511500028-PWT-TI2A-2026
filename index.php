<?php
session_start();
require_once("config/koneksi.php");

// proteksi login
if(!isset($_SESSION['Username']) || !isset($_SESSION['role'])){
    header("location:login.php");
    exit;
}

$role = $_SESSION['role'];
$username = $_SESSION['Username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard</title>

  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">

<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
    </li>
  </ul>
</nav>

<!-- Sidebar -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="#" class="brand-link text-center">
    <span class="brand-text">Sistem Jadwal</span>
  </a>

  <div class="sidebar">
    <div class="user-panel mt-3 pb-3 mb-3 text-center">
      <div><?php echo $username; ?></div>
      <small>(<?php echo $role; ?>)</small>
    </div>

    <nav>
      <ul class="nav nav-pills nav-sidebar flex-column">

      <?php if($role == 'admin'){ ?>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-database"></i>
            <p>Master</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-calendar"></i>
            <p>Jadwal</p>
          </a>
        </li>

      <?php } elseif($role == 'guru'){ ?>
        <li class="nav-item">
          <a href="profil.php" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>Profil</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="kelas.php" class="nav-link">
            <i class="nav-icon fas fa-school"></i>
            <p>Kelas</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="jadwal.php" class="nav-link">
            <i class="nav-icon fas fa-calendar"></i>
            <p>Jadwal</p>
          </a>
        </li>

      <?php } elseif($role == 'siswa'){ ?>
        <li class="nav-item">
          <a href="profil.php" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>Profil</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="jadwal.php" class="nav-link">
            <i class="nav-icon fas fa-calendar"></i>
            <p>Jadwal</p>
          </a>
        </li>
      <?php } ?>

      <li class="nav-item">
        <a href="logout.php" class="nav-link">
          <i class="nav-icon fas fa-sign-out-alt"></i>
          <p>Logout</p>
        </a>
      </li>

      </ul>
    </nav>
  </div>
</aside>

<!-- Content -->
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <h1>
        <?php 
        if($role == 'admin') echo "Dashboard Admin";
        elseif($role == 'guru') echo "Dashboard Guru";
        elseif($role == 'siswa') echo "Dashboard Siswa";
        ?>
      </h1>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-body">
          Selamat datang <b><?php echo $username; ?></b> di sistem jadwal.
        </div>
      </div>
    </div>
  </div>
</div>

</div>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>

</body>
</html>
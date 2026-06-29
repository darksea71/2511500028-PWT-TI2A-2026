<?php

// ── Filter ────────────────────────────────────────────────────────────────────
$f_bulan = $_GET['bulan'] ?? date('Y-m');
$f_kelas = $_GET['kelas'] ?? '';
if (!preg_match('/^\d{4}-\d{2}$/', $f_bulan)) $f_bulan = date('Y-m');

$bulan_awal  = $f_bulan . '-01';
$bulan_akhir = date('Y-m-t', strtotime($bulan_awal));


$daftar_kelas = ['7A','7B','7C','8A','8B','8C','9A','9B','9C'];
?>

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6"><h1 class="m-0 text-dark">Rekap Absensi</h1></div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Rekap Absensi</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<section class="content">
<div class="container-fluid">

  <!-- FILTER -->
  <div class="card card-primary">
    <div class="card-header">
      <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filter Rekap</h3>
    </div>
    <form method="GET" action="">
      <input type="hidden" name="page" value="rekap_absensi">
      <div class="card-body">
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label>Bulan</label>
              <input type="month" name="bulan" class="form-control"
                     value="<?= htmlspecialchars($f_bulan) ?>" required>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label>Kelas <small class="text-muted">(opsional)</small></label>
              <select name="kelas" class="form-control">
                <option value="">-- Semua Kelas --</option>
                <?php foreach ($daftar_kelas as $k): ?>
                  <option value="<?= $k ?>" <?= $f_kelas === $k ? 'selected' : '' ?>><?= $k ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="col-md-2 d-flex align-items-end">
            <div class="form-group w-100">
              <button type="submit" class="btn btn-primary btn-block">
                <i class="fas fa-search"></i> Tampilkan
              </button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>

  <?php
  // ── QUERY SESI ────────────────────────────────────────────────────────────
  $kelas_sql   = $f_kelas !== '' ? "AND s.kelas = '" . mysqli_real_escape_string($koneksi, $f_kelas) . "'" : '';
  $bln_awal_e  = mysqli_real_escape_string($koneksi, $bulan_awal);
  $bln_akhir_e = mysqli_real_escape_string($koneksi, $bulan_akhir);

  $sql_sesi = "
    SELECT
      s.kelas,
      a.tanggal,
      a.jam_masuk,
      a.jam_pulang,
      a.id_guru,
      g.nama_guru,
      COUNT(a.id_absensi)                  AS total_siswa,
      SUM(a.status_kehadiran = 'Hadir')    AS jml_hadir,
      SUM(a.status_kehadiran = 'Sakit')    AS jml_sakit,
      SUM(a.status_kehadiran = 'Izin')     AS jml_izin,
      SUM(a.status_kehadiran = 'Alpha')    AS jml_alpha
    FROM absensi a
    JOIN siswa s ON s.id_siswa = a.id_siswa
    JOIN guru  g ON g.id_guru  = a.id_guru
    WHERE a.tanggal BETWEEN '$bln_awal_e' AND '$bln_akhir_e'
    $kelas_sql
    GROUP BY s.kelas, a.tanggal, a.id_guru, a.jam_masuk, a.jam_pulang
    ORDER BY s.kelas ASC, a.tanggal ASC, a.jam_masuk ASC, g.nama_guru ASC
  ";

  $res_sesi = mysqli_query($koneksi, $sql_sesi);

  if (!$res_sesi) {
    echo '<div class="alert alert-danger">Query error: ' . mysqli_error($koneksi) . '</div>';
  } elseif (mysqli_num_rows($res_sesi) === 0) {
    echo '<div class="alert alert-info"><i class="fas fa-info-circle mr-1"></i>
          Tidak ada data absensi untuk periode <strong>' . htmlspecialchars($f_bulan) . '</strong>
          ' . ($f_kelas ? 'kelas <strong>' . htmlspecialchars($f_kelas) . '</strong>' : '') . '.</div>';
  } else {
    // Kelompokkan per kelas
    $data_kelas = [];
    while ($row = mysqli_fetch_assoc($res_sesi)) {
      $data_kelas[$row['kelas']][] = $row;
    }

    $badge_map = ['Hadir'=>'success','Sakit'=>'warning','Izin'=>'info','Alpha'=>'danger'];

    foreach ($data_kelas as $kelas => $sesi_list):
  ?>

  <!-- ═══ CARD PER KELAS ═══════════════════════════════════════════════════ -->
  <div class="card card-info mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h3 class="card-title">
        <i class="fas fa-school mr-1"></i>
        Kelas <?= htmlspecialchars($kelas) ?>
        &mdash; <?= date('F Y', strtotime($bulan_awal)) ?>
      </h3>
      <span class="badge badge-light"><?= count($sesi_list) ?> Sesi</span>
    </div>

    <div class="card-body p-0">
      <!-- TABEL DAFTAR SESI -->
      <table class="table table-bordered table-hover mb-0" id="tbl-<?= $kelas ?>">
        <thead class="thead-dark">
          <tr>
            <th class="text-center" width="4%">No</th>
            <th width="16%">Tanggal</th>
            <th width="24%">Guru Pencatat</th>
            <th width="12%" class="text-center">Jam</th>
            <th width="8%"  class="text-center">Siswa</th>
            <th width="6%"  class="text-center text-success">H</th>
            <th width="6%"  class="text-center text-warning">S</th>
            <th width="6%"  class="text-center text-info">I</th>
            <th width="6%"  class="text-center text-danger">A</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($sesi_list as $idx => $sesi): ?>
        <!-- Baris sesi -->
        <tr>
          <td class="text-center"><?= $idx + 1 ?></td>
          <td><?= date('d M Y', strtotime($sesi['tanggal'])) ?><br>
              <small class="text-muted"><?= date('l', strtotime($sesi['tanggal'])) ?></small>
          </td>
          <td><?= htmlspecialchars($sesi['nama_guru']) ?></td>
          <td class="text-center">
            <?= htmlspecialchars($sesi['jam_masuk']) ?> – <?= htmlspecialchars($sesi['jam_pulang']) ?>
          </td>
          <td class="text-center"><?= (int)$sesi['total_siswa'] ?></td>
          <td class="text-center"><span class="badge badge-success"><?= (int)$sesi['jml_hadir'] ?></span></td>
          <td class="text-center"><span class="badge badge-warning"><?= (int)$sesi['jml_sakit'] ?></span></td>
          <td class="text-center"><span class="badge badge-info"><?= (int)$sesi['jml_izin'] ?></span></td>
          <td class="text-center"><span class="badge badge-danger"><?= (int)$sesi['jml_alpha'] ?></span></td>
        </tr>

        <?php endforeach; // sesi_list ?>
        </tbody>
      </table>
    </div><!-- /.card-body -->
  </div><!-- /.card kelas -->

  <?php endforeach; // data_kelas
  } // endif ada data
  ?>

</div>
</section>

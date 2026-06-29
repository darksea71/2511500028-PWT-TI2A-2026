<?php
/*

 * Aturan kerja:
 * - User filter tanggal + kelas + guru + jam → klik Tampilkan → tabel muncul.
 * - Jika sesi sudah pernah diisi, muncul alaert "Sesi Sudah Ada" + data lama ditampikan.
 */

if (session_status() === PHP_SESSION_NONE) session_start();

// ── PROSES SIMPAN ─────────────────────────────────────────────────────────────
if (isset($_POST['simpan'])) {
    $tanggal     = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
    $id_guru     = (int) $_POST['id_guru'];
    $jam_masuk   = mysqli_real_escape_string($koneksi, $_POST['jam_masuk']);
    $jam_pulang  = mysqli_real_escape_string($koneksi, $_POST['jam_pulang']);
    $list_status = $_POST['status'] ?? [];

    $sukses = 0;
    $gagal  = 0;

    foreach ($list_status as $id_siswa => $status) {
        $id_siswa = (int) $id_siswa;
        $status   = mysqli_real_escape_string($koneksi, $status);

        $cek = mysqli_query($koneksi, "
            SELECT id_absensi FROM absensi
            WHERE id_siswa   = $id_siswa
              AND tanggal    = '$tanggal'
              AND id_guru    = $id_guru
              AND jam_masuk  = '$jam_masuk'
              AND jam_pulang = '$jam_pulang'
        ");

        if (mysqli_num_rows($cek) > 0) {
            $q = mysqli_query($koneksi, "
                UPDATE absensi SET status_kehadiran = '$status'
                WHERE id_siswa   = $id_siswa
                  AND tanggal    = '$tanggal'
                  AND id_guru    = $id_guru
                  AND jam_masuk  = '$jam_masuk'
                  AND jam_pulang = '$jam_pulang'
            ");
        } else {
            $q = mysqli_query($koneksi, "
                INSERT INTO absensi (tanggal, status_kehadiran, jam_masuk, jam_pulang, id_siswa, id_guru)
                VALUES ('$tanggal', '$status', '$jam_masuk', '$jam_pulang', $id_siswa, $id_guru)
            ");
        }

        $q ? $sukses++ : $gagal++;
    }

    $label_aksi = isset($_POST['mode_edit']) && $_POST['mode_edit'] === '1' ? 'diperbarui' : 'disimpan';

    if ($gagal === 0) {
        $_SESSION['absensi_msg'] = [
            'type'  => 'success',
            'judul' => 'Sukses',
            'teks'  => 'Absensi berhasil ' . $label_aksi . ' untuk <strong>' . $sukses . '</strong> siswa.',
        ];
    } else {
        $_SESSION['absensi_msg'] = [
            'type'  => 'warning',
            'judul' => 'Sebagian Gagal',
            'teks'  => 'Berhasil ' . $label_aksi . ': ' . $sukses . ' | Gagal: ' . $gagal,
        ];
    }
    echo '<meta http-equiv="refresh" content="1;url=index.php?page=absensi">';
}

// ── Nilai filter aktif ────────────────────────────────────────────────────────
$f_tanggal    = $_GET['tanggal']    ?? date('Y-m-d');
$f_kelas      = $_GET['kelas']      ?? '';
$f_id_guru    = $_GET['id_guru']    ?? '';
$f_jam_masuk  = $_GET['jam_masuk']  ?? '';
$f_jam_pulang = $_GET['jam_pulang'] ?? '';
$tampil       = isset($_GET['tampilkan']);

$daftar_kelas = ['7A','7B','7C','8A','8B','8C','9A','9B','9C'];
$jam_options  = ['07:00','07:30','08:00','08:30','09:00','09:30',
                 '10:00','10:30','11:00','11:30','12:00','12:30','13:00'];
$query_guru   = mysqli_query($koneksi, "SELECT id_guru, nama_guru FROM guru ORDER BY nama_guru ASC");
?>

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Absensi Siswa</h1>
      </div>
    </div>
  </div>
</div>

<section class="content">
<div class="container-fluid">

<?php
// ── TAMPILKAN ALERT DARI SESSION (muncul setelah redirect) ────────────────────
if (!empty($_SESSION['absensi_msg'])) {
    $msg  = $_SESSION['absensi_msg'];
    $icon = $msg['type'] === 'success' ? 'fa-check' : 'fa-exclamation-triangle';
    echo '<div class="alert alert-' . $msg['type'] . ' alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <h5><i class="icon fas ' . $icon . '"></i> ' . $msg['judul'] . '</h5>
        ' . $msg['teks'] . '
    </div>';
    unset($_SESSION['absensi_msg']);   // hapus setelah ditampilkan, tidak muncul lagi saat refresh
}
?>

  <!-- FORM FILTER -->
  <div class="card card-primary">
    <div class="card-header">
      <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filter Absensi</h3>
    </div>
    <form method="GET" action="">
      <input type="hidden" name="page" value="absensi">
      <div class="card-body">
        <div class="row">
          <div class="col-md-2">
            <div class="form-group">
              <label>Tanggal</label>
              <input type="date" name="tanggal" class="form-control"
                     value="<?= htmlspecialchars($f_tanggal) ?>" required>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <label>Kelas</label>
              <select name="kelas" class="form-control" required>
                <option value="">-- Pilih Kelas --</option>
                <?php foreach ($daftar_kelas as $k): ?>
                  <option value="<?= $k ?>" <?= $f_kelas == $k ? 'selected' : '' ?>><?= $k ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label>Guru Pencatat</label>
              <select name="id_guru" class="form-control" required>
                <option value="">-- Pilih Guru --</option>
                <?php while ($g = mysqli_fetch_array($query_guru)): ?>
                  <option value="<?= $g['id_guru'] ?>"
                    <?= $f_id_guru == $g['id_guru'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($g['nama_guru']) ?>
                  </option>
                <?php endwhile; ?>
              </select>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <label>Jam Masuk</label>
              <select name="jam_masuk" class="form-control" required>
                <option value="">-- Pilih --</option>
                <?php foreach ($jam_options as $j): ?>
                  <option value="<?= $j ?>" <?= $f_jam_masuk == $j ? 'selected' : '' ?>><?= $j ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <label>Jam Pulang</label>
              <select name="jam_pulang" class="form-control" required>
                <option value="">-- Pilih --</option>
                <?php foreach ($jam_options as $j): ?>
                  <option value="<?= $j ?>" <?= $f_jam_pulang == $j ? 'selected' : '' ?>><?= $j ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="col-md-1 d-flex align-items-end">
            <div class="form-group w-100">
              <button type="submit" name="tampilkan" class="btn btn-primary btn-block">
                <i class="fas fa-search"></i> Tampilkan
              </button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>

  <!-- TABEL ABSENSI — hanya muncul setelah klik Tampilkan -->
  <?php if ($tampil && $f_kelas && $f_id_guru && $f_jam_masuk && $f_jam_pulang): ?>
  <?php
    $kelas_esc   = mysqli_real_escape_string($koneksi, $f_kelas);
    $tanggal_esc = mysqli_real_escape_string($koneksi, $f_tanggal);
    $jam_in_esc  = mysqli_real_escape_string($koneksi, $f_jam_masuk);
    $jam_out_esc = mysqli_real_escape_string($koneksi, $f_jam_pulang);
    $id_guru_int = (int) $f_id_guru;

    $query_siswa = mysqli_query($koneksi,
      "SELECT id_siswa, nama_siswa FROM siswa WHERE kelas='$kelas_esc' ORDER BY nama_siswa ASC");
    $total = mysqli_num_rows($query_siswa);

    // Cek apakah sesi ini sudah pernah diisi
    $cek_sesi  = mysqli_query($koneksi, "
      SELECT COUNT(*) AS jml FROM absensi a
      JOIN siswa s ON s.id_siswa = a.id_siswa
      WHERE a.tanggal    = '$tanggal_esc'
        AND a.id_guru    = $id_guru_int
        AND a.jam_masuk  = '$jam_in_esc'
        AND a.jam_pulang = '$jam_out_esc'
        AND s.kelas      = '$kelas_esc'
    ");
    $info_sesi   = mysqli_fetch_assoc($cek_sesi);
    $sudah_diisi = $info_sesi['jml'] > 0;
  ?>

  <?php if ($total == 0): ?>
    <div class="alert alert-warning">
      Tidak ada siswa di kelas <strong><?= htmlspecialchars($f_kelas) ?></strong>.
    </div>
  <?php else: ?>

  <?php if ($sudah_diisi): ?>
    <div class="alert alert-warning">
      <h5><i class="icon fas fa-edit"></i> Sesi Sudah Ada</h5>
      Data absensi untuk sesi ini (<strong><?= date('d F Y', strtotime($f_tanggal)) ?></strong>,
      kelas <strong><?= htmlspecialchars($f_kelas) ?></strong>,
      jam <strong><?= htmlspecialchars($f_jam_masuk) ?>–<?= htmlspecialchars($f_jam_pulang) ?></strong>)
      sudah pernah diisi. Status yang tersimpan ditampilkan di bawah — ubah seperlunya lalu klik
      <strong>Update Absensi</strong>.
    </div>
  <?php endif; ?>

  <form method="POST" action="index.php?page=absensi" onsubmit="return validasi()">
    <input type="hidden" name="tanggal"    value="<?= htmlspecialchars($f_tanggal) ?>">
    <input type="hidden" name="kelas"      value="<?= htmlspecialchars($f_kelas) ?>">
    <input type="hidden" name="id_guru"    value="<?= htmlspecialchars($f_id_guru) ?>">
    <input type="hidden" name="jam_masuk"  value="<?= htmlspecialchars($f_jam_masuk) ?>">
    <input type="hidden" name="jam_pulang" value="<?= htmlspecialchars($f_jam_pulang) ?>">
    <input type="hidden" name="mode_edit"  value="<?= $sudah_diisi ? '1' : '0' ?>">

    <div class="card <?= $sudah_diisi ? 'card-warning' : 'card-info' ?>">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">
          <?php if ($sudah_diisi): ?><i class="fas fa-edit mr-1"></i><?php endif; ?>
          Kelas <?= htmlspecialchars($f_kelas) ?>
          &mdash; <?= date('d F Y', strtotime($f_tanggal)) ?>
          <small class="ml-2 text-light">
            <i class="fas fa-clock mr-1"></i><?= htmlspecialchars($f_jam_masuk) ?> – <?= htmlspecialchars($f_jam_pulang) ?>
          </small>
        </h3>
        <span class="badge badge-light"><?= $total ?> Siswa</span>
      </div>
      <div class="card-body p-0">
        <table class="table table-bordered table-hover mb-0">
          <thead class="thead-dark">
            <tr>
              <th width="5%"  class="text-center">No</th>
              <th width="45%" class="text-center">Nama Siswa</th>
              <th width="30%" class="text-center">Status Kehadiran</th>
              <th width="20%" class="text-center">Keterangan</th>
            </tr>
          </thead>
          <tbody>
          <?php
          $no = 0;
          while ($s = mysqli_fetch_array($query_siswa)):
            $no++;
            $id_siswa = (int) $s['id_siswa'];

            $cek = mysqli_query($koneksi, "
              SELECT status_kehadiran FROM absensi
              WHERE id_siswa   = $id_siswa
                AND tanggal    = '$tanggal_esc'
                AND id_guru    = $id_guru_int
                AND jam_masuk  = '$jam_in_esc'
                AND jam_pulang = '$jam_out_esc'
            ");
            $existing    = mysqli_fetch_array($cek);
            $status_lama = $existing['status_kehadiran'] ?? '';

            $badge_map = ['Hadir'=>'success','Sakit'=>'warning','Izin'=>'info','Alpha'=>'danger'];
          ?>
            <tr id="row-<?= $id_siswa ?>">
              <td class="text-center"><?= $no ?></td>
              <td><?= htmlspecialchars($s['nama_siswa']) ?></td>
              <td class="text-center">
                <select name="status[<?= $id_siswa ?>]"
                        class="form-control form-control-sm status-select"
                        data-id="<?= $id_siswa ?>"
                        onchange="updateBadge(this)">
                  <option value="">-- Pilih --</option>
                  <?php foreach (['Hadir','Sakit','Izin','Alpha'] as $opt): ?>
                    <option value="<?= $opt ?>" <?= $status_lama == $opt ? 'selected' : '' ?>><?= $opt ?></option>
                  <?php endforeach; ?>
                </select>
              </td>
              <td class="text-center">
                <span class="badge badge-<?= $badge_map[$status_lama] ?? 'light' ?>"
                      id="badge-<?= $id_siswa ?>">
                  <?= $status_lama ?: 'Belum diisi' ?>
                </span>
              </td>
            </tr>
          <?php endwhile; ?>
          </tbody>
          <tfoot class="table-secondary font-weight-bold dark-mode">
            <tr>
              <td colspan="2" class="text-right">Progress:</td>
              <td colspan="2" class="text-center">
                <span id="progress-text">0</span> / <?= $total ?> terisi
                <div class="progress mt-1" style="height:6px">
                  <div class="progress-bar bg-success" id="progress-bar" style="width:0%"></div>
                </div>
              </td>
            </tr>
          </tfoot>
        </table>
      </div>
      <div class="card-footer d-flex justify-content-between align-items-center">
        <div>
          <small class="text-muted">
            <i class="fas fa-info-circle mr-1"></i>
            <strong>Tips:</strong> Gunakan tombol <strong>Semua Hadir</strong> untuk mengisi semua siswa sebagai hadir, lalu ubah seperlunya.
          </small>
        </div>
        <div>
          <button type="button" class="btn btn-secondary mr-2" onclick="isiSemuaHadir()">
            <i class="fas fa-check-double"></i> Semua Hadir
          </button>
          <button type="submit" name="simpan" class="btn <?= $sudah_diisi ? 'btn-warning' : 'btn-success' ?>">
            <i class="fas fa-save mr-1"></i>
            <?= $sudah_diisi ? 'Update Absensi' : 'Simpan Absensi' ?>
          </button>
        </div>
      </div>
    </div>
  </form>

  <?php endif; ?>
  <?php endif; ?>

</div>
</section>

<script>
const totalSiswa = <?= $total ?? 0 ?>;

function updateBadge(el) {
    const map = {Hadir:'success', Sakit:'warning', Izin:'info', Alpha:'danger', '':'light'};
    const badge = document.getElementById('badge-' + el.dataset.id);
    badge.className = 'badge badge-' + (map[el.value] || 'light');
    badge.textContent = el.value || 'Belum diisi';
    hitungProgress();
}

function hitungProgress() {
    const selects = document.querySelectorAll('.status-select');
    let terisi = [...selects].filter(s => s.value !== '').length;
    document.getElementById('progress-text').textContent = terisi;
    document.getElementById('progress-bar').style.width = (terisi / totalSiswa * 100) + '%';
}

function isiSemuaHadir() {
    document.querySelectorAll('.status-select').forEach(s => {
        s.value = 'Hadir';
        updateBadge(s);
    });
}

function validasi() {
    const kosong = [...document.querySelectorAll('.status-select')].filter(s => s.value === '');
    if (kosong.length > 0) {
        kosong.forEach(s => {
            s.classList.add('is-invalid');
            document.getElementById('row-' + s.dataset.id)?.classList.add('table-danger');
        });
        alert('Masih ada ' + kosong.length + ' siswa yang belum diisi!');
        kosong[0].scrollIntoView({behavior:'smooth', block:'center'});
        return false;
    }
    return true;
}

document.addEventListener('DOMContentLoaded', () => {
    hitungProgress();
    document.querySelectorAll('.status-select').forEach(s => {
        s.addEventListener('change', function() {
            this.classList.remove('is-invalid');
            document.getElementById('row-' + this.dataset.id)?.classList.remove('table-danger');
        });
    });
});
</script>

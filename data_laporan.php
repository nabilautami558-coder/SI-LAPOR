
<?php
include "../api_si_lapor/koneksi.php";

$query = mysqli_query($koneksi, "SELECT * FROM laporan ORDER BY id DESC");
$total = mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM laporan"));
$menunggu = mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM laporan WHERE status='Menunggu'"));
$diproses = mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM laporan WHERE status='Diproses'"));
$selesai = mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM laporan WHERE status='Selesai'"));
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Data Laporan | SI-LAPOR</title>
<style>
*{box-sizing:border-box}
body{margin:0;font-family:Arial,sans-serif;background:#f3f6fb;color:#243247}
.sidebar{position:fixed;inset:0 auto 0 0;width:235px;background:#142b4a;color:white;padding:28px 20px}
.brand{font-size:26px;font-weight:bold}.brand span{color:#62d6c5}
.tagline{font-size:12px;color:#bdcce0;margin:6px 0 40px}
.menu-title{font-size:11px;color:#9fb2ca;letter-spacing:1.5px;margin:25px 10px 12px}
.menu{display:block;padding:13px 14px;margin:7px 0;border-radius:9px;color:#e7eef8;text-decoration:none}
.menu:hover,.active{background:#25466e}
.main{margin-left:235px;padding:30px}
h1{margin:0 0 7px;font-size:27px;color:#172f50}
.subtitle{color:#77869a;font-size:14px;margin-bottom:25px}
.cards{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:15px;margin-bottom:24px}
.card,.panel{background:white;border:1px solid #e5ebf3;border-radius:14px;padding:20px;box-shadow:0 4px 14px #20395a08}
.card-label{font-size:13px;color:#718198}
.number{font-size:30px;font-weight:bold;color:#1a3557;margin:12px 0 7px}
.note{font-size:12px;color:#8492a5}
.panel-head{display:flex;justify-content:space-between;gap:12px;align-items:center;flex-wrap:wrap;margin-bottom:18px}
h2{font-size:19px;margin:0 0 6px;color:#1b3454}
.desc{font-size:13px;color:#8592a4}
input,select{padding:10px;border:1px solid #dce5ef;border-radius:8px;background:white}
.btn{display:inline-block;padding:10px 14px;border:0;border-radius:8px;background:#1e426d;color:white;text-decoration:none;cursor:pointer;font-size:13px}
.btn-light{background:#eaf1f9;color:#24456b}
.table-wrap{overflow-x:auto}
table{width:100%;border-collapse:collapse;min-width:1000px}
th{background:#f4f7fb;color:#64758c;font-size:11px;text-transform:uppercase;letter-spacing:.5px}
th,td{padding:13px 12px;border-bottom:1px solid #edf1f6;text-align:left;font-size:13px;vertical-align:top}
tbody tr:hover{background:#fafcff}
.status{display:inline-block;border-radius:20px;padding:5px 10px;font-size:11px;font-weight:bold;background:#fff2d8;color:#946200}
.foto{width:90px;height:65px;object-fit:cover;border-radius:7px;display:block;margin-bottom:5px}
.footer{text-align:center;color:#93a0b0;font-size:12px;padding:24px}
@media(max-width:1000px){.cards{grid-template-columns:repeat(2,minmax(0,1fr))}.sidebar{width:190px}.main{margin-left:190px;padding:20px}}
@media(max-width:650px){.sidebar{position:static;width:100%;padding:18px;height:auto}.tagline{margin-bottom:10px}.menu-title{display:none}.menu{display:inline-block;padding:9px}.main{margin:0;padding:14px}.cards{grid-template-columns:repeat(2,minmax(0,1fr))}.card{padding:14px}}
@media print{.sidebar,.no-print{display:none!important}.main{margin:0;padding:0}.cards{grid-template-columns:repeat(4,1fr)}}
</style>
</head>
<body>
<aside class="sidebar">
  <div class="brand">SI-<span>LAPOR</span></div>
  <div class="tagline">Sistem Pelaporan Fasilitas Sekolah</div>
  <div class="menu-title">MENU UTAMA</div>
  <a class="menu" href="index.php">▦ &nbsp; Dashboard Admin</a>
  <div class="menu-title">PENGELOLAAN</div>
  <a class="menu active" href="data_laporan.php">▤ &nbsp; Data Laporan</a>
  <a class="menu" href="javascript:window.print()">▧ &nbsp; Cetak Laporan</a>
</aside>

<main class="main">
  <h1>Data Laporan</h1>
  <div class="subtitle">Kelola, periksa, dan pantau laporan kerusakan fasilitas sekolah.</div>

  <section class="cards">
    <div class="card"><div class="card-label">Total Laporan</div><div class="number"><?= $total ?></div><div class="note">Semua laporan masuk</div></div>
    <div class="card"><div class="card-label">Menunggu</div><div class="number"><?= $menunggu ?></div><div class="note">Belum ditangani</div></div>
    <div class="card"><div class="card-label">Diproses</div><div class="number"><?= $diproses ?></div><div class="note">Sedang ditangani</div></div>
    <div class="card"><div class="card-label">Selesai</div><div class="number"><?= $selesai ?></div><div class="note">Sudah ditangani</div></div>
  </section>

  <section class="panel">
    <div class="panel-head">
      <div><h2>Semua Laporan Fasilitas</h2><div class="desc">Gunakan pencarian untuk menemukan laporan.</div></div>
      <div class="no-print" style="display:flex;gap:8px;flex-wrap:wrap">
        <input id="cari" type="search" placeholder="Cari fasilitas atau deskripsi...">
        <button class="btn" onclick="window.print()">Cetak</button>
        <a class="btn btn-light" href="export_excel.php">Export Excel</a>
      </div>
    </div>

    <div class="table-wrap">
      <table id="tabel">
        <thead><tr><th>No.</th><th>Fasilitas</th><th>Deskripsi</th><th>Lokasi</th><th>Foto</th><th>Status</th><th>Tanggal</th><th>Ubah Status</th></tr></thead>
        <tbody>
        <?php if($query && mysqli_num_rows($query)>0): $no=1; while($r=mysqli_fetch_assoc($query)): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><strong><?= htmlspecialchars($r['jenis_fasilitas']) ?></strong></td>
            <td><?= htmlspecialchars($r['deskripsi']) ?></td>
            <td>Lat: <?= htmlspecialchars($r['latitude'] ?: '-') ?><br>Long: <?= htmlspecialchars($r['longitude'] ?: '-') ?></td>
            <td>
            <?php
              $foto = trim($r['foto'] ?? '');
              $namaFoto = basename(str_replace('\\','/',$foto));
              $pathFoto = __DIR__ . '/../api_si_lapor/uploads/' . $namaFoto;
              $urlFoto = '../api_si_lapor/uploads/' . rawurlencode($namaFoto);
            ?>
            <?php if($foto !== '' && is_file($pathFoto)): ?>
              <a href="<?= htmlspecialchars($urlFoto) ?>" target="_blank">
                <img class="foto" src="<?= htmlspecialchars($urlFoto) ?>" alt="Foto laporan">Lihat foto
              </a>
            <?php elseif($foto !== ''): ?>
              File foto tidak ditemukan
            <?php else: ?>
              <span style="color:#94a3b8">Belum ada foto</span>
            <?php endif; ?>
            </td>
            <td><span class="status"><?= htmlspecialchars($r['status']) ?></span></td>
            <td><?= htmlspecialchars($r['tanggal']) ?></td>
            <td>
              <form method="post" action="update_status.php">
                <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                <select name="status">
                <?php foreach(['Menunggu','Diproses','Selesai','Ditolak'] as $s): ?>
                  <option value="<?= $s ?>" <?= $r['status']===$s?'selected':'' ?>><?= $s ?></option>
                <?php endforeach; ?>
                </select>
                <button class="btn" type="submit" style="margin-top:6px">Simpan</button>
              </form>
            </td>
          </tr>
        <?php endwhile; else: ?>
          <tr><td colspan="8" style="text-align:center;padding:30px">Belum ada laporan.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </section>
  <div class="footer">SI-LAPOR · Sistem Pelaporan Fasilitas Sekolah</div>
</main>

<script>
document.getElementById('cari').addEventListener('input', function(){
  const kata = this.value.toLowerCase();
  document.querySelectorAll('#tabel tbody tr').forEach(function(baris){
    baris.style.display = baris.innerText.toLowerCase().includes(kata) ? '' : 'none';
  });
});
</script>
</body>
</html>
```
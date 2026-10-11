
<?php
include "../api_si_lapor/koneksi.php";

$total = (int)mysqli_fetch_assoc(mysqli_query($koneksi,
    "SELECT COUNT(*) AS jumlah FROM laporan"))['jumlah'];

$menunggu = (int)mysqli_fetch_assoc(mysqli_query($koneksi,
    "SELECT COUNT(*) AS jumlah FROM laporan WHERE status='Menunggu'"))['jumlah'];

$diproses = (int)mysqli_fetch_assoc(mysqli_query($koneksi,
    "SELECT COUNT(*) AS jumlah FROM laporan WHERE status='Diproses'"))['jumlah'];

$selesai = (int)mysqli_fetch_assoc(mysqli_query($koneksi,
    "SELECT COUNT(*) AS jumlah FROM laporan WHERE status='Selesai'"))['jumlah'];

$ditolak = (int)mysqli_fetch_assoc(mysqli_query($koneksi,
    "SELECT COUNT(*) AS jumlah FROM laporan WHERE status='Ditolak'"))['jumlah'];

$terbaru = mysqli_query($koneksi,
    "SELECT jenis_fasilitas, deskripsi, status, tanggal
     FROM laporan ORDER BY id DESC LIMIT 5");

function persen($nilai, $total) {
    return $total > 0 ? round(($nilai / $total) * 100) : 0;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Dashboard Admin | SI-LAPOR</title>
<style>
*{box-sizing:border-box}
body{margin:0;font-family:Arial,sans-serif;background:#f3f6fb;color:#26364c}
.sidebar{position:fixed;inset:0 auto 0 0;width:235px;background:#142b4a;color:white;padding:27px 20px}
.brand{font-size:26px;font-weight:bold}.brand span{color:#62d6c5}
.tagline{font-size:12px;color:#bdcce0;margin:6px 0 40px}
.menu-title{font-size:11px;color:#9fb2ca;letter-spacing:1.5px;margin:25px 10px 12px}
.menu{display:block;padding:13px 14px;margin:7px 0;border-radius:9px;color:#e7eef8;text-decoration:none}
.menu:hover,.active{background:#25466e}
.main{margin-left:235px;padding:30px;max-width:1600px}
.header{display:flex;justify-content:space-between;align-items:center;gap:15px;flex-wrap:wrap;margin-bottom:25px}
h1{font-size:27px;margin:0 0 8px;color:#172f50}
.sub{font-size:14px;color:#77869a}
.admin{background:white;border:1px solid #e3eaf2;border-radius:10px;padding:12px 16px;font-size:13px}
.welcome{background:linear-gradient(120deg,#1b3d66,#28618a);color:white;padding:25px;border-radius:15px;margin-bottom:23px}
.welcome h2{margin:0 0 10px;font-size:22px}
.welcome p{margin:0;color:#e0eaf5;line-height:1.6;font-size:14px}
.cards{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:15px;margin-bottom:23px}
.card{background:white;border:1px solid #e5ebf3;border-radius:13px;padding:19px;box-shadow:0 4px 14px #20395a08}
.label{font-size:13px;color:#718198}
.number{font-size:31px;font-weight:bold;color:#1a3557;margin:12px 0 8px}
.note{font-size:12px;color:#8592a4}
.panel-grid{display:grid;grid-template-columns:1fr 1.3fr;gap:20px}
.panel{background:white;border:1px solid #e5ebf3;border-radius:14px;padding:22px;min-width:0}
h3{margin:0 0 7px;font-size:18px;color:#1b3454}
.desc{font-size:13px;color:#8592a4;margin-bottom:22px}
.progress-item{margin-bottom:22px}
.progress-head{display:flex;justify-content:space-between;gap:10px;font-size:13px;margin-bottom:9px}
.track{height:9px;background:#edf1f7;border-radius:20px;overflow:hidden}
.fill{height:100%;border-radius:20px;background:#2d6a9f}
.fill.wait{background:#e7ae43}
.fill.work{background:#5687c5}
.fill.done{background:#36a782}
.fill.reject{background:#d66b72}
.legend{font-size:12px;color:#77869a;margin-top:18px;line-height:1.8}
.activity{padding:15px 0;border-bottom:1px solid #edf1f6}
.activity:last-child{border-bottom:0}
.activity-top{display:flex;justify-content:space-between;gap:10px;flex-wrap:wrap;margin-bottom:7px}
.activity strong{font-size:14px;color:#243b59}
.activity p{margin:0;color:#75849a;font-size:13px;line-height:1.5}
.pill{font-size:11px;padding:5px 9px;border-radius:20px;background:#fff2d8;color:#946200;white-space:nowrap;height:max-content}
.pill.work{background:#e4efff;color:#315f9a}
.pill.done{background:#def7e9;color:#23744c}
.pill.reject{background:#ffe5e5;color:#a43b3b}
.btn{display:inline-block;background:#1e426d;color:white;text-decoration:none;padding:11px 15px;border-radius:8px;font-size:13px}
.btn:hover{opacity:.88}
.quick{display:flex;gap:10px;flex-wrap:wrap;margin-top:22px}
.footer{text-align:center;font-size:12px;color:#93a0b0;padding:25px 0}
.empty{color:#8592a4;font-size:13px;padding:15px 0}
@media(max-width:1100px){.cards{grid-template-columns:repeat(2,minmax(0,1fr))}.panel-grid{grid-template-columns:1fr}}
@media(max-width:650px){.sidebar{position:static;width:100%;height:auto;padding:18px}.tagline{margin-bottom:10px}.menu-title{display:none}.menu{display:inline-block;padding:9px}.main{margin:0;padding:15px}.cards{gap:9px}.card{padding:13px}.number{font-size:26px}.welcome{padding:20px}}
@media print{.sidebar,.admin,.quick{display:none!important}.main{margin:0;padding:0}.panel-grid{grid-template-columns:1fr 1fr}.welcome{background:white;color:black;border:1px solid #ccc}.welcome p{color:#333}}
</style>
</head>
<body>
<aside class="sidebar">
  <div class="brand">SI-<span>LAPOR</span></div>
  <div class="tagline">Sistem Pelaporan Fasilitas Sekolah</div>
  <div class="menu-title">MENU UTAMA</div>
  <a class="menu active" href="index.php">▦ &nbsp; Dashboard Admin</a>
  <div class="menu-title">PENGELOLAAN</div>
  <a class="menu" href="data_laporan.php">▤ &nbsp; Data Laporan</a>
  <a class="menu" href="javascript:window.print()">▧ &nbsp; Cetak Ringkasan</a>
</aside>

<main class="main">
  <header class="header">
    <div>
      <h1>Dashboard Admin</h1>
      <div class="sub">Ringkasan kondisi pelaporan fasilitas sekolah.</div>
    </div>
    <div class="admin">Administrator &nbsp; ●</div>
  </header>

  <section class="welcome">
    <h2>Selamat datang di SI-LAPOR 👋</h2>
    <p>Pantau laporan kerusakan fasilitas sekolah melalui satu dashboard.
    Periksa jumlah laporan, progres penanganan, dan aktivitas terbaru di bawah ini.</p>
    <div class="quick">
      <a class="btn" href="data_laporan.php">Kelola Data Laporan →</a>
    </div>
  </section>

  <section class="cards">
    <div class="card">
      <div class="label">Total Laporan</div>
      <div class="number"><?= $total ?></div>
      <div class="note">Semua laporan yang tercatat</div>
    </div>
    <div class="card">
      <div class="label">Menunggu</div>
      <div class="number"><?= $menunggu ?></div>
      <div class="note">Belum ditindaklanjuti</div>
    </div>
    <div class="card">
      <div class="label">Diproses</div>
      <div class="number"><?= $diproses ?></div>
      <div class="note">Sedang dalam penanganan</div>
    </div>
    <div class="card">
      <div class="label">Selesai</div>
      <div class="number"><?= $selesai ?></div>
      <div class="note">Sudah ditindaklanjuti</div>
    </div>
  </section>

  <section class="panel-grid">
    <div class="panel">
      <h3>Progres Penanganan</h3>
      <div class="desc">Persentase berdasarkan seluruh laporan.</div>

      <div class="progress-item">
        <div class="progress-head"><span>Menunggu</span><strong><?= persen($menunggu,$total) ?>%</strong></div>
        <div class="track"><div class="fill wait" style="width:<?= persen($menunggu,$total) ?>%"></div></div>
      </div>

      <div class="progress-item">
        <div class="progress-head"><span>Diproses</span><strong><?= persen($diproses,$total) ?>%</strong></div>
        <div class="track"><div class="fill work" style="width:<?= persen($diproses,$total) ?>%"></div></div>
      </div>

      <div class="progress-item">
        <div class="progress-head"><span>Selesai</span><strong><?= persen($selesai,$total) ?>%</strong></div>
        <div class="track"><div class="fill done" style="width:<?= persen($selesai,$total) ?>%"></div></div>
      </div>

      <div class="progress-item">
        <div class="progress-head"><span>Ditolak</span><strong><?= persen($ditolak,$total) ?>%</strong></div>
        <div class="track"><div class="fill reject" style="width:<?= persen($ditolak,$total) ?>%"></div></div>
      </div>

      <div class="legend">Grafik akan berubah otomatis mengikuti status laporan di database.</div>
    </div>

    <div class="panel">
      <h3>Aktivitas Terbaru</h3>
      <div class="desc">Lima laporan terakhir yang masuk ke sistem.</div>

      <?php if($terbaru && mysqli_num_rows($terbaru)>0): ?>
        <?php while($r=mysqli_fetch_assoc($terbaru)):
          $status = $r['status'];
          $kelas = $status === 'Selesai' ? 'done' : ($status === 'Diproses' ? 'work' : ($status === 'Ditolak' ? 'reject' : ''));
        ?>
          <div class="activity">
            <div class="activity-top">
              <strong><?= htmlspecialchars($r['jenis_fasilitas']) ?></strong>
              <span class="pill <?= $kelas ?>"><?= htmlspecialchars($status) ?></span>
            </div>
            <p><?= htmlspecialchars($r['deskripsi']) ?></p>
            <p style="margin-top:5px;font-size:11px"><?= htmlspecialchars($r['tanggal']) ?></p>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="empty">Belum ada laporan masuk.</div>
      <?php endif; ?>

      <div class="quick">
        <a class="btn" href="data_laporan.php">Lihat Semua Data →</a>
      </div>
    </div>
  </section>

  <div class="footer">SI-LAPOR · Sistem Pelaporan Fasilitas Sekolah</div>
</main>
</body>
</html>

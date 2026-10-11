
<?php
include "koneksi.php";

header("Content-Type: text/plain; charset=UTF-8");

$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

if ($id <= 0) {
    http_response_code(400);
    exit("GAGAL: ID laporan tidak valid");
}

$data = file_get_contents("php://input");

if ($data === false || strlen($data) === 0) {
    http_response_code(400);
    exit("GAGAL: Data foto kosong");
}

$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->buffer($data);

if ($mime === "image/jpeg") {
    $ext = "jpg";
} elseif ($mime === "image/png") {
    $ext = "png";
} else {
    http_response_code(400);
    exit("GAGAL: Format foto bukan JPG atau PNG");
}

$folder = __DIR__ . "/uploads/";

if (!is_dir($folder)) {
    if (!mkdir($folder, 0777, true)) {
        http_response_code(500);
        exit("GAGAL: Folder uploads tidak bisa dibuat");
    }
}

$nama = "laporan_" . $id . "_" . uniqid() . "." . $ext;
$tujuan = $folder . $nama;

if (file_put_contents($tujuan, $data) === false) {
    http_response_code(500);
    exit("GAGAL: Foto tidak bisa disimpan");
}

$stmt = mysqli_prepare(
    $koneksi,
    "UPDATE laporan SET foto = ? WHERE id = ?"
);

if (!$stmt) {
    @unlink($tujuan);
    http_response_code(500);
    exit("GAGAL: Query database bermasalah");
}

mysqli_stmt_bind_param($stmt, "si", $nama, $id);
$berhasil = mysqli_stmt_execute($stmt);
$jumlah = mysqli_stmt_affected_rows($stmt);
mysqli_stmt_close($stmt);

if (!$berhasil || $jumlah < 0) {
    @unlink($tujuan);
    http_response_code(500);
    exit("GAGAL: Database tidak berhasil diperbarui");
}

echo "BERHASIL: Foto tersimpan untuk laporan ID " . $id;

mysqli_close($koneksi);
?>

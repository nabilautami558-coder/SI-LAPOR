
<?php
header('Content-Type: application/json; charset=utf-8');
include "koneksi.php";

$jenis = $_POST['jenis_fasilitas'] ?? '';
$deskripsi = $_POST['deskripsi'] ?? '';
$latitude = $_POST['latitude'] ?? '';
$longitude = $_POST['longitude'] ?? '';
$foto = '';

if ($jenis === '' || $deskripsi === '') {
    echo json_encode(["status"=>"error","message"=>"Fasilitas dan deskripsi wajib diisi"]);
    exit;
}

if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $folder = __DIR__ . '/uploads/';
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $ekstensi = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
    $diizinkan = ['jpg', 'jpeg', 'png'];

    if (in_array($ekstensi, $diizinkan, true)) {
        $namaFoto = uniqid('foto_', true) . '.' . $ekstensi;

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $folder . $namaFoto)) {
            $foto = $namaFoto;
        }
    }
}

$stmt = mysqli_prepare($koneksi,
    "INSERT INTO laporan (jenis_fasilitas, deskripsi, foto, latitude, longitude)
     VALUES (?, ?, ?, ?, ?)"
);
mysqli_stmt_bind_param($stmt, "sssss", $jenis, $deskripsi, $foto, $latitude, $longitude);

if (mysqli_stmt_execute($stmt)) {
    $id = mysqli_insert_id($koneksi);
echo json_encode(["status"=>"success","id"=>$id,"message"=>"Laporan tersimpan","foto"=>$foto]);
} else {
    http_response_code(500);
    echo json_encode(["status"=>"error","message"=>"Gagal menyimpan laporan"]);
}

mysqli_stmt_close($stmt);
mysqli_close($koneksi);
?>

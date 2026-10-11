
<?php
header('Content-Type: application/json; charset=utf-8');

include "koneksi.php";

$sql = "SELECT id, jenis_fasilitas, deskripsi, foto,
        latitude, longitude, status, tanggal
        FROM laporan ORDER BY id DESC";

$result = mysqli_query($koneksi, $sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(["error" => "Gagal mengambil laporan"]);
    exit;
}

$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = [
        "id" => $row["id"],
        "jenis_fasilitas" => $row["jenis_fasilitas"],
        "deskripsi" => $row["deskripsi"],
        "foto" => $row["foto"],
        "latitude" => $row["latitude"],
        "longitude" => $row["longitude"],
        "status" => $row["status"],
        "tanggal" => $row["tanggal"]
    ];
}

echo json_encode($data, JSON_UNESCAPED_UNICODE);
mysqli_close($koneksi);
?>

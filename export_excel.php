```php
<?php
include "../api_si_lapor/koneksi.php";

header("Content-Type: text/csv; charset=UTF-8");
header("Content-Disposition: attachment; filename=laporan_si_lapor.csv");

$output = fopen("php://output", "w");

// BOM agar teks terbaca dengan baik di Excel
fwrite($output, "\xEF\xBB\xBF");

fputcsv($output, [
    "ID",
    "Jenis Fasilitas",
    "Deskripsi",
    "Foto",
    "Latitude",
    "Longitude",
    "Status",
    "Tanggal"
]);

$query = mysqli_query(
    $koneksi,
    "SELECT id, jenis_fasilitas, deskripsi, foto,
    latitude, longitude, status, tanggal
    FROM laporan ORDER BY id DESC"
);

while ($row = mysqli_fetch_assoc($query)) {
    fputcsv($output, [
        $row["id"],
        $row["jenis_fasilitas"],
        $row["deskripsi"],
        $row["foto"],
        $row["latitude"],
        $row["longitude"],
        $row["status"],
        $row["tanggal"]
    ]);
}

fclose($output);
mysqli_close($koneksi);
exit;
?>
```
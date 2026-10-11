```php
<?php
include "../api_si_lapor/koneksi.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = (int)($_POST["id"] ?? 0);
    $status = $_POST["status"] ?? "";

    $daftar_status = ["Menunggu", "Diproses", "Selesai", "Ditolak"];

    if ($id > 0 && in_array($status, $daftar_status, true)) {
        $stmt = mysqli_prepare(
            $koneksi,
            "UPDATE laporan SET status = ? WHERE id = ?"
        );

        mysqli_stmt_bind_param($stmt, "si", $status, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

header("Location: index.php#daftar-laporan");
exit;
?>
```
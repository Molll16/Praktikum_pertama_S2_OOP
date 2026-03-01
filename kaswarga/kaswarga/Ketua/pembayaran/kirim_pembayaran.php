<?php
include "../../../Database/koneksi.php";

date_default_timezone_set("Asia/Jakarta");
$tanggal = date("Y-m-d H:i:s");
$nik = $_POST['nik'];
$nama = $_POST['nama'];
$iuran_id = $_POST['iuran_id'];
$iuran_type = $_POST['iuran_type'];
$nominal = $_POST['nominal'];
$metode = $_POST['metode'];
$periode = $_POST['periode'];
$nama_iuran = $_POST['iuran_nama'];

if (empty($metode)) {
    echo "<script>
        alert('Metode pembayaran harus dipilih!');
        window.location.href = 'pembayaran.php';
    </script>";
    exit();
}

// upload bukti
$bukti_name = null;
if (!empty($_FILES['bukti']['name'])) {
    $ext = pathinfo($_FILES['bukti']['name'], PATHINFO_EXTENSION);
    $bukti_name = "bukti_" . time() . "." . $ext;
    move_uploaded_file($_FILES['bukti']['tmp_name'], "../../../uploads/" . $bukti_name);
} else {
    echo "<script>
        alert('Bukti pembayaran harus diunggah!');
        window.location.href = 'pembayaran.php';
    </script>";
    exit(); 
}

$query = "
UPDATE iuran_warga
SET 
    nama = '$nama',
    jenis_iuran = '$iuran_type',
    nominal = '$nominal',
    tanggal_bayar = '$tanggal_bayar',
    bukti = '$bukti_name',
    status = 'pending',
    metode = '$metode',
    periode = '$periode',
    nama_iuran = '$nama_iuran'
WHERE nik = '$nik' AND iuran_id = '$iuran_id'
";

mysqli_query($koneksi, $query);

header("Location: pembayaran.php?success=1");
exit();
?>

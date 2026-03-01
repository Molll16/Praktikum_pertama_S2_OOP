<?php
include '../../../Database/koneksi.php';

$id              = $_POST['id'] ?? 0;
$nama_transaksi  = $_POST['nama_transaksi'] ?? '';
$nama            = $_POST['nama'] ?? '';
$kategori        = $_POST['kategori'] ?? '';
$jumlah          = $_POST['jumlah'] ?? 0;
$metode          = $_POST['metode'] ?? '';
$alamat          = $_POST['alamat'] ?? '';
$uraian          = $_POST['uraian'] ?? '';

// ambil bukti lama dulu
$result = mysqli_query($koneksi, "SELECT bukti FROM catatan WHERE id='$id'");
$row    = mysqli_fetch_assoc($result);
$bukti_nama = $row['bukti'] ?? '';

// cek apakah ada file baru diupload
if (isset($_FILES['bukti']) && $_FILES['bukti']['error'] === 0) {
    $folder = "../../../uploads/";
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }
    $bukti_nama = time() . "_" . basename($_FILES["bukti"]["name"]);
    $target_file = $folder . $bukti_nama;
    move_uploaded_file($_FILES["bukti"]["tmp_name"], $target_file);
}

// buat query update
$sql = "UPDATE catatan SET 
            nama_iuran   = '$nama_transaksi',
            nama         = '$nama',
            kategori     = '$kategori',
            jumlah       = '$jumlah',
            metode       = '$metode',
            alamat       = '$alamat',
            uraian       = '$uraian',
            bukti        = '$bukti_nama'
        WHERE id='$id'";

if (mysqli_query($koneksi, $sql)) {
    echo "sukses";
} else {
    echo "error: " . mysqli_error($koneksi);
}

mysqli_close($koneksi);
?>
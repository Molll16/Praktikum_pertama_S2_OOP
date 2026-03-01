<?php 
include '../../../Database/koneksi.php';
session_start();

if (!isset($_SESSION['nama'])) {
    // User belum login
    header("Location: ../../Daftar login/Login.php");
    exit;
}   
    $nik  = $_SESSION['nik'] ?? '';
    $nama = $_SESSION['nama'] ?? '';

    $namaBank   = $_POST['bank_nama'];
    $namaPemilik = $_POST['bank_pemilik'];
    $nomorRek   = $_POST['bank_nomor'];

// Upload QRIS
$qrisName = "";

if (!empty($_FILES['qris']['name'])) {
    $qrisName = time() . "_" . $_FILES['qris']['name'];
    move_uploaded_file($_FILES['qris']['tmp_name'], "../../../uploads/" . $qrisName);
}

// cek apakah data sudah ada
$cek = mysqli_query($koneksi, "SELECT * FROM metode_pembayaran LIMIT 1");

if (mysqli_num_rows($cek) > 0) {
    mysqli_query($koneksi, "
        UPDATE metode_pembayaran SET
        bank_nama='$namaBank',
        bank_pemilik='$namaPemilik',
        bank_nomor='$nomorRek',
        qris_file='$qrisName'
    ");
} else {
    mysqli_query($koneksi, "
        INSERT INTO metode_pembayaran (bank_nama, bank_pemilik, bank_nomor, qris_file, nik)
        VALUES ('$namaBank', '$namaPemilik', '$nomorRek', '$qrisName' ,'$nik')
    ");
}

// setelah simpan → kembali + alert
echo "
<script>
    alert('Pengaturan berhasil disimpan!');
    window.location.href='modal_pembayaran.php';
</script>
";

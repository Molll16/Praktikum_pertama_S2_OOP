<?php
include "../../../Database/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<script>
            alert('Akses tidak valid!');
            window.location.href='modal_pembayaran.php';
          </script>";
    exit;
}

$id = $_POST['id'];
$jenis = $_POST['jenis'];
$nama = $_POST['nama'];
$nominal = $_POST['nominal'];
$periode = $_POST['periode'];
$tanggal_mulai = $_POST['tanggal_mulai'];
$tanggal_tenggat = $_POST['tanggal_tenggat'];

if ($jenis === "rutin") {

    

    $update = mysqli_query($koneksi, "
        UPDATE jenis_iuran
        SET nama_iuran='$nama', nominal='$nominal', periode='$periode', tanggal_mulai='$tanggal_mulai', tanggal_tenggat='$tanggal_tenggat'
        WHERE id='$id' AND jenis='$jenis'
    ");

} else if ($jenis === "tidak rutin") {

    

    $update = mysqli_query($koneksi, "
        UPDATE jenis_iuran
        SET nama_iuran='$nama', nominal='$nominal', periode='$periode', tanggal_mulai='$tanggal_mulai', tanggal_tenggat='$tanggal_tenggat'
        WHERE id='$id' AND jenis='$jenis'
    ");

} else {
    echo "<script>
            alert('Jenis iuran tidak valid!');
            window.location.href='modal_pembayaran.php';
          </script>";
    exit;
}

if ($update) {
    echo "<script>
            alert('Iuran berhasil diperbarui!');
            window.location.href='modal_pembayaran.php';
          </script>";
} else {
    echo "<script>
            alert('Gagal memperbarui iuran!');
            window.location.href='modal_pembayaran.php';
          </script>";
}
?>

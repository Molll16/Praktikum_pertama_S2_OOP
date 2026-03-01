<?php
include "../../../Database/koneksi.php";

if (!isset($_GET['id']) || !isset($_GET['jenis'])) {
    echo "<script>alert('Data tidak valid!'); window.location.href='modal_pembayaran.php';</script>";
    exit;
}

$id = $_GET['id'];
$jenis = $_GET['jenis'];

if ($jenis === "rutin") {
    $delete = mysqli_query($koneksi, "DELETE FROM jenis_iuran WHERE id='$id'");
} else if ($jenis === "tidak rutin") {
    $delete = mysqli_query($koneksi, "DELETE FROM jenis_iuran WHERE id='$id'");
} else {
    echo "<script>alert('Jenis iuran tidak valid!'); window.location.href='modal_pembayaran.php';</script>";
    exit;
}

if ($delete) {
    echo "<script>
            alert('Iuran berhasil dihapus!');
            window.location.href = 'modal_pembayaran.php';
          </script>";
} else {
    echo "<script>
            alert('Gagal menghapus iuran!');
            window.location.href = 'modal_pembayaran.php';
          </script>";
}
?>

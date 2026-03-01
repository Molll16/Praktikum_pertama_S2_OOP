<?php
include '../../../Database/koneksi.php';

$id = $_POST['id'] ?? 0;
$id = (int)$id;

if ($id <= 0) {
    echo "error: ID tidak valid";
    exit;
}

$sql = "DELETE FROM catatan WHERE id = $id";
if (mysqli_query($koneksi, $sql)) {
    echo "sukses";
} else {
    echo "error: " . mysqli_error($koneksi);
}
?>
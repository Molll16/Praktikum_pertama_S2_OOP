<?php
include '../../../Database/koneksi.php';

$id = $_GET['id'] ?? 0;
$q = mysqli_query($koneksi, "SELECT * FROM catatan WHERE id='$id'");
$data = mysqli_fetch_assoc($q);

header('Content-Type: application/json');
echo json_encode($data);
?>
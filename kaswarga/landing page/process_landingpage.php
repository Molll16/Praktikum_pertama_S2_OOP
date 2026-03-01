<?php
$koneksi = mysqli_connect("localhost", "root", "", "kaswarga");

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$nama = $_POST['nama'];
$email = $_POST['email'];
$pesan = $_POST['pesan'];

$query = "INSERT INTO pesan (nama, email, pesan)
          VALUES ('$nama', '$email', '$pesan')";

if (mysqli_query($koneksi, $query)) {
    echo "Berhasil mengirim pesan!";
} else {
    echo "GAGAL: " . mysqli_error($koneksi);
}
?>

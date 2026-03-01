<?php
include "../../../Database/koneksi.php";

date_default_timezone_set("Asia/Jakarta");

$tanggal_bayar = date("Y-m-d H:i:s");
$user_id       = $_POST['user_id'] ?? '';     // NIK warga dipilih
$nama_warga    = $_POST['nama_warga'] ?? '';  // nama warga dari select (hidden)
$jenis_iuran   = $_POST['jenis_iuran'] ?? '';
$iuran_id      = $_POST['iuran_id'] ?? '';
$nominal       = $_POST['nominal'] ?? 0;
$periode       = $_POST['periode'] ?? '';
$nama_iuran    = $_POST['nama_iuran'] ?? '';
$status        = "lunas";                     // pembayaran tunai langsung lunas
$metode        = "tunai";

// Fallback: jika nama_warga kosong, ambil dari tabel user_role
if ($nama_warga === '' && $user_id !== '') {
    $resNama = mysqli_query($koneksi, "SELECT nama FROM user_role WHERE nik = '$user_id' LIMIT 1");
    if ($resNama && ($row = mysqli_fetch_assoc($resNama))) {
        $nama_warga = $row['nama'];
    }
}

// Bentuk uraian otomatis (aman jika salah satu kosong)
$uraian_value = ($nama_warga !== '' && $nama_iuran !== '')
    ? ($nama_warga . " telah membayar iuran " . $nama_iuran)
    : "";

// Cek apakah kolom 'uraian' ada di tabel iuran_warga (hindari fatal error)
$hasUraian = false;
$desc = mysqli_query($koneksi, "SHOW COLUMNS FROM iuran_warga LIKE 'uraian'");
if ($desc && mysqli_num_rows($desc) > 0) {
    $hasUraian = true;
}

// =========================
//  UPLOAD BUKTI (WAJIB)
// =========================
if (empty($_FILES['bukti']['name'])) {
    die("Error: Bukti pembayaran wajib diupload.");
}

$fileName = $_FILES['bukti']['name'];
$tmpName  = $_FILES['bukti']['tmp_name'];
$ext = pathinfo($fileName, PATHINFO_EXTENSION);
$namaFileBaru = "bukti_" . time() . "_" . rand(1000,9999) . "." . $ext;

if (!move_uploaded_file($tmpName, "../../../uploads/" . $namaFileBaru)) {
    die("Error: Gagal upload bukti pembayaran.");
}

// =========================
//  SIMPAN KE DATABASE
// =========================
// Bangun bagian SET secara dinamis agar kompatibel dengan skema berbeda
$setParts = [
    "jenis_iuran   = '$jenis_iuran'",
    "iuran_id      = '$iuran_id'",
    "nominal       = '$nominal'",
    "tanggal_bayar = '$tanggal_bayar'",
    "bukti         = '$namaFileBaru'",
    "status        = '$status'",
    "metode        = '$metode'",
    "periode       = '$periode'",
    "nama_iuran    = '$nama_iuran'",
];

if ($hasUraian) {
    $setParts[] = "uraian        = '" . mysqli_real_escape_string($koneksi, $uraian_value) . "'";
}

$query = "UPDATE iuran_warga
          SET " . implode(", ", $setParts) . "
          WHERE nik = '$user_id' AND iuran_id = '$iuran_id'";

if (mysqli_query($koneksi, $query)) {
    header("Location: status_pembayaran.php?success=1");
    exit;
} else {
    echo "Error: " . mysqli_error($koneksi);
}
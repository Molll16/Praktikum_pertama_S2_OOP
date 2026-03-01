<?php
include "../../../Database/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<script>
            alert('Akses tidak valid!');
            window.location.href='modal_pembayaran.php';
          </script>";
    exit;
}

// Ambil mentah
$jenis_raw       = $_POST['jenis'] ?? 'rutin';
$nama            = $_POST['nama'] ?? '';
$nominal         = $_POST['nominal'] ?? 0;
$periode         = $_POST['periode'] ?? '';
$tanggal_mulai   = $_POST['tanggal_mulai'] ?? '';
$tanggal_tenggat = $_POST['tanggal_tenggat'] ?? '';
$status          = $_POST['status'] ?? 'aktif';
$tanggal_dibuat  = $_POST['tanggal_dibuat'] ?? date('Y-m-d');

// Normalisasi jenis: dari JS "tidak-rutin" → DB "tidak rutin"
if ($jenis_raw === 'tidak-rutin') {
    $jenis = 'tidak rutin';
} else {
    $jenis = 'rutin';
}

// Escape
$nama            = mysqli_real_escape_string($koneksi, $nama);
$nominal         = (float)$nominal;
$periode         = mysqli_real_escape_string($koneksi, $periode);
$tanggal_mulai   = mysqli_real_escape_string($koneksi, $tanggal_mulai);
$tanggal_tenggat = mysqli_real_escape_string($koneksi, $tanggal_tenggat);
$status          = mysqli_real_escape_string($koneksi, $status);
$tanggal_dibuat  = mysqli_real_escape_string($koneksi, $tanggal_dibuat);
$jenis           = mysqli_real_escape_string($koneksi, $jenis);

// Validasi sederhana
if ($nama === '' || $nominal <= 0 || $periode === '' || $tanggal_mulai === '' || $tanggal_tenggat === '') {
    echo "<script>
            alert('Data belum lengkap!');
            window.location.href='modal_pembayaran.php';
          </script>";
    exit;
}

$sql = "
    INSERT INTO jenis_iuran 
        (nama_iuran, nominal, jenis, periode, tanggal_mulai, tanggal_tenggat, status, tanggal_dibuat)
    VALUES 
        ('$nama', '$nominal', '$jenis', '$periode', '$tanggal_mulai', '$tanggal_tenggat', '$status', '$tanggal_dibuat')
";

$insert = mysqli_query($koneksi, $sql);

if ($insert) {
    echo "<script>
            alert('Iuran berhasil ditambahkan!');
            window.location.href='modal_pembayaran.php';
          </script>";
} else {
    $err = mysqli_error($koneksi);
    echo "<script>
            alert('Gagal menambah iuran! Error: $err');
            window.location.href='modal_pembayaran.php';
          </script>";
}

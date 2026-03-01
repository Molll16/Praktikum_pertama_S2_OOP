<?php
include '../../../Database/koneksi.php';

// Ambil parameter dari URL
$nik_selected = $_GET['nik'] ?? '';
$jenis        = $_GET['jenis'] ?? '';

if (!$nik_selected || !$jenis) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT ji.id, ji.nama_iuran, ji.nominal, ji.periode
        FROM jenis_iuran ji
        JOIN iuran_warga iw ON iw.iuran_id = ji.id
        WHERE iw.nik = '$nik_selected'
        AND iw.status NOT IN ('lunas')
        AND ji.jenis = '$jenis'
        AND (
                -- Tahunan: cocokkan bulan/tanggal dan pastikan hari ini belum lewat tenggat
                (ji.periode = 'tahunan'
                AND DATE_FORMAT(CURDATE(), '%m-%d') BETWEEN DATE_FORMAT(ji.tanggal_mulai, '%m-%d') AND DATE_FORMAT(ji.tanggal_tenggat, '%m-%d')
                AND CURDATE() BETWEEN DATE_FORMAT(CONCAT(YEAR(CURDATE()), '-', DATE_FORMAT(ji.tanggal_mulai, '%m-%d')), '%Y-%m-%d')
                                AND DATE_FORMAT(CONCAT(YEAR(CURDATE()), '-', DATE_FORMAT(ji.tanggal_tenggat, '%m-%d')), '%Y-%m-%d'))

                -- Bulanan: hari ini harus di antara tanggal mulai dan akhir bulan
                OR (ji.periode = 'bulanan'
                    AND CURDATE() BETWEEN ji.tanggal_mulai AND LAST_DAY(CURDATE()))

                -- Sekali bayar: hari ini harus di antara tanggal mulai dan tenggat
                OR (ji.periode = 'sekali bayar'
                    AND CURDATE() BETWEEN ji.tanggal_mulai AND ji.tanggal_tenggat)
            )
        ORDER BY ji.id ASC";

$result = mysqli_query($koneksi, $sql);
$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
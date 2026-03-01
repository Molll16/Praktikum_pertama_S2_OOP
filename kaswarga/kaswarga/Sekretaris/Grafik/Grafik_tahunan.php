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

// Ambil SEMUA tahun yang ada di database
$Tahun = mysqli_query($koneksi, "
    SELECT DISTINCT YEAR(tanggal) AS tahun 
    FROM catatan
    ORDER BY tahun ASC
");

$daftarTahun = [];
while ($row = mysqli_fetch_assoc($Tahun)) {
    $daftarTahun[] = $row['tahun'];
}

// Ambil pemasukan & pengeluaran per TAHUN
$pemasukanTahunan = [];
$pengeluaranTahunan = [];

foreach ($daftarTahun as $th) {

    // pemasukan
    $q1 = mysqli_query($koneksi, "
        SELECT SUM(jumlah) AS total
        FROM catatan
        WHERE kategori='Pemasukan'
        AND YEAR(tanggal) = '$th'
    ");
    $pemasukanTahunan[$th] = mysqli_fetch_assoc($q1)['total'] ?? 0;

    // pengeluaran
    $q2 = mysqli_query($koneksi, "
        SELECT SUM(jumlah) AS total
        FROM catatan
        WHERE kategori='Pengeluaran'
        AND YEAR(tanggal) = '$th'
    ");
    $pengeluaranTahunan[$th] = mysqli_fetch_assoc($q2)['total'] ?? 0;
}


// Menghitung koordinat untuk grafik garis
$jumlahTahun = count($daftarTahun);

$xStart = 10;
$xGap = 280 / max(1, $jumlahTahun - 1); // memberikan jarak antar 1 titik

$xCoords = [];
for ($i = 0; $i < $jumlahTahun; $i++) {
    $xCoords[] = $xStart + ($xGap * $i);
}


$maxVal = max(
    !empty($pemasukanTahunan) ? max($pemasukanTahunan) : 0,
    !empty($pengeluaranTahunan) ? max($pengeluaranTahunan) : 0,
    1
);
$maxY = 90;

function convY($val, $maxVal, $maxY) {
    return $maxY - (($val / $maxVal) * $maxY);
}
// Penutup dari proses penentuan koordinat grafik garis




$pointsMasuk = "";
$circlesMasuk = "";

$i = 0;
foreach ($daftarTahun as $th) {
    $y = convY($pemasukanTahunan[$th], $maxVal, $maxY);
    $x = $xCoords[$i];

    $pointsMasuk .= "$x,$y ";
    $circlesMasuk .= "<circle cx='$x' cy='$y' r='4' fill='#22c55e'
                      data-tahun='$th' data-nilai='".$pemasukanTahunan[$th]."'></circle>";

    $i++;
}

$pointsKeluar = "";
$circlesKeluar = "";

$i = 0;
foreach ($daftarTahun as $th) {
    $y = convY($pengeluaranTahunan[$th], $maxVal, $maxY);
    $x = $xCoords[$i];

    $pointsKeluar .= "$x,$y ";
    $circlesKeluar .= "<circle cx='$x' cy='$y' r='4' fill='#ef4444'
                      data-tahun='$th' data-nilai='".$pengeluaranTahunan[$th]."'></circle>";

    $i++;
}

// Data untuk pie chart
$qPieMasuk = mysqli_query($koneksi,
    "SELECT SUM(jumlah) AS total FROM catatan
     WHERE kategori='Pemasukan'"
);

$qPieKeluar = mysqli_query($koneksi,
    "SELECT SUM(jumlah) AS total FROM catatan
     WHERE kategori='Pengeluaran'"
);

$pieMasuk = mysqli_fetch_assoc($qPieMasuk)['total'] ?? 0;
$pieKeluar = mysqli_fetch_assoc($qPieKeluar)['total'] ?? 0;

$totalPie = $pieMasuk + $pieKeluar;
$persenMasuk = $totalPie > 0 ? ($pieMasuk / $totalPie) * 100 : 0;
$persenKeluar = 100 - $persenMasuk;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Grafik Keuangan Tahunan</title>
    <link href="https://fonts.googleapis.com/css2?family=Kadwa:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="grafik_tahunan.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="shortcut icon" href="../../../foto/ICONB.png" type="image/x-icon">
</head>

<body>

<!--SIDEBAR-->
<aside class="sidebar">
    <!--Sidebar Header-->
    <header class="sidebar-header">
        <a href="#" class="header-logo">
            <img src="../../../foto/ICON.png" alt="KasWarga">
        </a>
        <button class="sidebar-toggler">
            <i class="fa-solid fa-chevron-left"></i>
        </button>
    </header>

    <nav class="sidebar-nav">
        <!--Primary Top Nav-->
        <ul class="nav-list primary-nav">
            <li class="nav-item">
                <a href="../dashboard/dashboard_sekre.php" class="nav-link">
                    <i class="fa-solid fa-house"></i>
                    <span class="nav-label">Dashboard</span>
                </a>
            </li>

            <!--Dropdown-->
            <li class="nav-item dropdown-container">
                <a href="dashboard.html" class="nav-link dropdown-toggle">
                    <i class="fa-solid fa-thumbtack"></i>
                    <span class="nav-label">Fitur</span>
                    <i class="fa-solid fa-chevron-down dropdown-icon"></i>
                </a>
                <!--dropdown Menu-->
                <ul class="dropdown-menu">
                    <li class="nav-item">
                        <a href="../riwayat transaksi/kelola_riwayat.php" class="nav-link dropdown-link">Kelola Riwayat</a>
                    </li>
                    <li class="nav-item">
                        <a href="../pembayaran/pembayaran.php" class="nav-link dropdown-link">Pembayaran</a>
                    </li>
                </ul>
            </li>
            <!--Dropdown-->
            <li class="nav-item dropdown-container">
                <a href="#" class="nav-link dropdown-toggle">
                    <i class="fa-solid fa-line-chart"></i>
                    <span class="nav-label">Grafik</span>
                    <i class="fa-solid fa-chevron-down dropdown-icon"></i>
                </a>
                <!--dropdown Menu-->
                <ul class="dropdown-menu">
                    <li class="nav-item">
                        <a href="../Grafik/Grafik_bulanan.php" class="nav-link dropdown-link">Bulanan</a>
                    </li>
                    <li class="nav-item">
                        <a href="../Grafik/Grafik_tahunan.php" class="nav-link dropdown-link">Tahunan</a>
                    </li>
                </ul>
            </li>
            <!--Dropdown-->
            <li class="nav-item dropdown-container">
                <a href="#" class="nav-link dropdown-toggle">
                    <i class="fa-solid fa-book"></i>
                    <span class="nav-label">Laporan</span>
                    <i class="fa-solid fa-chevron-down dropdown-icon"></i>
                </a>
                <!--dropdown Menu-->
                <ul class="dropdown-menu">
                    <li class="nav-item">
                        <a href="../Laporan/laporan bulanan.php" class="nav-link dropdown-link">Bulanan</a>
                    </li>
                    <li class="nav-item">
                        <a href="../Laporan/laporan tahunan.php" class="nav-link dropdown-link">Tahunan</a>
                    </li>
                </ul>
            </li>
            <!--Dropdown-->
            <li class="nav-item dropdown-container">
                <a href="#" class="nav-link dropdown-toggle">
                    <i class="fa-solid fa-cog"></i>
                    <span class="nav-label">Pengaturan</span>
                    <i class="fa-solid fa-chevron-down dropdown-icon"></i>
                </a>
                <!--dropdown Menu-->
                <ul class="dropdown-menu">
                    <li class="nav-item">
                        <a href="../../Daftar login/logout.php" class="nav-link dropdown-link">Keluar</a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</aside>


<div class="container">
        <h1>Grafik Keuangan Tahunan</h1>

        <div class="layout">
            <div class="left">
                <!-- Grafik Pemasukan -->
                <div class="card">
                    <h3>Pemasukan Tahunan</h3>
                    <div class="line-wrap">
                        <div class="grid-lines">
                            <svg class="line" viewBox="0 0 300 100" preserveAspectRatio="none">
                                <polyline points="<?= $pointsMasuk ?>" stroke="#22c55e" stroke-width="3" fill="none"></polyline>

                                <?= $circlesMasuk ?>
                            </svg>

                            <div class="tooltip" id="tooltip-year"></div>
                        </div>

                        <div class="year-labels">
                            <?php foreach ($daftarTahun as $th): ?>
                                <span><?= $th ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Grafik Pengeluaran -->
                <div class="card">
                    <h3>Pengeluaran Tahunan</h3>
                    <div class="line-wrap">
                        <div class="grid-lines">
                            <svg class="line" viewBox="0 0 300 100" preserveAspectRatio="none">
                                <polyline points="<?= $pointsKeluar ?>" stroke="#ef4444" stroke-width="3" fill="none"></polyline>

                                <?= $circlesKeluar ?>
                            </svg>

                            <div class="tooltip" id="tooltip-year"></div>
                        </div>

                        <div class="year-labels">
                            <?php foreach ($daftarTahun as $th): ?>
                                <span><?= $th ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pie di kanan -->
            <aside class="right">
                <div class="card pie-card">
                    <h3 style="margin-bottom:6px">Proporsi Pemasukan & Pengeluaran</h3>
                    <div class="pie"
                        style="background:
                        conic-gradient(
                         #22c55e <?= $persenMasuk ?>%, 
                         #ef4444 <?= $persenMasuk ?>%
                             );
                          ">
                    </div>
                    <div class="legend">
                        <div class="row"><span class="dot-green"></span> Pemasukan (Rp.<?= number_format($pieMasuk) ?>)</div>
                        <div class="row"><span class="dot-red"></span> Pengeluaran (Rp.<?= number_format($pieKeluar) ?>)</div>
                    </div>
                </div>
            </aside>
        </div>
</div>

    <script src="grafik_tahunan_ok.js"></script>

</body>

</html>
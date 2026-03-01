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

// Membuat pemilihan tahun menjadi dynamic
$qTahun = mysqli_query($koneksi,
    "SELECT DISTINCT YEAR(tanggal) AS th
     FROM catatan
     ORDER BY th ASC"
);

$daftarTahun = [];
while ($r = mysqli_fetch_assoc($qTahun)) {
    $daftarTahun[] = $r['th'];
}

// Ambil bulan & tahun dari dropdown
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

// Nama bulan
$namaBulan = [1=>"Jan",2=>"Feb",3=>"Mar",4=>"Apr",5=>"Mei",6=>"Jun",
              7=>"Jul",8=>"Agu",9=>"Sep",10=>"Okt",11=>"Nov",12=>"Des"];

// Siapkan array nilai
$pemasukan = [];
$pengeluaran = [];

// Ambil total pemasukan/pengeluaran per bulan
for ($i = 1; $i <= 12; $i++) {

    // Pemasukan
    $q1 = mysqli_query($koneksi,
        "SELECT SUM(jumlah) AS total FROM catatan
         WHERE kategori='Pemasukan'
         AND MONTH(tanggal)='$i'
         AND YEAR(tanggal)='$tahun'"
    );
    $pemasukan[$i] = mysqli_fetch_assoc($q1)['total'] ?? 0;

    // Pengeluaran
    $q2 = mysqli_query($koneksi,
        "SELECT SUM(jumlah) AS total FROM catatan
         WHERE kategori='Pengeluaran'
         AND MONTH(tanggal)='$i'
         AND YEAR(tanggal)='$tahun'"
    );
    $pengeluaran[$i] = mysqli_fetch_assoc($q2)['total'] ?? 0;
}

// Skala grafik
$maxVal = max(max($pemasukan), max($pengeluaran), 1);
$maxY = 90;

// Konversi nilai menjadi posisi Y pada grafik SVG
function convY($nilai, $maxVal, $maxY) {
    return $maxY - (($nilai / $maxVal) * $maxY);
}

// pos X tiap bulan (sesuaikan kalau mau)
$x = [1=>10, 35, 60, 85, 110, 135, 160, 185, 210, 235, 260, 285];

// Generate polyline & circle pemasukan
$pointsMasuk = "";
$circlesMasuk = "";

for ($i=1; $i<=12; $i++) {
    $y = convY($pemasukan[$i], $maxVal, $maxY);
    $pointsMasuk .= $x[$i].",".$y." ";
    $circlesMasuk .= "<circle cx='".$x[$i]."' cy='".$y."' r='4'
                      class='point' data-type='Pemasukan'
                      data-bulan='".$namaBulan[$i]."'
                      data-nilai='".$pemasukan[$i]."' fill='#22c55e'></circle>";
}

// Generate polyline & circle pengeluaran
$pointsKeluar = "";
$circlesKeluar = "";

for ($i=1; $i<=12; $i++) {
    $y = convY($pengeluaran[$i], $maxVal, $maxY);
    $pointsKeluar .= $x[$i].",".$y." ";
    $circlesKeluar .= "<circle cx='".$x[$i]."' cy='".$y."' r='4'
                       class='point' data-type='Pengeluaran'
                       data-bulan='".$namaBulan[$i]."'
                       data-nilai='".$pengeluaran[$i]."' fill='#ef4444'></circle>";
}

// TOTAL PEMASUKAN & PENGELUARAN BULAN INI MENGGUNAKAN PIE CHART
$qMasukPie = mysqli_query($koneksi,
    "SELECT SUM(jumlah) AS total FROM catatan
     WHERE kategori='Pemasukan'
     AND YEAR(tanggal)='$tahun'"
);
$pemasukan_bulan_ini = mysqli_fetch_assoc($qMasukPie)['total'] ?? 0;

$qKeluarPie = mysqli_query($koneksi,
    "SELECT SUM(jumlah) AS total FROM catatan
     WHERE kategori='Pengeluaran'
     AND YEAR(tanggal)='$tahun'"
);
$pengeluaran_bulan_ini = mysqli_fetch_assoc($qKeluarPie)['total'] ?? 0;

// Hitung persentase pie
$totalPie = $pemasukan_bulan_ini + $pengeluaran_bulan_ini;
$persenMasuk = $totalPie > 0 ? ($pemasukan_bulan_ini / $totalPie) * 100 : 0;
$persenKeluar = 100 - $persenMasuk;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Grafik Keuangan Bulanan</title>
    <link href="https://fonts.googleapis.com/css2?family=Kadwa:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="grafik_bulanan_oke.css">
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
                <a href="../dashboard/dashboard_warga.php" class="nav-link">
                    <i class="fa-solid fa-house"></i>
                    <span class="nav-label">Dashboard</span>
                </a>
            </li>

            <!--Dropdown-->
            <li class="nav-item dropdown-container">
                <a href="#" class="nav-link dropdown-toggle">
                    <i class="fa-solid fa-thumbtack"></i>
                    <span class="nav-label">Fitur</span>
                    <i class="fa-solid fa-chevron-down dropdown-icon"></i>
                </a>
                <!--dropdown Menu-->
                <ul class="dropdown-menu">
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
                        <a href="../Grafik/grafik_bulanan.php" class="nav-link dropdown-link">Bulanan</a>
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


<!---PAGE CONTENT-->
<div class="container">
        <h1>Grafik Keuangan Bulanan</h1>


        <form method="GET">
            <select class="select-year" name="tahun" onchange="this.form.submit()">
                <?php for ($t=2020; $t<=date("Y"); $t++): ?>
                    <option value="<?= $t ?>" <?= ($t==$tahun?'selected':'') ?>>
                        <?= $t ?>
                    </option>
                <?php endfor ?>
            </select>
        </form>

        <div class="layout">
            <div class="left">
                <!-- Grafik Pemasukan -->
                <div class="card">
                    <h3>Pemasukan Bulanan</h3>
                    <div class="line-wrap">
                        <div class="grid-lines">
                            <svg class="line" viewBox="0 0 300 100" preserveAspectRatio="none">
                                <polyline points="<?= $pointsMasuk ?>"
                                    fill="none" stroke="#16a34a" stroke-width="3">
                                </polyline>

                                <?= $circlesMasuk ?>
                            </svg>

                            <div class="tooltip" id="tooltip"></div>
                        </div>
                        <div class="month-labels">
                            <span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span><span>Mei</span><span>Jun</span>
                            <span>Jul</span><span>Agu</span><span>Sep</span><span>Okt</span><span>Nov</span><span>Des</span>
                        </div>
                    </div>
                </div>

                <!-- Grafik Pengeluaran -->
                <div class="card">
                    <h3>Pengeluaran Bulanan</h3>
                    <div class="line-wrap">
                        <div class="grid-lines">
                            <svg class="line" viewBox="0 0 300 100" preserveAspectRatio="none">
                                <polyline points="<?= $pointsKeluar ?>"
                                    fill="none" stroke="#ef4444" stroke-width="3">
                                </polyline>

                                <!-- Titik pengeluaran -->
                                <?= $circlesKeluar ?>
                            </svg>

                            <div class="tooltip" id="tooltip"></div>
                        </div>

                        <div class="month-labels">
                            <span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span><span>Mei</span><span>Jun</span>
                            <span>Jul</span><span>Agu</span><span>Sep</span><span>Okt</span><span>Nov</span><span>Des</span>
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
                        <div class="row"><span class="dot-green"></span> Pemasukan (Rp.<?= number_format($pemasukan_bulan_ini) ?>)</div>
                        <div class="row"><span class="dot-red"></span> Pengeluaran (Rp.<?= number_format($pengeluaran_bulan_ini) ?>)</div>
                    </div>
                </div>
            </aside>
        </div>
</div>

    

    <script src="grafik_bulanan_ok.js"></script>

</body>

</html>
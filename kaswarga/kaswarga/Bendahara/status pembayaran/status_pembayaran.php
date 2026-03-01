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

// Ambil iuran yang dipilih (contoh: R12 atau N5)
$selectedIuran = $_GET['iuran'] ?? "";

// Total user (warga + ketua + bendahara)
$totalUser = mysqli_fetch_assoc(mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total FROM user_role"
))['total'];

$jumlahBelum = 0;
$jenisFull   = "";
$idIuran     = "";

// Ambil daftar iuran
$iuran_rutin = mysqli_query($koneksi, "SELECT * FROM jenis_iuran WHERE jenis = 'rutin'");
$iuran_non   = mysqli_query($koneksi, "SELECT * FROM jenis_iuran WHERE jenis = 'tidak rutin'");

// Pembayaran terbaru (10 data terakhir)
$rekap = mysqli_query($koneksi, "
    SELECT * FROM catatan
    ORDER BY tanggal DESC
    LIMIT 10
");

// DATA BELUM BAYAR & SUDAH BAYAR
if ($selectedIuran !== "") {
    // Tentukan jenis rutin / nonrutin
    $jenis   = substr($selectedIuran, 0, 1); // R atau N
    $idIuran = substr($selectedIuran, 1);

    // Sesuaikan dengan isi DB (huruf kecil)
    $jenisFull = ($jenis == "R") ? "rutin" : "tidak rutin";

    // Hitung yang sudah bayar
    $sudah = mysqli_fetch_assoc(mysqli_query(
        $koneksi,
        "SELECT COUNT(*) AS total 
         FROM iuran_warga 
         WHERE jenis_iuran='$jenisFull' 
           AND iuran_id='$idIuran' 
           AND status='lunas'"
    ))['total'];

    // Belum bayar
    $jumlahBelum = $totalUser - $sudah;

    // Ambil data warga yang belum bayar
    $belum = mysqli_query($koneksi, "
    SELECT 
        ur.nama AS nama_warga,
        j.nominal,
        iw.status
    FROM iuran_warga iw
    LEFT JOIN user_role ur ON ur.nik = iw.nik
    LEFT JOIN jenis_iuran j ON iw.iuran_id = j.id
    WHERE iw.jenis_iuran = '$jenisFull'
      AND iw.iuran_id = '$idIuran'
      AND iw.status IN ('pending', 'belum lunas', 'ditolak')
");

    // Ambil data warga yang sudah bayar
    $dataSudah = mysqli_query($koneksi, "
        SELECT iw.nik,
               ur.nama AS nama_warga,
               iw.jenis_iuran,
               j.nama_iuran,
               j.nominal,
               iw.tanggal_bayar
        FROM iuran_warga iw
        LEFT JOIN user_role ur ON ur.nik = iw.nik
        LEFT JOIN jenis_iuran j ON iw.iuran_id = j.id
        WHERE iw.status = 'lunas'
          AND iw.jenis_iuran = '$jenisFull'
          AND iw.iuran_id   = '$idIuran'
        ORDER BY iw.tanggal_bayar DESC
    ");
} else {
    // Tidak memilih iuran → kosong
    $belum = mysqli_query($koneksi, "SELECT * FROM user_role WHERE 1=0");
    $dataSudah = mysqli_query($koneksi, "SELECT * FROM iuran_warga WHERE 1=0");
}

// Ambil semua user untuk dropdown input
$warga = mysqli_query($koneksi, "SELECT * FROM user_role");
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Catatan Iuran</title>
    <link rel="stylesheet" href="status_pembayaran_ok.css">
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
            <i class="fa-solid fa-chevron-left toggle-icon"></i>
        </button>
    </header>

    <nav class="sidebar-nav">
        <!--Primary Top Nav-->
        <ul class="nav-list primary-nav">
            <li class="nav-item">
                <a href="../dashboard/dashboard.php" class="nav-link">
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
                        <a href="../status pembayaran/status_pembayaran.php" class="nav-link dropdown-link">Catatan Iuran</a>
                    </li>
                    <li class="nav-item">
                        <a href="../catatan pemasukan dan pengeluaran/catatan pemasukan dan pengeluaran.php" class="nav-link dropdown-link">Catatan Keuangan</a>
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



<div class="container">
    <div class="header">
        <h1 class="title">Catatan Iuran</h1>

        <!-- Dropdown iuran -->
        <select class="year-select" onchange="window.location='?iuran=' + this.value;">
            <option selected disabled>Pilih Iuran</option>

            <?php mysqli_data_seek($iuran_rutin, 0); ?>
            <?php while ($r = mysqli_fetch_assoc($iuran_rutin)): ?>
                <option value="R<?= $r['id'] ?>" <?= ($selectedIuran == "R".$r['id'])?"selected":"" ?>>
                    <?= $r['nama_iuran'] ?> (Rutin)
                </option>
            <?php endwhile; ?>

            <?php mysqli_data_seek($iuran_non, 0); ?>
            <?php while ($n = mysqli_fetch_assoc($iuran_non)): ?>
                <option value="N<?= $n['id'] ?>" <?= ($selectedIuran == "N".$n['id'])?"selected":"" ?>>
                    <?= $n['nama_iuran'] ?> (Tidak Rutin)
                </option>
            <?php endwhile; ?>
        </select>
    </div>


    <!-- SUMMARY -->
    <div class="summary">
        <a id="btn-sudah-bayar" class="summary-card">
            <div><span class="summary-card-title">Sudah Bayar</span></div>
        </a>

        <a id="btn-belum-bayar" class="inbox-with-notification summary-card">
            <div><span class="summary-card-title">Belum Bayar</span></div>
            <?php if ($jumlahBelum > 0): ?>
                <span class="badge-counter"><?= $jumlahBelum ?></span>
            <?php endif; ?>
        </a>
    </div>


    <!-- TABEL PEMBAYARAN TERKINI -->
    <div class="rekap">
        <div class="rekap-title">Pembayaran Terkini</div>
    </div>

    <div class="table-wrapper">
        <table class="rekap-table" id="rekap-table">
            <tr>
                <thead>
                    <th>Tanggal</th>
                    <th>Nama</th>
                    <th>Jenis Iuran</th>
                    <th>Nominal</th>
                    <th>Bukti</th>
                </thead>
            </tr>
                
            <?php while ($r = mysqli_fetch_assoc($rekap)): ?>
            <tr>
                <td><?= date("D / d-m-Y", strtotime($r['tanggal'])) . " pukul " . date("H:i", strtotime($r['tanggal'])) ?></td>
                <td><?= $r['nama'] ?></td>
                <td><?= $r['nama_iuran'] ?></td>
                <td>Rp. <?= number_format($r['jumlah']) ?></td>
                <td>
                    <?php if ($r['bukti']): ?>
                        <a href="../../../uploads/<?= $r['bukti'] ?>" target="_blank" class="btn-view">Lihat</a>
                    <?php else: ?> - <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <button id="openInputModal" class="fab-btn">+</button>
</div>




<!-- POPUP INPUT PEMBAYARAN TUNAI -->
<div id="inputModal" class="modal hidden">
    <div class="modal-content">
        <span id="closeInputModal" class="close-btn">&times;</span>
        <h2>Input Pembayaran Tunai</h2>

        <form id="formTunai" action="simpan_pembayaran.php" method="POST" enctype="multipart/form-data">
        
        <!-- Nama Warga -->
        <label>Nama Warga</label>
        <select name="user_id" id="userSelect" required>
            <option value="">-- Pilih Warga --</option>
            <?php while ($w = mysqli_fetch_assoc($warga)): ?>
                <option value="<?= $w['nik'] ?>" data-nama="<?= $w['nama'] ?>">
                <?= $w['nama'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <!-- hidden input untuk kirim nama warga -->
        <input type="hidden" name="nama_warga" id="namaWarga">

        <!-- Jenis Iuran -->
        <label>Jenis Iuran</label>
        <select name="jenis_iuran" id="jenisSelect" required>
            <option value="">-- Pilih Jenis --</option>
            <option value="rutin">Iuran Rutin</option>
            <option value="tidak rutin">Iuran Tidak Rutin</option>
        </select>

        <!-- Kategori Iuran -->
        <label>Kategori Iuran</label>
        <select name="iuran_id" id="kategoriSelect" required>
            <option value="">-- Pilih Kategori --</option>
            <!-- opsi akan diisi via get_kategori_iuran.php -->
        </select>

        <!-- Nominal -->
        <label>Nominal</label>
        <input type="number" name="nominal" id="nominal" readonly required>

        <!-- Periode -->
        <label>Periode</label>
        <input type="text" name="periode" id="periode" readonly>

        <!-- Nama Iuran -->
        <input type="hidden" name="nama_iuran" id="nama_iuran">

        <!-- Metode -->
        <input type="hidden" name="metode" value="tunai">

        <!-- Bukti Pembayaran (wajib) -->
        <label>Bukti Pembayaran</label>
        <input type="file" name="bukti" accept="image/*,application/pdf" required>

        <button type="submit" class="btn-submit">Simpan Pembayaran</button>
        </form>
    </div>
</div>



<!-- POPUP SUDAH BAYAR -->
<div id="sudah-bayar" class="modal hidden">
    <div class="modal-content">
        <span class="close-btn1">&times;</span>
        <h2>Sudah Bayar</h2>

        <table class="sudah-bayar-table">
            <tr>
                <thead>
                    <th>Nama Warga</th>
                    <th>Nominal</th>
                    <th>Tanggal</th>
                </thead>
            </tr>

            <?php if (mysqli_num_rows($dataSudah) > 0): ?>
            <?php mysqli_data_seek($dataSudah, 0); ?>
            <?php while ($s = mysqli_fetch_assoc($dataSudah)): ?>
                <tr>
                    <td><?= $s['nama_warga'] ?></td>
                    <td>Rp. <?= number_format($s['nominal'], 0, ',', '.') ?></td>
                    <td><?= date("D / d-m-Y", strtotime($s['tanggal_bayar'])) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5" class="empty">Belum ada warga yang bayar</td></tr>
        <?php endif; ?>
        </table>
    </div>
</div>


<!-- POPUP BELUM BAYAR -->
<div id="belum-bayar" class="modal hidden">
    <div class="modal-content">
        <span class="close-btn2">&times;</span>
        <h2>Belum Bayar</h2>

        <table class="belum-bayar-table">
            <tr>
                <thead>
                    <th>Nama Warga</th>
                    <th>Nominal</th>
                    <th>Status</th>
                </thead>
            </tr>

            <?php if (mysqli_num_rows($belum) > 0): ?>
                <?php mysqli_data_seek($belum, 0); ?>
                <?php while ($b = mysqli_fetch_assoc($belum)): ?>
                    <tr>
                        <td><?= $b['nama_warga'] ?></td>
                        <td>Rp. <?= number_format($b['nominal']) ?></td>
                        <td style="color:
                            <?= ($b['status'] == 'lunas') ? 'green' : 
                            (($b['status'] == 'pending') ? 'orange' : 
                            (($b['status'] == 'ditolak') ? 'gray' : 'red')) ?>;">
                            <?= ucfirst($b['status']) ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align:center; color:gray;">
                        Semua warga sudah bayar untuk iuran ini
                    </td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</div>

<script src="status_pembayaran.js"></script>
<script>

    


</script>
</body>
</html>

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
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Riwayat Bukti</title>

    <link rel="stylesheet" href="kelola_riwayat_oke.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="shortcut icon" href="../../../foto/ICONB.png" type="image/x-icon">
</head>

<body>

<div class="layout">
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

<!-- ================= MAIN CONTENT ================= -->
<div class="container">
    <header class="top-header">
        <h1>Kelola Bukti Pembayaran</h1>
    </header>

    <?php
    $data = mysqli_query($koneksi, "SELECT iw.*,
            ji.nama_iuran,
            ur.nama AS nama_warga
        FROM iuran_warga iw
        JOIN jenis_iuran ji ON iw.iuran_id = ji.id
        JOIN user_role ur   ON iw.nik = ur.nik
        WHERE iw.bukti IS NOT NULL
        AND iw.status IN ('pending','ditolak')
        ORDER BY iw.tanggal_bayar DESC;
            ");
    ?>

<div class="table-wrapper">
<table class="arsip-table">
    <tr>
        <th>No</th>
        <th>Nama Pembayar</th>
        <th>Jenis Iuran</th>
        <th>Nama Iuran</th>
        <th>Nominal</th>
        <th>Status</th>
        <th>Bukti</th>
        <th>Aksi</th>
    </tr>

    <?php $no = 1; ?>
    <?php while ($row = mysqli_fetch_assoc($data)): ?>
    <tr>
        <td><?= $no++; ?></td>
        <td><?= htmlspecialchars($row['nama']); ?></td>
        <td><?= htmlspecialchars($row['jenis_iuran']); ?></td>
        <td><?= htmlspecialchars($row['nama_iuran']); ?></td>
        <td>Rp<?= number_format($row['nominal'], 0, ',', '.'); ?></td>

        <td style="font-weight:700; color:
            <?= $row['status'] == 'lunas' 
                ? 'green' 
                : ($row['status'] == 'belum lunas' 
                    ? 'red' 
                    : ($row['status'] == 'ditolak' 
                        ? 'red' 
                        : 'orange'
                    )
                ); ?>">
            <?= htmlspecialchars($row['status']); ?>
        </td>

        <td>
            <div class="btn-view">
            <a href="../../../uploads/<?= $row['bukti']; ?>" target="_blank">Lihat</a>
            </div>
        </td>

        <td>
            <a href="verifikasi_bukti.php?id=<?= $row['id']; ?>&aksi=terima"
            onclick="return confirm('Terima pembayaran ini?');"
            class="btn-acc">Terima</a>

            <a href="verifikasi_bukti.php?id=<?= $row['id']; ?>&aksi=tolak"
            onclick="return confirm('Tolak pembayaran ini?');"
            class="btn-rej">Tolak</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
</div>

</div>
</div>

<script src="kelola_riwayat_ok.js"></script>
</body>
</html>

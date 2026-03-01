<?php
include '../../../Database/koneksi.php';

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// 1) Ambil semua catatan untuk menghitung running saldo global
$dataSaldo = mysqli_query($koneksi, "
    SELECT id, tanggal, kategori, jumlah
    FROM catatan
    ORDER BY tanggal ASC, id ASC
");

if (!$dataSaldo) {
    die("Query dataSaldo gagal: " . mysqli_error($koneksi));
}

$runningSaldo = [];
$saldo = 0;

while ($row = mysqli_fetch_assoc($dataSaldo)) {
    $nominal = (int)$row['jumlah'];
    // Jika di DB kategori memakai huruf besar, aman-kan dengan strtolower
    $kat = strtolower($row['kategori']);
    if ($kat === 'pemasukan') {
        $saldo += $nominal;
    } else if ($kat === 'pengeluaran') {
        $saldo -= $nominal;
    }
    $runningSaldo[$row['id']] = $saldo;
}

// 2) Ambil data 30 hari terakhir untuk ditampilkan
$data = mysqli_query($koneksi, "SELECT * FROM catatan WHERE tanggal >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) ORDER BY tanggal DESC, id ASC;");

if (!$data) {
    die("Query data tampil gagal: " . mysqli_error($koneksi));
}

$no = 1;
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=devide-width,initial-scale=1.0">
        <title>Pemasukan dan Pengeluaran</title>
        <link rel="stylesheet" href="catatan_masuk_keluar_oke.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
        <link rel="shortcut icon" href="../../../foto/ICONB.png" type="image/x-icon">
        <style>


        </style>
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
        <h1>Catatan Keuangan</h1>

        <div class="transaction-box">
            <div class="header-controls">
                <h3>Riwayat Pemasukan dan Pengeluaran</h3>
                <button class="btn-value" onclick="window.location.href='pop up pemasukan dan pengeluaran.php'">+ Tambah</button>
            </div>

    <div class="tabel-container">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kategori</th>
                <th>Uraian</th>
                <th>Nominal</th>
                <th>Saldo</th>
                <th>Bukti</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php while ($row = mysqli_fetch_assoc($data)) : ?>
                <tr data-id="<?= $row['id'] ?>"
                    data-tanggal="<?= htmlspecialchars($row['tanggal']) ?>"
                    data-kategori="<?= htmlspecialchars($row['kategori']) ?>"
                    data-uraian="<?= htmlspecialchars($row['uraian']) ?>"
                    data-jumlah="<?= htmlspecialchars($row['jumlah']) ?>"
                    data-nama="<?= htmlspecialchars($row['nama']) ?>"
                    data-nama_transaksi="<?= htmlspecialchars($row['nama_iuran']) ?>"
                    data-metode="<?= htmlspecialchars($row['metode']) ?>"
                    data-alamat="<?= htmlspecialchars($row['alamat']) ?>">

                    <td><?= $no++; ?></td>
                    <td class="col-tanggal"><?= htmlspecialchars($row['tanggal']); ?></td>
                    <td class="col-kategori"><?= htmlspecialchars($row['kategori']); ?></td>
                    <td class="col-uraian"><?= htmlspecialchars($row['uraian']); ?></td>
                    <td class="col-jumlah">Rp. <?= number_format($row['jumlah']) ?></td>
                    <td>Rp <?= number_format($runningSaldo[$row['id']] ?? 0, 0, ',', '.'); ?></td>
                    <td>
                        <?php if (!empty($row['bukti'])) : ?>
                            <a href="../../../uploads/<?= htmlspecialchars($row['bukti']); ?>" target="_blank" class="btn-view">Lihat</a>
                        <?php else : ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="container-button-value">
                            <button class="btn-submit" onclick="editTransaksi(<?= (int)$row['id'] ?>)">Edit</button>
                            <button class="btn-delete" onclick="deleteTransaksi(<?= (int)$row['id'] ?>)">Delete</button>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</div>

<!-- Modal Edit -->
<div id="editModal" style="display:none;">
    <div class="modal-content" style="padding: 20px;">
        <h2>Edit Catatan</h2>
        <form id="editForm">
            <input type="hidden" name="id" id="edit_id">

            <label>Nama Transaksi</label>
            <input type="text" name="nama_transaksi" id="edit_nama_transaksi">

            <label>Nama</label>
            <input type="text" name="nama" id="edit_nama">

            <label>Kategori</label>
            <select name="kategori" id="edit_kategori">
                <option value="Pemasukan">Pemasukan</option>
                <option value="Pengeluaran">Pengeluaran</option>
            </select>

            <label>Jumlah</label>
            <input type="number" name="jumlah" id="edit_jumlah">

            <label>Metode Pembayaran</label>
            <select name="metode" id="edit_metode">
                <option value="tunai">Tunai</option>
                <option value="transfer">Transfer</option>
                <option value="qris">Qris</option>
            </select>

            <label>Alamat (Opsional)</label>
            <input type="text" name="alamat" id="edit_alamat"></input>

            <label>Uraian</label>
            <input type="text" name="uraian" id="edit_uraian"></input>

            <label>Bukti (Opsional)</label>
            <input type="file" id="edit_bukti" name="bukti" accept="image/*,application/pdf">

            <div class="modal-actions">
                <button type="submit" class="btn-submit">Simpan Perubahan</button>
                <button type="button" class="btn-submit" onclick="closeEdit()">Batal</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div id="editModal" style="display:none;">
    <div class="modal-content" style="padding: 20px;">
        <h2>Edit Catatan</h2>
        <form id="editForm">
            <input type="hidden" name="id" id="edit_id">

            <label>Nama Transaksi</label>
            <input type="text" name="nama_transaksi" id="edit_nama_transaksi">

            <label>Nama</label>
            <input type="text" name="nama" id="edit_nama">

            <label>Kategori</label>
            <select name="kategori" id="edit_kategori">
                <option value="Pemasukan">Pemasukan</option>
                <option value="Pengeluaran">Pengeluaran</option>
            </select>

            <label>Jumlah</label>
            <input type="number" name="jumlah" id="edit_jumlah">

            <label>Metode Pembayaran</label>
            <select name="metode" id="edit_metode">
                <option value="tunai">Tunai</option>
                <option value="transfer">Transfer</option>
                <option value="qris">Qris</option>
            </select>

            <label>Alamat (Opsional)</label>
            <input type="text" name="alamat" id="edit_alamat"></input>

            <label>Uraian</label>
            <input type="text" name="uraian" id="edit_uraian"></input>

            <label>Bukti (Opsional)</label>
            <input type="file" id="edit_bukti" name="bukti" accept="image/*,application/pdf">

            <div class="modal-actions">
                <button type="submit" class="btn-submit">Simpan Perubahan</button>
                <button type="button" class="btn-submit" onclick="closeEdit()">Batal</button>
            </div>
        </form>
    </div>
</div>

<script src="catatan.js"></script>
    </body> 
</html>
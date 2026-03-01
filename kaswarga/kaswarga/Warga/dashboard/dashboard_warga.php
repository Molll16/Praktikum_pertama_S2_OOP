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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard_ok.css">
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

    <div class="container">
        <h1>Selamat Datang, <?= $nama ?>!!!</h1>
        <h1>Ringkasan Kas</h1>
    
        <!-- Bagian Ringkasan -->
        <div class="summary-box">
            <div class="card">
                <p>Saldo</p>
                <h3>Rp. 2.000.000</h3>
            </div>
            <div class="card">
                <p>Pemasukan Bulan Ini</p>
                <h3>Rp. 4.500.000</h3>
            </div>
            <div class="card">
                <p>Pengeluaran Bulan Ini</p>
                <h3>Rp. 2.000.000</h3>
            </div>
        </div>
    
        <!-- Bagian Grafik -->
        <div class="chart-wrapper">
            <!-- Grid kiri (4 grafik garis) -->
            <div class="chart-grid">
                <div class="chart-box">
                    <h3>Pemasukan Bulanan</h3>
                    <div class="grid-lines">
                        <svg class="line" viewBox="0 0 300 100" preserveAspectRatio="none">
                            <polyline points="26,82 45,75 105,54 185,80 270,45"
                                fill="none" stroke="rgba(34,197,94,0.95)" stroke-width="3"></polyline>
                            <g fill="#22c55e">
                                <circle cx="26" cy="85" r="3"></circle>
                                <circle cx="105" cy="54" r="3"></circle>
                                <circle cx="185" cy="80" r="3"></circle>
                                <circle cx="270" cy="45" r="3"></circle>
                            </g>
                        </svg>
                    </div>
                    <div class="month-labels">
                        <span>September</span><span>Oktober</span><span>November</span><span>desember</span>
                    </div>
                </div>
                <div class="chart-box">
                    <h3>Pengeluaran Bulanan</h3>
                    <div class="grid-lines">
                        <svg class="line" viewBox="0 0 300 100" preserveAspectRatio="none">
                            <polyline points="17, 80 33, 71 105, 29 193, 75 283, 68"
                                fill="none" stroke="rgba(239,68,68,0.95)" stroke-width="3"></polyline>
                            <g fill="#ef4444">
                                <circle cx="17" cy="80" r="3.3"></circle>
                                <circle cx="105" cy="29" r="3.3"></circle>
                                <circle cx="193" cy="75" r="3.3"></circle>
                                <circle cx="283" cy="68" r="3.3"></circle>
                            </g>
                        </svg>
                    </div>
                    <div class="month-labels">
                        <span>2022</span><span>2023</span><span>2024</span><span>2025</span>
                    </div>
                </div>
                <div class="chart-box">
                    <h3>Pemasukan Tahunan</h3>
                    <div class="grid-lines">
                        <svg class="line" viewBox="0 0 300 100" preserveAspectRatio="none">
                            <polyline points="26,82 45,75 105,54 185,80 270,45" fill="none" stroke="rgba(34,197,94,0.95)" stroke-width="3">
                            </polyline>
                            <g fill="#22c55e">
                                <circle cx="26" cy="85" r="3"></circle>
                                <circle cx="105" cy="54" r="3"></circle>
                                <circle cx="185" cy="80" r="3"></circle>
                                <circle cx="270" cy="45" r="3"></circle>
                            </g>
                        </svg>
                    </div>
                    <div class="month-labels">
                        <span>2022</span><span>2023</span><span>2024</span><span>2025</span>
                    </div>
                </div>
                <div class="chart-box">
                    <h3>Pengeluaran Tahunan</h3>
                    <div class="grid-lines">
                        <svg class="line" viewBox="0 0 300 100" preserveAspectRatio="none">
                            <polyline points="17, 80 33, 71 105, 29 193, 75 283, 68" fill="none" stroke="rgba(239,68,68,0.95)"
                                stroke-width="3"></polyline>
                            <g fill="#ef4444">
                                <circle cx="17" cy="80" r="3.3"></circle>
                                <circle cx="105" cy="29" r="3.3"></circle>
                                <circle cx="193" cy="75" r="3.3"></circle>
                                <circle cx="283" cy="68" r="3.3"></circle>
                            </g>
                        </svg>
                    </div>
                    <div class="month-labels">
                        <span>2022</span><span>2023</span><span>2024</span><span>2025</span>
                    </div>
                </div>
            </div>
    
            <!-- Box kanan (diagram lingkaran) -->
            <div class="chart-box pie">
                <h3>Proporsi Pemasukan Pengeluaran</h3>
                <div class="circle-chart"></div>
                <div class="mini-circle">
                    <p><span class="green"></span> Pemasukan 65%</p>
                    <p><span class="red"></span> Pengeluaran 35%</p>
                </div>
            </div>
        </div>
    
        <!-- Bagian Tabel -->
        <div class="table-section">
            <h2>Transaksi Real-Time</h2>
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama Warga</th>
                        <th>Deskripsi</th>
                        <th>Pemasukan</th>
                        <th>Pengeluaran</th>
                        <th>Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>015/09/25</td>
                        <td>Hani</td>
                        <td>Uang Kebersihan</td>
                        <td>Rp. 1.500.000</td>
                        <td>-</td>
                        <td>Rp. 1.500.000</td>
                    </tr>
                </tbody>
                </thead>
                <tbody>
                    <tr>
                        <td>01/10/25</td>
                        <td>Aysel</td>
                        <td>Uang Kebersihan</td>
                        <td>Rp. 1.500.000</td>
                        <td>-</td>
                        <td>Rp. 3.000.000</td>
                    </tr>
                </tbody>
                </thead>
                <tbody>
                    <tr>
                        <td>01/11/25</td>
                        <td>Tara</td>
                        <td>Uang Kebersihan</td>
                        <td>Rp. 1.500.000</td>
                        <td>-</td>
                        <td>Rp. 4.500.000</td>
                    </tr>
                </tbody>
                </thead>
                <tbody>
                    <tr>
                        <td>01/11/25</td>
                        <td>Shafwan</td>
                        <td>Top Up Roblox</td>
                        <td>-</td>
                        <td>Rp. 2.000.000</td>
                        <td>Rp. 2.000.000</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

<script>
    
        // Toggle the visibility of a dropdown menu
        const toggleDropdown = (dropdown, menu, isOpen) => {
            dropdown.classList.toggle("open", isOpen)
            menu.style.height = isOpen ? `${menu.scrollHeight}px` : 0;
        }

        // Close all open dropdowns
        const closeAllDropdowns = () => {
            document.querySelectorAll(".dropdown-container.open").forEach(openDropdown => {
                toggleDropdown(openDropdown, openDropdown.querySelector(".dropdown-menu"), false);
            });
        }

        // Attach click event to all dropdown toggles
        document.querySelectorAll(".dropdown-toggle").forEach(dropdownToggle => {
            dropdownToggle.addEventListener("click", e => {
                e.preventDefault();

                const dropdown = e.target.closest(".dropdown-container");
                const menu = dropdown.querySelector(".dropdown-menu");
                const isOpen = dropdown.classList.contains("open");

                toggleDropdown(dropdown, menu, !isOpen);
            });
        });


        document.querySelector(".sidebar-toggler").addEventListener("click", () => {
            closeAllDropdowns();

            // Toggle collapsed class on sidebar
            document.querySelector(".sidebar").classList.toggle("collapsed");
        });

</script>

</body>
</html>
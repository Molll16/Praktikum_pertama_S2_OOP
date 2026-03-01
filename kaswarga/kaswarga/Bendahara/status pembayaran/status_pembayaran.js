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


// ======================================================
// FINAL FIX: SATUKAN SEMUA LOGIKA SIDEBAR DI SINI SAJA
// ======================================================

const sidebar = document.querySelector(".sidebar");
const toggler = document.querySelector(".sidebar-toggler");

toggler.addEventListener("click", () => {

    if (typeof closeAllDropdowns === "function") {
        closeAllDropdowns();
    }

    if (window.innerWidth <= 768) {
        // mode HP
        sidebar.classList.toggle("open");

        // PASTIKAN state desktop dibersihkan
        sidebar.classList.remove("collapsed");
        document.body.classList.remove("sidebar-collapsed");
    } else {
        // mode PC
        sidebar.classList.toggle("collapsed");

        // PASTIKAN state mobile dibersihkan
        sidebar.classList.remove("open");
        document.body.classList.remove("sidebar-open");
    }
});

//  reset state saat resize
window.addEventListener("resize", () => {
    if (window.innerWidth <= 768) {
        sidebar.classList.remove("collapsed");
        document.body.classList.remove("sidebar-collapsed");
    } else {
        sidebar.classList.remove("open");
        document.body.classList.remove("sidebar-open");
    }
});


// ===============================
// Modal Sudah Bayar & Belum Bayar
// ===============================
const btnSudahBayar = document.getElementById('btn-sudah-bayar');
const loadSudahBayar = document.getElementById('sudah-bayar');
const closeModalBtn1 = document.querySelector('.close-btn1');

if (btnSudahBayar && loadSudahBayar && closeModalBtn1) {
    btnSudahBayar.addEventListener('click', () => {
        loadSudahBayar.classList.remove('hidden');
    });
    closeModalBtn1.addEventListener('click', () => {
        loadSudahBayar.classList.add('hidden');
    });
}

const btnBelumBayar = document.getElementById('btn-belum-bayar');
const loadBelumBayar = document.getElementById('belum-bayar');
const closeModalBtn2 = document.querySelector('.close-btn2');

if (btnBelumBayar && loadBelumBayar && closeModalBtn2) {
    btnBelumBayar.addEventListener('click', () => {
        loadBelumBayar.classList.remove('hidden');
    });
    closeModalBtn2.addEventListener('click', () => {
        loadBelumBayar.classList.add('hidden');
    });
}

// ===============================
// Modal Input Pembayaran Tunai
// ===============================
const openInputBtn = document.getElementById("openInputModal");
const inputModal = document.getElementById("inputModal");
const closeInputBtn = document.getElementById("closeInputModal");

if (openInputBtn && inputModal && closeInputBtn) {
    openInputBtn.addEventListener("click", () => {
        inputModal.classList.remove("hidden");
        inputModal.classList.add("show");
    });

    closeInputBtn.addEventListener("click", () => {
        inputModal.classList.add("hidden");
        inputModal.classList.remove("show");
    });

    // klik area gelap = tutup modal
    inputModal.addEventListener("click", (e) => {
        if (e.target === inputModal) {
            inputModal.classList.add("hidden");
            inputModal.classList.remove("show");
        }
    });
}

// ===============================
// Autofill Kategori Iuran (Tunai)
// ===============================
const userSelect = document.getElementById("userSelect");
const jenisSelect = document.getElementById("jenisSelect");
const kategoriSelect = document.getElementById("kategoriSelect");
const nominalInput = document.getElementById("nominal");
const periodeInput = document.getElementById("periode");
const namaIuranInput = document.getElementById("nama_iuran");
const namaWargaHidden = document.getElementById("namaWarga");

// isi hidden nama_warga sesuai pilihan dropdown
if (userSelect && namaWargaHidden) {
    userSelect.addEventListener("change", () => {
        const opt = userSelect.selectedOptions[0];
        namaWargaHidden.value = opt ? (opt.dataset.nama || "") : "";
    });
}

// fetch kategori iuran sesuai nik + jenis
if (jenisSelect && kategoriSelect) {
    jenisSelect.addEventListener("change", () => {
        const nik_selected = userSelect.value;
        const jenis = jenisSelect.value;

        if (!nik_selected || !jenis) {
            kategoriSelect.innerHTML = '<option value="">-- Pilih Kategori --</option>';
            nominalInput.value = "";
            periodeInput.value = "";
            namaIuranInput.value = "";
            return;
        }

        fetch(`kategori.php?nik=${nik_selected}&jenis=${jenis}`)
            .then(res => res.json())
            .then(data => {
                kategoriSelect.innerHTML = '<option value="">-- Pilih Kategori --</option>';
                data.forEach(item => {
                    const opt = document.createElement("option");
                    opt.value = item.id;
                    opt.textContent = `${item.nama_iuran} (Rp${item.nominal})`;
                    opt.dataset.nominal = item.nominal;
                    opt.dataset.periode = item.periode;
                    opt.dataset.nama = item.nama_iuran;
                    kategoriSelect.appendChild(opt);
                });
            });
    });

    kategoriSelect.addEventListener("change", () => {
        const opt = kategoriSelect.selectedOptions[0];
        if (!opt) return;
        nominalInput.value = opt.dataset.nominal;
        periodeInput.value = opt.dataset.periode;
        namaIuranInput.value = opt.dataset.nama;
    });
}
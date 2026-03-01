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
// FUNGSI BANTU
// ===============================
function show(el) {
    el.classList.remove("hidden");
}
function hide(el) {
    el.classList.add("hidden");
}


// ===============================
// BAGIAN TAB (Rutin / Tidak Rutin)
// ===============================

const tabButtons = document.querySelectorAll(".tab");
const tabContents = document.querySelectorAll(".tab-content");

tabButtons.forEach(btn => {
    btn.addEventListener("click", () => {

        document.querySelector(".tab.active").classList.remove("active");
        btn.classList.add("active");

        document.querySelector(".tab-content.active").classList.remove("active");
        document.getElementById(btn.dataset.tab).classList.add("active");

        // Reset UI pembayaran ketika ganti tab
        const bankInfo = document.getElementById("bankInfo");
        const qrBox = document.getElementById("qrBox");
        const metodePembayaran = document.querySelectorAll('input[name="metode"]');
        if (bankInfo) hide(bankInfo);
        if (qrBox) hide(qrBox);
        metodePembayaran.forEach(r => r.checked = false);
        document.getElementById("jenisIuran").value = "";
        document.getElementById("nominal").value = "";
        document.getElementById("periode").value = "";
    });
});


// ===============================
// BAGIAN METODE PEMBAYARAN
// ===============================

// ===============================
// BAGIAN METODE PEMBAYARAN
// ===============================
const metodePembayaran = document.querySelectorAll('input[name="metode"]');
const bankInfo = document.getElementById("bankInfo");
const qrBox = document.getElementById("qrBox");

// KONDISI AWAL - SEMUA DIHIDE
if (bankInfo) {
    bankInfo.classList.add("hidden");
}
if (qrBox) {
    qrBox.classList.add("hidden");
}

metodePembayaran.forEach(radio => {
    radio.addEventListener("change", () => {
        // HIDE SEMUA DULU
        if (bankInfo) bankInfo.classList.add("hidden");
        if (qrBox) qrBox.classList.add("hidden");
        
        // SHOW YANG DIPILIH
        if (radio.value === "transfer") {
            if (bankInfo) bankInfo.classList.remove("hidden");
        }
        if (radio.value === "qris") {
            if (qrBox) qrBox.classList.remove("hidden");
        }
    });
});



// ===============================
//  AUTOFILL JENIS IURAN & NOMINAL
// ===============================

const selectRutin = document.getElementById("select-rutin");
const selectNon = document.getElementById("select-nonrutin");
const jenisIuranInput = document.getElementById("jenisIuran");
const nominalInput = document.getElementById("nominal");
const periodeInput = document.getElementById("periode");

// hidden fields in form
const hid_iuran_id = document.getElementById("iuran_id");
const hid_iuran_type = document.getElementById("iuran_type");
const hid_iuran_nama = document.getElementById("iuran_nama");

// Untuk Iuran Rutin
if (selectRutin) {
    selectRutin.addEventListener("change", () => {
        const opt = selectRutin.selectedOptions[0];
        jenisIuranInput.value = opt.dataset.nama;
        nominalInput.value = opt.dataset.nominal;
        periodeInput.value = opt.dataset.periode;

        // set hidden
        hid_iuran_id.value = opt.value;
        hid_iuran_type.value = "Rutin";
        hid_iuran_nama.value = opt.dataset.nama;

        // RESET PEMBAYARAN - FORCE HIDE
        const bankInfo = document.getElementById("bankInfo");
        const qrBox = document.getElementById("qrBox");
        if (bankInfo) bankInfo.classList.add("hidden");
        if (qrBox) qrBox.classList.add("hidden");
        document.querySelectorAll('input[name="metode"]').forEach(r => r.checked = false);
    });
}

// Untuk Iuran Tidak Rutin
if (selectNon) {
    selectNon.addEventListener("change", () => {
        const opt = selectNon.selectedOptions[0];
        jenisIuranInput.value = opt.dataset.nama;
        nominalInput.value = opt.dataset.nominal;
        periodeInput.value = opt.dataset.periode;

        // set hidden
        hid_iuran_id.value = opt.value;
        hid_iuran_type.value = "Tidak Rutin";
        hid_iuran_nama.value = opt.dataset.nama;

        // RESET PEMBAYARAN - FORCE HIDE
        const bankInfo = document.getElementById("bankInfo");
        const qrBox = document.getElementById("qrBox");
        if (bankInfo) bankInfo.classList.add("hidden");
        if (qrBox) qrBox.classList.add("hidden");
        document.querySelectorAll('input[name="metode"]').forEach(r => r.checked = false);
    });
}



// ===============================
// SEBELUM SUBMIT -> VALIDASI RINGAN
// ===============================
const formPembayaran = document.getElementById("formPembayaran");
if (formPembayaran) {
    formPembayaran.addEventListener("submit", (e) => {
        // cek apakah iuran dipilih
        if (!hid_iuran_id.value) {
            e.preventDefault();
            alert('Pilih iuran terlebih dahulu (rutin atau tidak rutin).');
            return;
        }
        // cek nama kepala
        const nama = document.getElementById("nama").value.trim();
        if (!nama) {
            e.preventDefault();
            alert('Isi Nama Kepala Keluarga.');
            return;
        }
        // nominal harus > 0
        const nom = Number(document.getElementById("nominal").value);
        if (!nom || nom <= 0) {
            e.preventDefault();
            alert('Nominal harus diisi dan lebih dari 0.');
            return;
        }

        // Optional: beri konfirmasi pengiriman (UI requested alert after send is done by redirect)
        // allow submit -> backend akan redirect kembali dengan ?success=1
    });
}
function pilihTahun(tahun) {
    window.location.href = "laporan bulanan.php?tahun=" + tahun;
}

    document.addEventListener('DOMContentLoaded', function () {
        const selectWrapper = document.querySelector('.custom-select-wrapper');
        const selected = selectWrapper.querySelector('.select-selected');
        const items = selectWrapper.querySelector('.select-items');
        const options = selectWrapper.querySelectorAll('.select-items div');

        selected.addEventListener('click', function (e) {
            e.stopPropagation();
            items.classList.toggle('active');
            this.classList.toggle('open');
        });

        options.forEach(option => {
            option.addEventListener('click', function () {
                // 1. Update teks yang dipilih
                selected.textContent = this.textContent;
                // 2. Tutup dropdown
                items.classList.remove('active');
                selected.classList.remove('open');
            });
        });

        // Tutup dropdown jika mengklik di luar
        document.addEventListener('click', function () {
            items.classList.remove('active');
            selected.classList.remove('open');
        });
    });


    //SIDEBAR

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
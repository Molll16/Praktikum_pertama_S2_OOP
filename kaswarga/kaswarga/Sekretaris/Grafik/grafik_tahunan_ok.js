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

// Untuk POPUP informasi dalam grafik
const tooltipYear = document.getElementById("tooltip-year");

document.querySelectorAll("circle").forEach(pt => {

    // DESKTOP (hover)
    pt.addEventListener("mousemove", e => {
        if (window.innerWidth <= 768) return;

        tooltipYear.style.display = "block";
        tooltipYear.style.left = (e.pageX + 10) + "px";
        tooltipYear.style.top  = (e.pageY - 20) + "px";

        tooltipYear.innerHTML = `
            <b>${pt.getAttribute("fill") === "#22c55e" ? "Pemasukan" : "Pengeluaran"}</b><br>
            Tahun: ${pt.dataset.tahun}<br>
            Nominal: Rp ${Number(pt.dataset.nilai).toLocaleString("id-ID")}
        `;
    });

    pt.addEventListener("mouseleave", () => {
        tooltipYear.style.display = "none";
    });

    // MOBILE (tap)
    pt.addEventListener("click", e => {
        e.stopPropagation();

        tooltipYear.style.display = "block";
        tooltipYear.style.left = (e.pageX + 10) + "px";
        tooltipYear.style.top  = (e.pageY - 20) + "px";

        tooltipYear.innerHTML = `
            <b>${pt.getAttribute("fill") === "#22c55e" ? "Pemasukan" : "Pengeluaran"}</b><br>
            Tahun: ${pt.dataset.tahun}<br>
            Nominal: Rp ${Number(pt.dataset.nilai).toLocaleString("id-ID")}
        `;
    });
});

// tap di luar → nutup tooltip (mobile)
document.addEventListener("click", () => {
    tooltipYear.style.display = "none";
});

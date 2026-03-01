// Toggle dropdown menu
const toggleDropdown = (dropdown, menu, isOpen) => {
    dropdown.classList.toggle("open", isOpen)
    menu.style.height = isOpen ? `${menu.scrollHeight}px` : 0;
}

// Close all dropdowns
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

// =======================
// Edit transaksi
// =======================
function editTransaksi(id) {
    const row = document.querySelector(`tr[data-id="${id}"]`);
    if (!row) return;

    // isi form dari atribut data-*
    document.getElementById("edit_id").value = id;
    document.getElementById("edit_nama_transaksi").value = row.dataset.nama_transaksi;
    document.getElementById("edit_nama").value = row.dataset.nama;
    document.getElementById("edit_jumlah").value = row.dataset.jumlah;
    document.getElementById("edit_alamat").value = row.dataset.alamat;
    document.getElementById("edit_uraian").value = row.dataset.uraian;

    // auto-fill kategori
    const kategoriValue = row.dataset.kategori.toLowerCase();
    const kategoriSelect = document.getElementById("edit_kategori");
    for (let opt of kategoriSelect.options) {
        if (opt.value.toLowerCase() === kategoriValue) {
            kategoriSelect.value = opt.value;
            break;
        }
    }

    // auto-fill metode pembayaran
    const metodeValue = row.dataset.metode.toLowerCase();
    const metodeSelect = document.getElementById("edit_metode");
    for (let opt of metodeSelect.options) {
        if (opt.value.toLowerCase() === metodeValue) {
            metodeSelect.value = opt.value;
            break;
        }
    }

    // tampilkan modal
    document.getElementById("editModal").style.display = "flex";
}

function closeEdit() {
    document.getElementById("editModal").style.display = "none";
}

document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("editForm");
    if (form) {
        form.addEventListener("submit", function (e) {
            e.preventDefault();
            let formData = new FormData(this);

            fetch("update_catatan.php", {
                method: "POST",
                body: formData
            })
            .then(r => r.text())
            .then(res => {
                if (res.trim() === "sukses") {
                    alert("Perubahan disimpan!");
                    location.reload();
                } else {
                    alert("Gagal: " + res);
                }
            })
            .catch(err => {
                console.error("Error:", err);
                alert("Terjadi kesalahan: " + err);
            });
        });
    }
});

// =======================
// Delete transaksi
// =======================
function deleteTransaksi(id) {
    console.log("ID yang dikirim:", id); // debug

    if (!id || isNaN(id)) {
        alert("ID tidak valid.");
        return;
    }

    if (!confirm("Yakin ingin menghapus catatan ini?")) return;

    fetch("delete_catatan.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "id=" + encodeURIComponent(id)
    })
    .then(res => res.text())
    .then(res => {
        if (res.trim() === "sukses") {
            alert("Catatan berhasil dihapus!");
            location.reload();
        } else {
            alert("Gagal hapus: " + res);
        }
    })
    .catch(err => {
        alert("Terjadi kesalahan: " + err);
    });
}
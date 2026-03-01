document.addEventListener("DOMContentLoaded", () => {
    console.log("modal_pembayaran_ok.js loaded");

    // ========= AUTOFILL DATA BANK =========
    const section = document.querySelector(".bank-section");
    if (section) {
        const bankId     = section.dataset.bankId || "";
        const bankNama   = section.dataset.bankNama || "";
        const bankPemilik= section.dataset.bankPemilik || "";
        const bankNomor  = section.dataset.bankNomor || "";
        const qrisFile   = section.dataset.qrisFile || "";

        const bankIdInput     = document.getElementById("bank_id");
        const bankNamaInput   = document.getElementById("bank-nama");
        const bankPemilikInput= document.getElementById("bank-pemilik");
        const bankNomorInput  = document.getElementById("bank-nomor");

        if (bankIdInput) bankIdInput.value = bankId;
        if (bankNamaInput) bankNamaInput.value = bankNama;
        if (bankPemilikInput) bankPemilikInput.value = bankPemilik;
        if (bankNomorInput) bankNomorInput.value = bankNomor;

        if (qrisFile) {
            const qrisSection = document.querySelector(".qris-section");
            if (qrisSection && !qrisSection.querySelector(".qris-preview")) {
                qrisSection.insertAdjacentHTML("beforeend", `
                    <div class="qris-preview" style="margin-top:10px;padding:12px;background:#e8f5e8;border-radius:8px;border-left:4px solid #2d6a4f;font-size:0.9rem;">
                        <strong>QRIS Saat Ini:</strong><br>
                        <a href="../../../uploads/${qrisFile}" target="_blank" style="color:#2d6a4f;text-decoration:none;font-weight:500;">
                            Lihat QR Code
                        </a>
                    </div>
                `);
            }
        }
    }

    // ========= ELEMENT MODAL =========
    const editModal   = document.getElementById("editModal");
    const closeEditBtn= document.querySelector(".close-edit");
    const editForm    = document.getElementById("editForm");

    const editId      = document.getElementById("editId");
    const editNama    = document.getElementById("editNama");
    const editNominal = document.getElementById("editNominal");
    const editPeriode = document.getElementById("editPeriode");
    const inputMulai  = document.getElementById("tanggal_mulai");
    const inputTenggat= document.getElementById("tanggal_tenggat");
    const inputStatus = document.getElementById("data-status");
    const inputDibuat = document.getElementById("data-tanggal-dibuat");
    const jenisType   = document.getElementById("jenisIuranType");

    const openEditModal  = () => editModal.classList.add("show");
    const closeEditModal = () => editModal.classList.remove("show");

    if (closeEditBtn) {
        closeEditBtn.addEventListener("click", closeEditModal);
    }
    if (editModal) {
        editModal.addEventListener("click", e => {
            if (e.target === editModal) closeEditModal();
        });
    }

    // ========= EDIT BUTTON =========
    document.querySelectorAll(".edit-btn").forEach(btn => {
        btn.addEventListener("click", () => {
            editId.value       = btn.dataset.id || "";
            editNama.value     = btn.dataset.nama || "";
            editNominal.value  = btn.dataset.nominal || "";
            editPeriode.value  = btn.dataset.periode || "";
            inputMulai.value   = btn.dataset.tanggalMulai || "";
            inputTenggat.value = btn.dataset.tanggalTenggat || "";
            inputStatus.value  = btn.dataset.status || "aktif";
            inputDibuat.value  = new Date().toISOString().split("T")[0];

            const parentTitle = btn.closest(".iuran-box").querySelector("h3").innerText.toLowerCase();
            jenisType.value = parentTitle.includes("tidak") ? "tidak rutin" : "rutin";

            editForm.setAttribute("action", "update_iuran.php");
            openEditModal();
        });
    });

    // ========= TAMBAH BUTTON =========
    document.querySelectorAll(".add-btn").forEach(btn => {
        btn.addEventListener("click", () => {
            editId.value      = "";
            editNama.value    = "";
            editNominal.value = "";
            editPeriode.value = "";
            inputMulai.value  = "";
            inputTenggat.value= "";
            inputStatus.value = "aktif";
            inputDibuat.value = new Date().toISOString().split("T")[0];

            // data-type : "rutin" atau "tidak-rutin"
            const t = btn.dataset.type;
            jenisType.value = (t === "tidak-rutin") ? "tidak-rutin" : "rutin";

            editForm.setAttribute("action", "tambah_iuran.php");
            openEditModal();
        });
    });

    // ========= DELETE BUTTON =========
    document.querySelectorAll(".delete-btn").forEach(btn => {
        btn.addEventListener("click", () => {
            const id = btn.dataset.id;
            if (!id) return;

            const parentTitle = btn.closest(".iuran-box").querySelector("h3").innerText;
            const jenis = parentTitle.includes("Tidak") ? "tidak rutin" : "rutin";

            if (confirm("Yakin ingin menghapus iuran ini?\nData tidak bisa dikembalikan!")) {
                window.location.href = `delete_iuran.php?id=${id}&jenis=${jenis}`;
            }
        });
    });

    // ========= VALIDASI FORM BANK =========
    const bankForm = document.getElementById("bankForm");
    if (bankForm) {
        bankForm.addEventListener("submit", e => {
            const bankNama    = document.getElementById("bank-nama").value.trim();
            const bankPemilik = document.getElementById("bank-pemilik").value.trim();
            const bankNomor   = document.getElementById("bank-nomor").value.trim();

            if (!bankNama || !bankPemilik || !bankNomor) {
                e.preventDefault();
                alert("Semua field rekening wajib diisi!");
                return;
            }

            if (!/^\d{10,18}$/.test(bankNomor.replace(/\s/g, ""))) {
                e.preventDefault();
                alert("Nomor rekening harus angka 10-18 digit!");
                return;
            }

            if (!confirm("Simpan perubahan rekening?")) {
                e.preventDefault();
            }
        });
    }
});

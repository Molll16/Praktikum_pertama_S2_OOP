// SIDEBAR START
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
//SIDEBAR END


    // TABEL DATA
    const form = document.getElementById("registrationForm");
    const message = document.getElementById("message");
    const table = document.getElementById("dataTable");
    const tbody = table.querySelector("tbody");
    let editIndex = null;

    form.addEventListener("submit", function (e) {

      const nama = document.getElementById("nama").value.trim();
      const nik = document.getElementById("nik").value.trim();
      const telepon = document.getElementById("telepon").value.trim();
      const password = document.getElementById("password").value.trim();

      if (msg && box) {
          box.textContent = msg;
          box.style.display = "block";
      }

      if (!nama || !nik || !telepon || !password) {
        showMessage("Lengkapi semua data sebelum melanjutkan.", "red");
        return;
      }

      if (!/^\d{16}$/.test(nik)) {
        showMessage("NIK harus terdiri dari 16 digit angka.", "red");
        return;
      }

      if (!/^\d{10,13}$/.test(telepon)) {
        showMessage("Nomor telepon harus terdiri dari 10-13 digit angka.", "red");
        return;
      }

      if (password.length < 6) {
        showMessage("Password minimal 6 karakter.", "red");
        return;
      }

      const existingRows = Array.from(tbody.querySelectorAll("tr"));
      const duplicate = existingRows.some((row, idx) =>
        row.children[3].textContent === telepon && idx !== editIndex
      );

      if (duplicate) {
        showMessage("Nomor telepon sudah digunakan, silakan gunakan nomor lain.", "red");
        return;
      }

      const username = nik.slice(-3) + telepon.slice(-3);

      if (editIndex !== null) {
        const row = tbody.children[editIndex];
        row.children[1].textContent = nama;
        row.children[2].textContent = nik;
        row.children[3].textContent = telepon;
        row.children[4].textContent = username;
        row.children[5].textContent = password;

        showMessage("Data warga berhasil diperbarui.", "green");
        editIndex = null;
        form.reset();
        modal.style.display = 'none';
        return;
      }

      const row = document.createElement("tr");
      const rowNumber = tbody.children.length + 1;
      row.innerHTML = `
        <td>${rowNumber}</td>
        <td>${nama}</td>
        <td>${nik}</td>
        <td>${telepon}</td>
        <td>${username}</td>
        <td>${password}</td>
        <td>
          <button class="edit-btn"><i class="fas fa-edit me-1"></i>Edit</button>
          <button class="delete-btn"><i class="fas fa-trash me-1"></i>Hapus</button>
        </td>
      `;

      row.querySelector(".edit-btn").addEventListener("click", () => {
        document.getElementById("nama").value     = row.children[1].textContent;
        document.getElementById("nik").value      = row.children[2].textContent;
        document.getElementById("telepon").value  = row.children[3].textContent;
        document.getElementById("password").value = row.children[5].textContent;

        editIndex = Array.from(tbody.children).indexOf(row);
        showMessage("Sedang mengedit data warga...", "orange");
        modal.style.display = 'flex';
      });

      row.querySelector(".delete-btn").addEventListener("click", () => {
        if (confirm("Apakah Anda yakin ingin menghapus data warga ini?")) {
          row.remove();
          updateRowNumbers();
          showMessage("Data warga berhasil dihapus.", "green");
          
          if (tbody.children.length === 0) {
            table.style.display = "none";
          }
        }
      });

      tbody.appendChild(row);
      table.style.display = "table";
      showMessage("Akun warga berhasil dibuat.", "green");
      form.reset();
      modal.style.display = 'none';
    });

    function updateRowNumbers() {
      const rows = tbody.querySelectorAll("tr");
      rows.forEach((row, index) => {
        row.children[0].textContent = index + 1;
      });
    }

    function showMessage(text, color) {
      message.textContent = text;
      message.style.color = color;
      message.style.backgroundColor = color === 'green' ?  '#f0fff0' : 
                                      color === 'red' ?    '#fff0f0' : 
                                      color === 'orange' ? '#fff8f0' : 'white';

      message.style.borderColor = color === 'green' ?  '#d4edda' : 
                                  color === 'red' ?    '#f8d7da' : 
                                  color === 'orange' ? '#ffeaa7' : '#e8f5e8';
    }


    // ---------------------------------MODAL TAMBAH DATA-------------------------------------------
    const modal         = document.getElementById('tambahDataModal');
    const tambahDataBtn = document.getElementById('tambahDataBtn');
    const closeModalBtn = document.getElementById('closeModalBtn');
    
    // TOMBOL TAMPILKAN MODAL TAMBAH DATA
    tambahDataBtn.addEventListener('click', function() {
      modal.style.display = 'flex';
    });
    
    // TOMBOL SEMBUNYIKAN MODAL TAMBAH DATA (JIKA DI KLIK)
    closeModalBtn.addEventListener('click', function() {
      modal.style.display = 'none';
      form.reset();
      editIndex = null;
      message.textContent = "";
    });
    
    // SEMBUNYIKAN MODAL (JIKA DI KLIK DI LUAR MODAL)
    window.addEventListener('click', function(event) {
      if (event.target === modal) {
        modal.style.display = 'none';
        form.reset();
        editIndex = null;
        message.textContent = "";
      }
    });


    // ---------------------------------MODAL EDIT DATA-------------------------------------------
    const editDataModal     = document.getElementById('editDataModal');
    const closeEditModalBtn = document.getElementById('closeEditModalBtn');
    const roleContainer     = document.getElementById('roleContainer');

    // Event listener tunggal untuk tombol Edit
    document.getElementById('dataTable').addEventListener('click', function(e) {
      const btn = e.target.closest('.edit-btn');
      if (!btn) return;

      // Ambil Data Dari Tabel
      const nik    = btn.getAttribute('data-nik');
      const nama   = btn.getAttribute('data-nama');
      const gender = btn.getAttribute('data-gender');
      const nomor  = btn.getAttribute('data-nomor');
      const alamat = btn.getAttribute('data-alamat');
      const role   = btn.getAttribute('data-role');

      // Debug
      console.log("Role yang diambil:", role);

      // Isi Input Modal
      document.getElementById('nik_lama').value    = nik;
      document.getElementById('edit_nik').value    = nik;
      document.getElementById('edit_nama').value   = nama;
      document.getElementById('edit_gender').value = gender;
      document.getElementById('edit_nomor').value  = nomor;
      document.getElementById('edit_alamat').value = alamat;

      // 🔒 Deteksi role ketua
      if (role.toLowerCase().trim() === 'ketua') {
          roleContainer.innerHTML = `
              <label class="form-label">Jabatan:</label>
              <input type="hidden" name="edit_role" value="ketua">
              <input type="text" class="form-control" value="Ketua" readonly>
          `;
      } else {
          roleContainer.innerHTML = `
              <label for="edit_role" class="form-label">Jabatan:</label>
              <select class="form-control" id="edit_role" name="edit_role" required>
                  <option value="warga" ${role === 'warga' ? 'selected' : ''}>Warga</option>
                  <option value="sekretaris" ${role === 'sekretaris' ? 'selected' : ''}>Sekretaris</option>
                  <option value="bendahara" ${role === 'bendahara' ? 'selected' : ''}>Bendahara</option>
              </select>
          `;
      }

      // Tampilkan Modal
      editDataModal.style.display = 'flex';
    });

    // Tombol Tutup Modal
    closeEditModalBtn.addEventListener('click', () => {
      editDataModal.style.display = 'none';
    });

    // Tutup Modal Jika Klik di luar
    window.addEventListener('click', function(event) {
      if (event.target === editDataModal) {
        editDataModal.style.display = 'none';
      }
    });
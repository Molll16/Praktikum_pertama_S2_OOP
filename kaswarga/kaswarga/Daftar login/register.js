document.getElementById("registerForm").addEventListener("submit", function(e) {
    e.preventDefault();

    // AMBIL VALUE
    const nik             = document.getElementById("fullName").value.trim();

    // VALIDASI NIK
    if (nik === "") {
        alert("Harap mengisi NIK");
        return;
    }

    if (!/^\d{16}$/.test(nik)) {
        alert("NIK harus terdiri dari 16 digit angka");
        return;
    }

    // JIKA SEMUA LOLOS
    this.submit();
});

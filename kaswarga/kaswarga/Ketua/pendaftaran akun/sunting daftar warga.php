<?php
    include '../../../Database/koneksi.php';

    $editData = [
    'nik' => '',
    'nama' => '',
    'gender' => '',
    'nomor' => '',
    'alamat' => '',
    'role' => ''
    ];

    $errors = [];

    if (isset($_POST['register'])) {
        $nik             = mysqli_real_escape_string($koneksi, $_POST['nik']);
        $nama            = mysqli_real_escape_string($koneksi, $_POST['nama']);
        $gender          = mysqli_real_escape_string($koneksi, $_POST['gender']);
        $alamat          = mysqli_real_escape_string($koneksi, $_POST['alamat']);
        $nomor           = mysqli_real_escape_string($koneksi, $_POST['nomor']);
        $role            = mysqli_real_escape_string($koneksi, $_POST['role']);
        $password        = mysqli_real_escape_string($koneksi, $_POST['password']);
        $passwordConfirm = mysqli_real_escape_string($koneksi, $_POST['passwordConfirm']);

        if (empty($nik))             {array_push($errors, "NIK harus di isi!"); }
        if (empty($nama))            {array_push($errors, "Nama Lengkap harus di isi!"); }
        if (empty($gender))          {array_push($errors, "Gender harus di isi!"); }
        if (empty($alamat))          {array_push($errors, "Alamat harus di isi!"); }
        if (empty($nomor))           {array_push($errors, "Nomor Telepon harus di isi!"); }
        if (empty($password))        {array_push($errors, "Password dibutuhkan"); }
        if (empty($passwordConfirm)) {array_push($errors, "Konfirmasi password dibutuhkan"); }

        if ($password != $passwordConfirm) 
            {array_push($errors, "Kedua Password tidak sama!"); }


        $nama_check_query = "SELECT * FROM `user_role` WHERE nik='$nik' OR nama='$nama'";
        $result = mysqli_query($koneksi, $nama_check_query);
        $user = mysqli_fetch_assoc($result);

        if ($user) {
            if ($user['nik'] == $nik) {
                array_push($errors, "nik already exists");
            }

            if ($user && $user['nama'] == $nama) {
                array_push($errors, "name already exists");
            }
        }

        if (count($errors) == 0) {
            $password = password_hash($password, PASSWORD_BCRYPT); //enkripsi password 

            $query = "INSERT INTO user_role (nik, nama, password, gender, alamat, nomor, role) VALUES ('$nik', '$nama', '$password', '$gender', '$alamat', '$nomor', '$role')";
            mysqli_query($koneksi, $query);
            $_SESSION['nama'] = $nama;
            $_SESSION['success'] = "Data added";
            header('location: daftar warga.php');
            exit();
        } else {
            echo "Error insert: " . mysqli_error($koneksi);
        }
    }

    // ==== EDIT DATA ====
    if (isset($_POST['edit']) && isset($_POST['edit_nik'])) {
        $niklama = mysqli_real_escape_string($koneksi, $_POST['nik_lama']);
        $nikbaru     = mysqli_real_escape_string($koneksi, $_POST['edit_nik']);
        $nama    = mysqli_real_escape_string($koneksi, $_POST['edit_nama']);
        $gender  = mysqli_real_escape_string($koneksi, $_POST['edit_gender']);
        $nomor   = mysqli_real_escape_string($koneksi, $_POST['edit_nomor']);
        $alamat  = mysqli_real_escape_string($koneksi, $_POST['edit_alamat']);
        $role    = mysqli_real_escape_string($koneksi, $_POST['edit_role']);

        $query = "UPDATE user_role 
                SET nik='$nikbaru', nama='$nama', gender='$gender', nomor='$nomor', alamat='$alamat', role='$role'
                WHERE nik='$niklama'";

        if (mysqli_query($koneksi, $query)) {
            // sukses update
            echo "<script>
                    alert('Data berhasil diperbarui');
                    window.location.href='daftar warga.php';
                </script>";
        } else {
            echo "Error: " . mysqli_error($koneksi);
        }
    }

    // ==== HAPUS DATA ====
    if (isset($_POST['delete'])) {
        $nik_delete = $_POST['delete_nik'];
        $query = "DELETE FROM user_role WHERE nik='$nik_delete'";
        if (mysqli_query($koneksi, $query)) {
            $_SESSION['message'] = "Data warga berhasil dihapus.";
        } else {
            $_SESSION['message'] = "Gagal menghapus data: " . mysqli_error($koneksi);
        }
        header("Location: daftar warga.php");
        exit();
    }
?>
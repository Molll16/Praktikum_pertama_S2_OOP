<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=devide-width,initial-scale=1.0">
        <title>tambahkan pemasuka dan pengeluaran</title>
        <link rel="stylesheet" href="popup_oke.css">
        <link rel="shortcut icon" href="../../../foto/ICONB.png" type="image/x-icon">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head>
    <body>
        <div class="modal-backdrop">
            <div class="modal-content">                   
                <h1><i class="fa-solid fa-book"></i> Tambahkan Catatan</h1>
                
                <form id="transaksiForm">
                    <div class="form-section">
                        <h3>Transaksi Data</h3>
                    </div>
        
        <table class="form-table">
            <form id="transaksiForm" action="proses_transaksi.php" method="POST" enctype="multipart/form-data">
            <tr>
                <td>Nama Transaksi</td>
            </tr>
            <tr>
                <td><input name="nama_transaksi" id="nama_transaksi" type="text"></td>
            </tr>

            <tr>
                <td>Nama Terkait</td>
            </tr>
            <tr>
                <td><input name="nama_terkait" id="nama_terkait" type="text"></td>
            </tr>

            <tr>
                <td>Pilih Kategori</td>
            </tr>
            <tr>
                <td><select name="kategori" id="kategori">
                <option value="">Pilih Kategori</option>
                <option value="Pemasukan">Pemasukan</option>
                <option value="Pengeluaran">Pengeluaran</option></select></td>
            </tr>

            <tr>
                <td>Jumlah</td>
            </tr>
            <tr>
                <td><input name="jumlah" id="jumlah" type="number"></td>
            </tr>

            <tr>
                <td>Metode Pembayaran</td>
            </tr>
            <tr>
                <td><select name="metode" id="metode">
                <option value="">Pilih Metode</option>
                <option value="tunai">Tunai</option>
                <option value="transfer">Transfer</option>
                <option value="qris">Qris</option></select></td>
            </tr>

            <tr>
                <td>Alamat (Opsional)</td>
            </tr>
            <tr>
                <td><input type="text" name="alamat" id="alamat"></input></td>
            </tr>

            <tr>
                <td>Uraian</td>
            </tr>
            <tr>
                <td><input type="text" name="uraian" id="uraian"></input></td>
            </tr>

            <tr>
                <td>Upload Bukti</td>
            </tr>
            <tr>
                <td text><input type="file" id="bukti" name="bukti" accept="image/*,application/pdf"></td>
            </tr>
            </form>
        </table>

        <div class="btn">
             <tr>
                 <td>
                     <button type="submit" class="btn-submit">Submit</button>                      
                 </td>
             </tr> 

             <button type="button" class="btn-back" onclick="window.location.href='../catatan pemasukan dan pengeluaran/catatan pemasukan dan pengeluaran.php'">Kembali</button>
        </div>

    </div>
</div>

<script src="popup.js"></script>

    </body>
</html>
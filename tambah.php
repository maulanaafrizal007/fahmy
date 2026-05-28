<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Tambah Data Barang — Toko Cemilan CKTB</title>
<style>
  body {
    font-family: Comic Sans MS, cursive;
    background-color: #fff8f0;
    margin: 30px;
  }
  h2 { color: #e65c00; }
  .form-box {
    background: #fff;
    border: 1px solid #f0c080;
    border-radius: 8px;
    padding: 24px 30px;
    max-width: 480px;
    margin-top: 16px;
  }
  .form-box label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
    margin-top: 14px;
    color: #555;
  }
  .form-box input[type="text"],
  .form-box input[type="number"],
  .form-box select {
    width: 100%;
    padding: 8px 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-family: Comic Sans MS, cursive;
    font-size: 13px;
    box-sizing: border-box;
  }
  .form-box input:focus,
  .form-box select:focus {
    border-color: #e65c00;
    outline: none;
  }
  .btn-simpan {
    margin-top: 20px;
    background-color: #e65c00;
    color: white;
    padding: 8px 22px;
    border: none;
    border-radius: 5px;
    font-family: Comic Sans MS, cursive;
    font-size: 14px;
    cursor: pointer;
  }
  .btn-simpan:hover { background-color: #bf4c00; }
  .btn-batal {
    margin-top: 20px;
    margin-left: 10px;
    background-color: #aaa;
    color: white;
    padding: 8px 18px;
    border: none;
    border-radius: 5px;
    font-family: Comic Sans MS, cursive;
    font-size: 14px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
  }
  .btn-batal:hover { background-color: #888; }
  .notif-gagal {
    background: #f8d7da; color: #721c24;
    border: 1px solid #f5c6cb;
    padding: 8px 14px; border-radius: 5px;
    margin-bottom: 12px; display: inline-block;
  }
  .notif-sukses {
    background: #d4edda; color: #155724;
    border: 1px solid #c3e6cb;
    padding: 8px 14px; border-radius: 5px;
    margin-bottom: 12px; display: inline-block;
  }
  .required { color: red; margin-left: 3px; }
</style>
</head>
<body>

<p><img src="Oranye &amp; Putih Minimalis Banner Tahu Krispi.png" width="480" height="166" alt="tahu aci" /></p>
<h2><strong><code>TAMBAH DATA BARANG CEMILAN</code></strong></h2>

<?php
// ================================================
// INSERT data baru — MySQLi Procedural
// Tabel: penjualan_cemilan
// ================================================

$conn = mysqli_connect('localhost', 'root', '', 'toko_cemilan');

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// ---- Proses INSERT jika form disubmit ----
if (isset($_POST['simpan'])) {

    $nama_barang     = trim($_POST['nama_barang']);
    $harga_barang    = (float) $_POST['harga_barang'];
    $kategori_barang = trim($_POST['kategori_barang']);

    // Validasi tidak boleh kosong
    if (empty($nama_barang) || empty($harga_barang) || empty($kategori_barang)) {
        echo '<p class="notif-gagal">&#10008; Semua kolom wajib diisi!</p>';
    } else {

        $stmt = mysqli_prepare($conn,
            "INSERT INTO penjualan_cemilan
             (nama_barang, harga_barang, kategori_barang)
             VALUES (?, ?, ?)"
        );

        // s=string, d=decimal, s=string
        mysqli_stmt_bind_param($stmt, 'sds',
            $nama_barang,
            $harga_barang,
            $kategori_barang
        );

        if (mysqli_stmt_execute($stmt)) {
            $id_baru = mysqli_insert_id($conn);
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            // Redirect ke index dengan notifikasi sukses
            header("Location: index.php?pesan=tambah_sukses");
            exit();
        } else {
            echo '<p class="notif-gagal">&#10008; Gagal menambahkan data: ' . mysqli_error($conn) . '</p>';
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<div class="form-box">
  <form method="post" action="tambah.php">

    <label>Nama Barang <span class="required">*</span></label>
    <input type="text" name="nama_barang"
           placeholder="contoh: Keripik Singkong"
           value="<?php echo isset($_POST['nama_barang']) ? htmlspecialchars($_POST['nama_barang']) : ''; ?>"
           required />

    <label>Harga Barang (Rp) <span class="required">*</span></label>
    <input type="number" name="harga_barang" step="100" min="0"
           placeholder="contoh: 5000"
           value="<?php echo isset($_POST['harga_barang']) ? htmlspecialchars($_POST['harga_barang']) : ''; ?>"
           required />

    <label>Kategori Barang <span class="required">*</span></label>
    <select name="kategori_barang" required>
      <option value="" selected="selected">-- Pilih Kategori --</option>
      <?php
      $kategori_list = ['Makanan Ringan','Cemilan','Gorengan','Kue Kering','Makanan Berat','Minuman','Lainnya'];
      foreach ($kategori_list as $kat) {
          $selected = (isset($_POST['kategori_barang']) && $_POST['kategori_barang'] == $kat) ? 'selected' : '';
          echo "<option value=\"$kat\" $selected>$kat</option>";
      }
      ?>
    </select>

    <div>
      <button type="submit" name="simpan" class="btn-simpan">&#10004; Simpan Data</button>
      <a href="index.php" class="btn-batal">&#10006; Batal</a>
    </div>

  </form>
</div>

<?php
if (isset($conn) && $conn) {
    mysqli_close($conn);
}
?>

</body>
</html>

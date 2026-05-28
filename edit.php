<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Edit Data Barang — Toko Cemilan CKTB</title>
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
  .btn-update {
    margin-top: 20px;
    background-color: #f0a500;
    color: white;
    padding: 8px 22px;
    border: none;
    border-radius: 5px;
    font-family: Comic Sans MS, cursive;
    font-size: 14px;
    cursor: pointer;
  }
  .btn-update:hover { background-color: #c88400; }
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
</style>
</head>
<body>

<h2><strong><code>EDIT DATA BARANG CEMILAN</code></strong></h2>

<?php
// ================================================
// EDIT & UPDATE data — MySQLi Procedural
// Tabel: penjualan_cemilan
// ================================================

$conn = mysqli_connect('localhost', 'root', '', 'toko_cemilan');

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// ---- Proses UPDATE jika form disubmit ----
if (isset($_POST['simpan'])) {
    $id_barang       = (int) $_POST['id_barang'];
    $nama_barang     = trim($_POST['nama_barang']);
    $harga_barang    = (float) $_POST['harga_barang'];
    $kategori_barang = trim($_POST['kategori_barang']);

    $stmt = mysqli_prepare($conn,
        "UPDATE penjualan_cemilan
         SET nama_barang     = ?,
             harga_barang    = ?,
             kategori_barang = ?
         WHERE id_barang = ?"
    );
    // s=string, d=decimal, s=string, i=integer
    mysqli_stmt_bind_param($stmt, 'sdsi',
        $nama_barang,
        $harga_barang,
        $kategori_barang,
        $id_barang
    );

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        // Redirect ke index dengan notifikasi sukses
        header("Location: index.php?pesan=update_sukses");
        exit();
    } else {
        echo '<p class="notif-gagal">Gagal memperbarui data: ' . mysqli_error($conn) . '</p>';
    }
    mysqli_stmt_close($stmt);
}

// ---- Ambil data berdasarkan ID untuk ditampilkan di form ----
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID barang tidak valid. <a href='index.php'>Kembali</a>");
}

$id_barang = (int) $_GET['id'];

$stmt = mysqli_prepare($conn,
    "SELECT * FROM penjualan_cemilan WHERE id_barang = ?"
);
mysqli_stmt_bind_param($stmt, 'i', $id_barang);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row    = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$row) {
    die("Data tidak ditemukan. <a href='index.php'>Kembali</a>");
}
?>

<div class="form-box">
  <form method="post" action="edit.php?id=<?php echo $row['id_barang']; ?>">
    <input type="hidden" name="id_barang" value="<?php echo $row['id_barang']; ?>" />

    <label>ID Barang</label>
    <input type="text" value="<?php echo $row['id_barang']; ?>" disabled />

    <label>Nama Barang</label>
    <input type="text" name="nama_barang"
           value="<?php echo htmlspecialchars($row['nama_barang']); ?>"
           required />

    <label>Harga Barang (Rp)</label>
    <input type="number" name="harga_barang" step="100" min="0"
           value="<?php echo $row['harga_barang']; ?>"
           required />

    <label>Kategori Barang</label>
    <select name="kategori_barang" required>
      <?php
      $kategori_list = ['Makanan Ringan','Cemilan','Gorengan','Kue Kering','Makanan Berat','Minuman','Lainnya'];
      foreach ($kategori_list as $kat) {
          $selected = ($row['kategori_barang'] == $kat) ? 'selected' : '';
          echo "<option value=\"$kat\" $selected>$kat</option>";
      }
      ?>
    </select>

    <div>
      <button type="submit" name="simpan" class="btn-update">&#10004; Simpan Perubahan</button>
      <a href="index.php" class="btn-batal">&#10006; Batal</a>
    </div>
  </form>
</div>

<?php mysqli_close($conn); ?>

</body>
</html>

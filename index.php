<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Tugas Pemrograman WEB II</title>
<style>
  body {
    font-family: Comic Sans MS, cursive;
    background-color: #fff8f0;
    margin: 20px;
  }
  h2 { color: #e65c00; }
  table {
    border-collapse: collapse;
    width: 100%;
    margin-top: 10px;
    background-color: #fff;
  }
  table th {
    background-color: #e65c00;
    color: white;
    padding: 10px 14px;
    text-align: center;
    border: 1px solid #ccc;
  }
  table td {
    padding: 9px 14px;
    border: 1px solid #ccc;
    text-align: center;
  }
  table tr:nth-child(even) td { background-color: #fff3e0; }
  table tr:hover td { background-color: #ffe0b2; }
  .btn {
    padding: 5px 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-family: Comic Sans MS, cursive;
    font-size: 13px;
    text-decoration: none;
    display: inline-block;
  }
  .btn-edit   { background-color: #f0a500; color: white; }
  .btn-delete { background-color: #d9534f; color: white; }
  .btn-edit:hover   { background-color: #c88400; }
  .btn-delete:hover { background-color: #b52b27; }
  .btn-tambah {
    background-color: #e65c00;
    color: white;
    padding: 7px 18px;
    border: none;
    border-radius: 5px;
    font-family: Comic Sans MS, cursive;
    font-size: 14px;
    cursor: pointer;
  }
  .btn-tambah:hover { background-color: #bf4c00; }
  .notif-sukses {
    background: #d4edda; color: #155724;
    border: 1px solid #c3e6cb;
    padding: 8px 14px; border-radius: 5px;
    margin-bottom: 12px; display: inline-block;
  }
  .notif-gagal {
    background: #f8d7da; color: #721c24;
    border: 1px solid #f5c6cb;
    padding: 8px 14px; border-radius: 5px;
    margin-bottom: 12px; display: inline-block;
  }
</style>
</head>
<body>

<p><img src="Oranye &amp; Putih Minimalis Banner Tahu Krispi.png" width="957" height="250" alt="tahu aci" /></p>
<h2><strong><code>TOKO CEMILAN CKTB &mdash; CEMILAN KHAS TEGAL BREBES</code></strong></h2>

<?php
// Tampilkan notifikasi jika ada pesan dari proses delete/update
if (isset($_GET['pesan'])) {
    if ($_GET['pesan'] == 'hapus_sukses') {
        echo '<p class="notif-sukses">&#10004; Data berhasil dihapus!</p>';
    } elseif ($_GET['pesan'] == 'update_sukses') {
        echo '<p class="notif-sukses">&#10004; Data berhasil diperbarui!</p>';
    } elseif ($_GET['pesan'] == 'tambah_sukses') {
        echo '<p class="notif-sukses">&#10004; Data berhasil ditambahkan!</p>';
    }
}
?>

<!-- Tombol Tambah Data -->
<form id="form1" name="form1" method="get" action="tambah.php" style="margin-bottom:16px;">
  <button type="submit" class="btn-tambah">+ Tambah Data Barang</button>
</form>

<?php
// ================================================
// SELECT semua data — MySQLi Procedural
// Tabel: penjualan_cemilan
// ================================================

$conn = mysqli_connect('localhost', 'root', '', 'toko_cemilan');

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$sql    = "SELECT * FROM penjualan_cemilan ORDER BY id_barang ASC";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
?>

<table>
  <thead>
    <tr>
      <th>No</th>
      <th>ID Barang</th>
      <th>Nama Barang</th>
      <th>Harga Barang</th>
      <th>Kategori Barang</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
  <?php
  $no = 1;
  while ($row = mysqli_fetch_assoc($result)) {
  ?>
    <tr>
      <td><?php echo $no++; ?></td>
      <td><?php echo $row['id_barang']; ?></td>
      <td><?php echo htmlspecialchars($row['nama_barang']); ?></td>
      <td>Rp <?php echo number_format($row['harga_barang'], 0, ',', '.'); ?></td>
      <td><?php echo htmlspecialchars($row['kategori_barang']); ?></td>
      <td>
        <!-- Tombol Edit -->
        <a href="edit.php?id=<?php echo $row['id_barang']; ?>" class="btn btn-edit">&#9998; Edit</a>
        &nbsp;
        <!-- Tombol Delete -->
        <a href="delete.php?id=<?php echo $row['id_barang']; ?>"
           class="btn btn-delete"
           onclick="return confirm('Yakin ingin menghapus barang \'<?php echo htmlspecialchars($row['nama_barang']); ?>\'?');">
          &#128465; Hapus
        </a>
      </td>
    </tr>
  <?php } ?>
  </tbody>
</table>

<?php
} else {
    echo "<p>Tidak ada data ditemukan.</p>";
}

mysqli_free_result($result);
mysqli_close($conn);
?>

</body>
</html>

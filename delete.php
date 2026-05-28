<?php
// ================================================
// DELETE data berdasarkan id_barang — MySQLi Procedural
// Tabel: penjualan_cemilan
// ================================================

$conn = mysqli_connect('localhost', 'root', '', 'toko_cemilan');

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Validasi ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id_barang = (int) $_GET['id'];

// Cek apakah data ada sebelum dihapus
$cek = mysqli_prepare($conn,
    "SELECT nama_barang FROM penjualan_cemilan WHERE id_barang = ?"
);
mysqli_stmt_bind_param($cek, 'i', $id_barang);
mysqli_stmt_execute($cek);
$hasil = mysqli_stmt_get_result($cek);
$data  = mysqli_fetch_assoc($hasil);
mysqli_stmt_close($cek);

if (!$data) {
    // Data tidak ada, kembali ke index
    header("Location: index.php");
    exit();
}

// Proses hapus data
$stmt = mysqli_prepare($conn,
    "DELETE FROM penjualan_cemilan WHERE id_barang = ?"
);
mysqli_stmt_bind_param($stmt, 'i', $id_barang);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    // Redirect ke index dengan notifikasi sukses
    header("Location: index.php?pesan=hapus_sukses");
    exit();
} else {
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header("Location: index.php?pesan=hapus_gagal");
    exit();
}
?>

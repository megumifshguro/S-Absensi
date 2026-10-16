<?php
session_start();
include "koneksi.php";
date_default_timezone_set('Asia/Jakarta');

if (!isset($_SESSION['role'])) {
    header("Location: index.php");
    exit;
}

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];
$kelas_id = $_SESSION['kelas_id'] ?? null; // untuk siswa
$nama = $_SESSION['nama'];

// QUERY SESUAI ROLE
if ($role === 'siswa') {
    // siswa hanya lihat absensi dirinya sendiri
    $sql = "SELECT a.*, u.nama, u.nis, k.nama_kelas 
            FROM absensi a
            JOIN users u ON a.user_id = u.id
            JOIN kelas k ON u.kelas_id = k.id
            WHERE u.id = '$user_id'
            ORDER BY a.tanggal DESC";
}

else if ($role === 'guru') {
    // guru bisa lihat semua dalam satu kelas
    // (ubah sesuai kebutuhan nanti)
    $sql = "SELECT a.*, u.nama, u.nis, k.nama_kelas 
            FROM absensi a
            JOIN users u ON a.user_id = u.id
            JOIN kelas k ON u.kelas_id = k.id
            ORDER BY a.tanggal DESC";
}

else if ($role === 'admin') {
    // admin bisa lihat semuanya
    $sql = "SELECT a.*, u.nama, u.nis, k.nama_kelas 
            FROM absensi a
            JOIN users u ON a.user_id = u.id
            JOIN kelas k ON u.kelas_id = k.id
            ORDER BY a.tanggal DESC";
}

$result = mysqli_query($koneksi, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Riwayat Absensi</title>
<link rel="stylesheet" href="style.css">
<style>
    body {
        font-family: 'Poppins';
        background: #f3f3f3;
        padding: 20px;
    }
    h2 { text-align: center; }
    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 10px;
        overflow: hidden;
    }
    th, td {
        padding: 12px;
        border-bottom: 1px solid #ddd;
        text-align: center;
    }
    th {
        background: #3A7AFE;
        color: white;
    }
    tr:hover { background: #f0f4ff; }
</style>
</head>

<body>

<h2>Riwayat Absensi</h2>
<p style="text-align:center;">Role: <b><?= $role ?></b> | Nama: <b><?= $nama ?></b></p>

<table>
    <tr>
        <th>Nama</th>
        <th>NIS</th>
        <th>Kelas</th>
        <th>Tanggal</th>
        <th>Jam</th>
        <th>Status</th>
        <th>Surat (jika ada)</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td><?= $row['nama'] ?></td>
        <td><?= $row['nis'] ?></td>
        <td><?= $row['nama_kelas'] ?></td>
        <td><?= $row['tanggal'] ?></td>
        <td><?= $row['jam'] ?></td>
        <td><?= $row['status'] ?></td>
        <td>
            <?php if ($row['foto']) { ?>
                <a href="uploads/<?= $row['foto'] ?>" target="_blank">Lihat</a>
            <?php } else { echo "-"; } ?>
        </td>
    </tr>
    <?php } ?>

</table>

</body>
</html>

<?php
session_start();
include "koneksi.php";
date_default_timezone_set('Asia/Jakarta');

/* =====================
   CEK LOGIN
===================== */
if (!isset($_SESSION['nip_nis'])) {
    header("Location: index.php");
    exit;
}

/* =====================
   DATA SESSION
===================== */
$nama     = $_SESSION['nama'];
$nip_nis  = $_SESSION['nip_nis'];
$id_kelas = $_SESSION['id_kelas'];

/* =====================
   TANGGAL & JAM
===================== */
$tanggal = date("Y-m-d");
$jam     = date("H:i:s");

/* =====================
   CEK SUDAH ABSEN
===================== */
$cek = mysqli_query($koneksi, "
    SELECT id FROM absensi 
    WHERE nip_nis='$nip_nis' AND tanggal='$tanggal'
");

if (!$cek) {
    die("ERROR CEK ABSEN: " . mysqli_error($koneksi));
}

$sudah_absen = mysqli_num_rows($cek);

/* =====================
   PROSES ABSEN
===================== */
if (isset($_POST['absen']) && $sudah_absen == 0) {

    $status = $_POST['status'];
    $bukti  = "";

    if (($status == "sakit" || $status == "izin") && !empty($_FILES['bukti']['name'])) {
        $ext = pathinfo($_FILES['bukti']['name'], PATHINFO_EXTENSION);
        $bukti = "bukti_" . time() . "." . $ext;
        move_uploaded_file($_FILES['bukti']['tmp_name'], "uploads/" . $bukti);
    }

    $insert = mysqli_query($koneksi, "
        INSERT INTO absensi (nip_nis, id_kelas, tanggal, jam, status, bukti)
        VALUES ('$nip_nis','$id_kelas','$tanggal','$jam','$status','$bukti')
    ");

    if (!$insert) {
        die("INSERT GAGAL: " . mysqli_error($koneksi));
    }

    header("Location: dashboard_siswa.php");
    exit;
}

/* =====================
   AMBIL NAMA KELAS
===================== */
$qk = mysqli_query($koneksi, "SELECT nama_kelas FROM kelas WHERE id='$id_kelas'");
$kelas = mysqli_fetch_assoc($qk)['nama_kelas'] ?? '-';
?>


<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Siswa</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: #eef3ff;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: auto;
        }
        .box {
            background: #fff;
            padding: 25px;
            border-radius: 14px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 25px;
        }
        h2, h3 {
            color: #1a2a78;
            margin-bottom: 10px;
        }
        .btn {
            background: #3c5dff;
            color: white;
            padding: 10px 16px;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            cursor: pointer;
        }
        .btn:hover {
            background: #2e48d4;
        }
        select, input[type=file] {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background: #3c5dff;
            color: white;
            padding: 12px;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        tr:hover td {
            background: #f3f6ff;
        }
        .status {
            font-weight: 600;
        }

        .logout-btn {
    background: #ff4d4d;
    color: white;
    padding: 8px 14px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
}
.logout-btn:hover {
    background: #e63b3b;
}

    </style>
</head>
<body>

<div class="container">

    <!-- DATA SISWA -->
    <div class="box">
        <form action="logout.php" method="post" style="text-align:right;">
    <button type="submit" class="btn">Logout</button>
</form>

        <h2>Dashboard Siswa</h2>
        <p><b>Nama:</b> <?= $nama ?></p>
<p><b>NIP / NIS:</b> <?= $nip_nis ?></p>
<p><b>Kelas:</b> <?= $kelas ?></p>
<p><b>Tanggal:</b> <?= $tanggal ?> | <b>Jam:</b> <?= $jam ?></p>

    </div>

    <!-- ABSENSI -->
    <div class="box">
        <h3>Absensi Hari Ini</h3>

        <?php if ($sudah_absen > 0) { ?>
            <p style="color:green;font-weight:600;">✔ Kamu sudah absen hari ini</p>
        <?php } else { ?>
            <form method="POST" enctype="multipart/form-data">
                <label>Status Kehadiran</label><br>
                <select name="status" required>
    <option value="">-- Pilih --</option>
    <option value="hadir">Hadir</option>
    <option value="sakit">Sakit</option>
    <option value="izin">Izin</option>
</select>
<br><br>

                <label>Upload Bukti (Sakit / Izin)</label><br>
                <input type="file" name="bukti"><br><br>

                <button type="submit" name="absen" class="btn">Absen Sekarang</button>
            </form>
        <?php } ?>
    </div>

   
</div>

</body>
</html>

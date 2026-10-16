<?php
session_start();
include "koneksi.php";

/* =====================
   CEK LOGIN GURU
===================== */
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'guru') {
    header("Location: index.php");
    exit;
}

date_default_timezone_set('Asia/Jakarta');

$nama_guru = $_SESSION['nama'];
$tanggal   = isset($_GET['tanggal']) ? $_GET['tanggal'] : date("Y-m-d");
$id_kelas  = isset($_GET['kelas']) ? $_GET['kelas'] : "";

/* =====================
   DATA KELAS
===================== */
$kelas = mysqli_query($koneksi, "SELECT * FROM kelas");

/* =====================
   FILTER QUERY
===================== */
$where = "WHERE a.tanggal='$tanggal'";
if ($id_kelas != "") {
    $where .= " AND a.id_kelas='$id_kelas'";
}

/* =====================
   DATA ABSENSI
===================== */
$query = mysqli_query($koneksi, "
    SELECT a.*, u.nama, k.nama_kelas
    FROM absensi a
    JOIN users u ON a.nip_nis = u.nip_nis
    JOIN kelas k ON a.id_kelas = k.id
    $where
    ORDER BY k.nama_kelas, u.nama
");

/* =====================
   REKAP ABSENSI
===================== */
$rekap = mysqli_query($koneksi, "
    SELECT
        SUM(status='hadir') AS hadir,
        SUM(status='terlambat') AS terlambat,
        SUM(status='izin') AS izin,
        SUM(status='sakit') AS sakit
    FROM absensi a
    $where
");

$data = mysqli_fetch_assoc($rekap);

$total_hadir     = $data['hadir'] ?? 0;
$total_terlambat = $data['terlambat'] ?? 0;
$total_izin      = $data['izin'] ?? 0;
$total_sakit     = $data['sakit'] ?? 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Guru</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to bottom, #a8d8ff, #eef3ff);
            padding: 25px;
        }
        .container {
            max-width: 1100px;
            margin: auto;
        }
        .box {
            background: white;
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.08);
            margin-bottom: 25px;
        }
        h2, h3 {
            color: #1a2a78;
        }
        select, input[type=date] {
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        button {
            padding: 8px 16px;
            background: #3c5dff;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
        }
        .logout-btn {
            background: #ff4d4d;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 14px;
            text-align: center;
        }
        th {
            background: #3c5dff;
            color: white;
        }
        td {
            border-bottom: 1px solid #ddd;
        }
        tr:hover td {
            background: #f3f6ff;
        }
        .hadir { color: green; font-weight: 600; }
        .terlambat { color: orange; font-weight: 600; }
        .izin { color: #17a2b8; font-weight: 600; }
        .sakit { color: red; font-weight: 600; }
    </style>
</head>
<body>

<div class="container">

    <!-- HEADER -->
    <div class="box">
        <form action="logout.php" method="post" style="text-align:right;">
            <button class="logout-btn">Logout</button>
        </form>
        <h2>Dashboard Guru</h2>
        <p><b>Nama Guru:</b> <?= $nama_guru ?></p>
    </div>

    <!-- FILTER -->
    <div class="box">
        <form method="GET">
            <label>Tanggal:</label>
            <input type="date" name="tanggal" value="<?= $tanggal ?>">

            <label>Kelas:</label>
            <select name="kelas">
                <option value="">Semua Kelas</option>
                <?php while ($k = mysqli_fetch_assoc($kelas)) { ?>
                    <option value="<?= $k['id'] ?>" <?= ($id_kelas == $k['id']) ? 'selected' : '' ?>>
                        <?= $k['nama_kelas'] ?>
                    </option>
                <?php } ?>
            </select>

            <button type="submit">Filter</button>
        </form>
    </div>

    <!-- REKAP -->
    <div class="box">
        <h3>Rekap Kehadiran</h3>
        <table>
            <tr>
                <th>Hadir</th>
                <th>Terlambat</th>
                <th>Izin</th>
                <th>Sakit</th>
            </tr>
            <tr>
                <td><?= $total_hadir ?></td>
                <td><?= $total_terlambat ?></td>
                <td><?= $total_izin ?></td>
                <td><?= $total_sakit ?></td>
            </tr>
        </table>
    </div>

    <!-- GRAFIK -->
    <div class="box">
        <h3>Grafik Absensi</h3>
        <canvas id="chart"></canvas>
    </div>

    <!-- TABEL -->
    <div class="box">
        <h3>Data Absensi</h3>
        <table>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>NIS</th>
                <th>Kelas</th>
                <th>Jam</th>
                <th>Status</th>
            </tr>
            <?php $no=1; while ($r = mysqli_fetch_assoc($query)) { ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $r['nama'] ?></td>
                <td><?= $r['nip_nis'] ?></td>
                <td><?= $r['nama_kelas'] ?></td>
                <td><?= $r['jam'] ?></td>
                <td class="<?= $r['status'] ?>"><?= ucfirst($r['status']) ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('chart'), {
    type: 'pie',
    data: {
        labels: ['Hadir','Terlambat','Izin','Sakit'],
        datasets: [{
            data: [
                <?= $total_hadir ?>,
                <?= $total_terlambat ?>,
                <?= $total_izin ?>,
                <?= $total_sakit ?>
            ],
            backgroundColor: ['#28a745','#ffc107','#17a2b8','#dc3545']
        }]
    }
});
</script>

</body>
</html>

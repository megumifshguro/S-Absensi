<?php
session_start();
include "koneksi.php";

// CEK LOGIN ADMIN
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit;
}

$id_kelas = isset($_GET['kelas']) ? $_GET['kelas'] : "";

// Ambil semua kelas
$kelas = mysqli_query($koneksi, "SELECT * FROM kelas ORDER BY nama_kelas ASC");

// Ambil siswa jika kelas dipilih
$siswa = null;
if ($id_kelas != "") {
    $siswa = mysqli_query($koneksi, 
        "SELECT * FROM users 
         WHERE id_kelas='$id_kelas' AND role='siswa' 
         ORDER BY nama ASC");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: #bfe3ff;
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
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            margin-bottom: 25px;
        }

        h2 {
            color: #1a2a78;
            margin-bottom: 15px;
        }

        /* ===== FORM & BUTTON ===== */
        select {
            padding: 10px;
            border-radius: 10px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        .btn {
            background: #3c5dff;
            color: white;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            background: #2e48d4;
        }

        .btn-danger {
            background: #ff4d4d;
        }

        .btn-danger:hover {
            background: #e63b3b;
        }

        /* ===== LOGOUT ===== */
        .logout-btn {
            background: #ff4d4d;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
        }

        .logout-btn:hover {
            background: #e63b3b;
        }

        /* ===== TABLE ===== */
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

        .aksi {
            white-space: nowrap;
        }
    </style>
</head>
<body>

<div class="container">

    <!-- HEADER -->
    <div class="box">
        <form action="logout.php" method="post" style="text-align:right;">
            <button type="submit" class="logout-btn">Logout</button>
        </form>

        <h2>Dashboard Admin</h2>

        <form method="GET">
            <label><b>Pilih Kelas:</b></label><br><br>
            <select name="kelas" onchange="this.form.submit()">
                <option value="">-- Pilih Kelas --</option>
                <?php while ($k = mysqli_fetch_assoc($kelas)) { ?>
                    <option value="<?= $k['id'] ?>" <?= ($id_kelas == $k['id']) ? 'selected' : '' ?>>
                        <?= $k['nama_kelas'] ?>
                    </option>
                <?php } ?>
            </select>
        </form>

        <br>

        <?php if ($id_kelas != "") { ?>
            <a href="tambah_siswa.php" class="btn">+ Tambah Siswa</a>
        <?php } ?>
    </div>

    <!-- TABEL SISWA -->
    <?php if ($id_kelas != "") { ?>
    <div class="box">
        <h2>Daftar Siswa</h2>

        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>NIS</th>
                    <th>Password</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($s = mysqli_fetch_assoc($siswa)) { ?>
                <tr>
                    <td><?= $s['nama'] ?></td>
                    <td><?= $s['nip_nis'] ?></td>
                    <td><?= $s['password'] ?></td>
                    <td class="aksi">
                        <a class="btn" href="edit_siswa.php?id=<?= $s['id'] ?>">Edit</a>
                        <a class="btn btn-danger" 
                           href="hapus_siswa.php?id=<?= $s['id'] ?>" 
                           onclick="return confirm('Hapus siswa ini?')">Hapus</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <?php } ?>

</div>

</body>
</html>

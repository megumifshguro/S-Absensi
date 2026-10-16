<?php
include "koneksi.php";

$id = $_GET['id'];

// data siswa
$s = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM users WHERE id='$id'"));

// data kelas (buat dropdown)
$kelas = mysqli_query($koneksi, "SELECT * FROM kelas ORDER BY nama_kelas ASC");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama     = $_POST['nama'];
    $nis      = $_POST['nis'];
    $password = $_POST['password'];
    $id_kelas = $_POST['id_kelas'];

    mysqli_query($koneksi,
        "UPDATE users 
         SET nama='$nama',
             nip_nis='$nis',
             password='$password',
             id_kelas='$id_kelas'
         WHERE id='$id'");

    header("Location: admin_dashboard.php?kelas=" . $id_kelas);
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Siswa</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: #eef3ff;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
            background: white;
            padding: 30px;
            width: 420px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.12);
        }
        h2 {
            text-align: center;
            color: #1a2a78;
            margin-bottom: 20px;
        }
        label {
            font-weight: 500;
            font-size: 14px;
        }
        input, select {
            width: 100%;
            padding: 11px;
            margin-top: 6px;
            margin-bottom: 16px;
            border-radius: 10px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #3c5dff;
            box-shadow: 0 0 0 2px rgba(60,93,255,0.2);
        }
        .btn {
            width: 100%;
            background: #3c5dff;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
        }
        .btn:hover {
            background: #2e48d4;
        }
        .back {
            margin-top: 15px;
            text-align: center;
        }
        .back a {
            color: #3c5dff;
            text-decoration: none;
            font-weight: 500;
        }
        .back a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>Edit Data Siswa</h2>

    <form method="POST">
        <label>Nama</label>
        <input type="text" name="nama" value="<?= $s['nama'] ?>" required>

        <label>NIS</label>
        <input type="text" name="nis" value="<?= $s['nip_nis'] ?>" required>

        <label>Password</label>
        <input type="text" name="password" value="<?= $s['password'] ?>" required>

        <label>Kelas</label>
        <select name="id_kelas" required>
            <option value="">-- Pilih Kelas --</option>
            <?php while ($k = mysqli_fetch_assoc($kelas)) { ?>
                <option value="<?= $k['id'] ?>"
                    <?= ($k['id'] == $s['id_kelas']) ? 'selected' : '' ?>>
                    <?= $k['nama_kelas'] ?>
                </option>
            <?php } ?>
        </select>

        <button type="submit" class="btn">Update Data</button>
    </form>

    <div class="back">
        <a href="admin_dashboard.php?kelas=<?= $s['id_kelas'] ?>">← Kembali ke Dashboard</a>
    </div>
</div>

</body>
</html>

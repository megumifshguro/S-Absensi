<?php
session_start();
include "koneksi.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nip_nis = $_POST['nip_nis'];
    $password = $_POST['password'];

    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE nip_nis='$nip_nis' LIMIT 1");

    if (mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);

        if ($password === $data['password']) { 
            $_SESSION['id'] = $data['id'];
            $_SESSION['nama'] = $data['nama'];
            $_SESSION['nip_nis'] = $data['nip_nis'];
            $_SESSION['role'] = $data['role'];
            $_SESSION['id_kelas'] = $data['id_kelas'];

            if ($data['role'] == "admin") {
                header("Location: admin_dashboard.php");
            } elseif ($data['role'] == "guru") {
                header("Location: dashboard_guru.php");
            } else {
                header("Location: dashboard_siswa.php");
            }
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "NIP / NIS tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Absensi</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
           background: linear-gradient(to bottom, #d9f0ff, #a8d8ff);

            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            width: 370px;
            background: #fff;
            padding: 35px;
            border-radius: 16px;
            box-shadow: 0px 8px 25px rgba(0,0,0,0.15);
            text-align: center;
        }

        .logo-sekolah {
            width: 110px;
            margin-bottom: 12px;
        }

        h2 {
            margin-bottom: 10px;
            color: #1a2a78;
            font-weight: 600;
        }

        .input-group {
            text-align: left;
            margin-top: 15px;
        }

        .input-group label {
            font-size: 14px;
            font-weight: 500;
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border-radius: 10px;
            border: 1px solid #ccc;
            font-size: 15px;
        }

        .btn {
            margin-top: 20px;
            width: 100%;
            background: linear-gradient(to right, #00aaff, #0077ff);
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn:hover {
            background: #a1a9d0ff;
        }

        .error {
            margin-top: 10px;
            padding: 10px;
            background: #ffdddd;
            color: #b30000;
            border-left: 4px solid #ff4d4d;
            border-radius: 6px;
        }
    </style>
</head>
<body>

<div class="login-box">

    <!-- LOGO SEKOLAH -->
    <img src="Logo_SMK_Negeri_1_Kota_Bekasi.png" class="logo-sekolah" alt="Logo Sekolah">

    <h2>Login Absensi</h2>

    <?php if ($error != "") { ?>
        <div class="error"><?= $error ?></div>
    <?php } ?>

    <form method="POST">
        <div class="input-group">
            <label>NIP / NIS</label>
            <input type="text" name="nip_nis" required>
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <button type="submit" class="btn">LOGIN</button>
    </form>

</div>

</body>
</html>

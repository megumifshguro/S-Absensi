<?php
include "koneksi.php";

$id = $_GET['id'];

$s = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT id_kelas FROM users WHERE id='$id'"));

mysqli_query($koneksi, "DELETE FROM users WHERE id='$id'");

header("Location: admin_dashboard.php?kelas=" . $s['id_kelas']);
exit;

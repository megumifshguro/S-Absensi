<?php
mysqli_report(MYSQLI_REPORT_OFF);
$koneksi = @mysqli_connect("localhost", "root", "belajar123", "projectabsensi");

if (!$koneksi) {
    die("<div style='font-family:sans-serif;padding:20px;background:#ffe6e6;color:#900;border:1px solid #f5c6cb;border-radius:8px;'>
        <h3>Koneksi Database Gagal</h3>
        <p><b>Error:</b> " . mysqli_connect_error() . "</p>
        <p>Pastikan MySQL di Laragon sudah dalam keadaan <b>Start</b> dan database <b>projectabsensi</b> sudah ada.</p>
    </div>");
}
?>

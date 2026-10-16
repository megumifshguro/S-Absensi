<?php
session_start();
session_unset();   // hapus semua session
session_destroy(); // matiin session

header("Location: index.php");
exit;

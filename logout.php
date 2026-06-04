<?php
session_start();
session_destroy(); // Menghapus semua session login
header("Location: login.php");
exit();
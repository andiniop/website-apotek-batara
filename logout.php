<?php
session_start();
session_destroy(); // Hapus sesi login
header('Location: login.php');
exit();
?>

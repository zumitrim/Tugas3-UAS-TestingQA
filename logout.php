<?php
session_start();

// Hapus session pengguna
unset($_SESSION['username']);
session_destroy();

echo "success";
?>

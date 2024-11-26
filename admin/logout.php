<?php
session_start();
session_destroy();
// Vérifiez si l'utilisateur est connecté et s'il est administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit;
}
?>

<?php 
require_once('C:\xampp\htdocs\managementdesease\Login\controllerUserData.php');
if (!isset($_SESSION['username'])) {
    header("Location: Login/login.php"); // Redirige si non connecté
    exit();
}
?>
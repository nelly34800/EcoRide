<?php
require_once "pdo.php";
require_once "administrator.php";
require_once "role.php";
session_start(); 
// Vérifier que l'utilisateur est administrateur
verifRole(5);

if (isset($_GET['id'])) {
    activateUser($pdo, (int)$_GET['id']);
}

header("Location: ../admin.php");
exit;
?>
<?php
session_start();
require_once 'pdo.php';  // Connexion à la BDD
// Vérifie que l'utilisateur est bien connecté et a le bon rôle
if (isset($_SESSION['user'])) {
    $user_id = $_SESSION['user']['id'];
    $newRoleId = 6; // Passager & Chauffeur

    // Mettre à jour le rôle dans la BDD
    $query = $pdo->prepare("UPDATE users SET role_id = :role_id WHERE id = :id");
    $query->bindValue(':role_id', $newRoleId, PDO::PARAM_INT);
    $query->bindValue(':id', $user_id, PDO::PARAM_INT);
    $query->execute();

    // Mettre à jour la session
    $_SESSION['user']['role_id'] = $newRoleId;

    // Rediriger avec succès
    header("Location: ../passager_chauffeur.php");
    exit();
}

<?php
require_once "templates/header.php";
require_once "Lib/pdo.php";
require_once "lib/preference.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user']['id'])) {
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION['user']['id'];
$preferences_id = $_GET['id'] ?? null; // Récupérer l'ID des préférences à supprimer

if ($preferences_id) {
    $deleted = deletePreference($pdo, $preferences_id, $user_id);
    if ($deleted) {
        header("location: preferences_chauffeur.php?success"); // Redirection avec succès
    } else {
        header("location: preferences_chauffeur.php?error"); // Redirection en cas d'erreur
    }

    header("location: preferences_chauffeur.php?error"); // Si l'id est manquant
    exit();
}

require_once "templates/footer.php";

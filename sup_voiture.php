<?php
require_once "lib/pdo.php";
require_once "lib/car.php";
require_once "templates/header.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user']['id'])) {
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION['user']['id'];
$car_id = $_GET['id'] ?? null; // Récupérer l'ID de la voiture à supprimer

if ($car_id) {
    $deleted = deleteCar($pdo, $car_id, $user_id);
    if ($deleted) {
        header("Location: voitures.php?success"); // Redirection avec succès
        exit();
    } else {
        header("Location: voitures.php?error"); // Redirection en cas d'erreur
        exit();
    }
} else {
    header("Location: voitures.php?error"); // Si l'ID est manquant
    exit();
}

require_once "templates/footer.php";

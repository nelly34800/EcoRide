<?php
require_once "lib/pdo.php";
require_once "lib/journey.php";
require_once "lib/reservation.php";
require_once "lib/send_email.php";
require_once "templates/header.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user']['id'])) {
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION['user']['id'];
$journey_id = $_GET['id'] ?? null; // Récupérer l'ID de la voiture à supprimer

if ($journey_id) {
      // Récupère les passagers
   $passengers = getPassengersEmailsByJourney($pdo, $journey_id);
   //suprime le trajet
   $deleted = deletejourney($pdo, $journey_id, $user_id);

   if ($deleted && !empty($passengers)) {
   // Envoie un mail à chaque passager
        foreach ($passengers as $passenger) {
            $typeMessage = "annulation  de trajet";
            $messageHtml = "Désolé, le chauffeur a annulé le trajet.<br><br>
            Merci pour votre engagement dans la communauté EcoRide ! 🚗💬";
            sendBrevoMail($passenger['email'], $passenger['pseudo'], $typeMessage, $messageHtml);
        }
    }
    if ($deleted) {
        header("Location: chauffeur.php?success=3"); // Redirection avec succès
        exit();
    } else {
        header("Location: chauffeur.php?error"); // Redirection en cas d'erreur
        exit();
    }
} else {
    header("Location: chauffeur.php?error"); // Si l'ID est manquant
    exit();
}


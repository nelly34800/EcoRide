<?php
require_once "lib/pdo.php";
require_once "lib/journey.php";
require_once "lib/send_email.php";
require_once "lib/reservation.php";
require_once "templates/header.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user']['id'])) {
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION['user']['id'];
$journey_id = isset($_GET['id']) ? (int) $_GET['id'] : null; // Vérification et conversion en entier; Récupérer l'ID de la réservation à supprimer

if (!$journey_id || !($reservation_id = getReservationId($pdo, $journey_id, $user_id))) {
    header("Location: passager.php?error");
    exit();
}

// Supprimer la réservation, mettre à jour les places et récupérer le prix du trajet
deleteReservation($pdo, $reservation_id, $journey_id, $user_id);
updateAvailableSeats($pdo, $journey_id, 1);
$journey_data = getJourneysById($pdo, $journey_id);

// Vérifier et rembourser les crédits
if ($journey_data && isset($journey_data['price'])) {
    refundCredits($pdo, $user_id, $journey_id);
}
   // Récupère les chauffeur
   $driver = getDriverByJourney($pdo, $journey_id);
   // Envoie un mail au chauffeur
    if ($driver && isset($driver['email'], $driver['pseudo'])) {
       $typeMessage = "annulation  de réservation de trajet";
       $messageHtml = "Désolé, un passager a annulé sa réservation de trajet.<br><br>
Merci pour votre engagement dans la communauté EcoRide ! 🚗💬";
       
       sendBrevoMail($driver['email'], $driver['pseudo'], $typeMessage, $messageHtml);
        }
header("Location: passager.php?success=2"); 
exit();
<?php
require_once "lib/pdo.php";
require_once "lib/journey.php";
require_once "lib/reservation.php";
require_once "lib/send_email.php";
require_once "lib/utils.php";
require_once "templates/header.php";


$user_id = $_SESSION['user']['id'];
$journey_id = $_POST['journey_id'] ?? null; // Récupérer l'ID du covoiturage à supprimer

if ($journey_id) {
       // Récupère les infos du trajet
    $journey = getJourneysById($pdo, $journey_id);
      // Récupère les passagers
   $passengers = getPassengersEmailsByJourney($pdo, $journey_id);
   
   if (!empty($passengers)) {
        // Envoie un mail à chaque passager et rembourse les credits
        foreach ($passengers as $passenger) {
            //Remboursement des crédits
            refundCredits($pdo, $passenger['id'], $journey_id);

            //Envoi de mail
            $typeMessage = "annulation  de trajet";
            $messageHtml = "<p>Bonjour " . htmlspecialchars($passenger['pseudo']) . ",</p>";
            $messageHtml .= "<p> Désolé, le chauffeur a annulé le trajet de <strong>" 
                . htmlspecialchars($journey['place_departure']) 
                . "</strong> à <strong>" 
                . htmlspecialchars($journey['place_arrival']) 
                . "</strong> le <strong>" 
                . htmlspecialchars(changeDateFormatJour($journey['date'])) 
                . "</strong> 🚗💬</p>";
            $messageHtml .= "<p>Merci pour votre engagement dans la communauté EcoRide ! 🚗💬</p>";
            sendBrevoMail($passenger['email'], $passenger['pseudo'], $typeMessage, $messageHtml);
        }
    }
    //suprime le trajet
   $deleted = deletejourney($pdo, $journey_id, $user_id);
   //redirection
    if ($deleted) {
        header("Location: confirmation.php?type=suppression&status=success"); // Redirection avec succès
            exit();
    } else {
        header("confirmation.php?type=suppression&status=error"); // Redirection en cas d'erreur
        exit();
    }
}
require_once "templates/footer.php";

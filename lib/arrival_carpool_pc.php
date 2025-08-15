<?php
require_once "pdo.php";
require_once "reservation.php";
require_once "send_email.php";
require_once "journey.php";

if (isset($_POST['journey_id'])) {
    $journey_id = $_POST['journey_id'];

  // Récupération des infos du trajet
    $journey = getJourneysById($pdo, $journey_id);
    if (!$journey) {
        header("Location: ../passager_chauffeur.php?error=trajet-introuvable");
        exit();
    }

    // Met à jour du statut "terminé"
    $sql = "UPDATE journeys SET status = 'completed' WHERE id = :journey_id";
    $query = $pdo->prepare($sql);
    $query->bindValue(':journey_id', $journey_id, PDO::PARAM_INT);
    $query->execute();

 // Récupère les passagers
    $passengers = getPassengersEmailsByJourneyStatus($pdo, $journey_id);

    // Envoie un mail à chaque passager
    foreach ($passengers as $passenger) {
        $typeMessage = "validation de trajet";
        $messageHtml = "Le chauffeur a indiqué que votre trajet de "
         . htmlspecialchars($journey['place_departure']) 
        . " à " 
        . htmlspecialchars($journey['place_arrival']) 
        . " le " 
        . htmlspecialchars(changeDateFormatJour($journey['date']))
        . " est terminé.<br><br>
Merci de vous rendre sur votre espace passager pour confirmer que tout s'est bien passé ou signaler un problème.<br><br>
<a href='https://ecoride.com/passager.php' 
    style='display: inline-block; padding: 12px 24px; margin-top: 10px; background-color: #44eedd; color: #004477; 
           text-decoration: none; font-weight: bold; border-radius: 5px;'>
    Valider mon trajet
</a><br><br>
Vous disposer d'un délais de 7 jours pour valider votre trajet ou signaler un problème: <br><br>
passé ce délais le trajet sera automatiquement cloturé positivement.<br><br>
Merci pour votre engagement dans la communauté EcoRide ! 🚗💬";
        
        sendBrevoMail($passenger['email'], $passenger['pseudo'], $typeMessage, $messageHtml);
    }

    header("Location: ../passager_chauffeur.php?success=1"); // Redirection avec succès
    exit();
} else {
    header("Location: ../passager_chauffeur.php?error=1"); // Redirection en cas d'erreur
    exit();
}

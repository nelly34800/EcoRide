<?php
require_once "lib/pdo.php";
require_once "lib/reservation.php";
require_once "templates/header.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user']['id'])) {
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION['user']['id'];
$journey_id = $_GET['id'] ?? null; // Récupérer l'ID de la voiture à supprimer

if ($journey_id) {
    $reservation_id = getReservationId($pdo, $journey_id, $user_id);

    if ($reservation_id) {
        // Supprimer la réservation
        $deleted = deleteReservation($pdo, $reservation_id, $journey_id, $user_id);
        if ($deleted) {
            header("Location: passager.php?success=2"); // Redirection avec succès
            exit();
        }
    }
}
header("Location: passager.php?error"); // en cas d'erreur
exit();

require_once "templates/footer.php";

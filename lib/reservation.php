<?php
require_once "journey.php";

function verifAvailableSeats($pdo, $journey_id): bool
{
    // Récupérer le nombre de places disponibles pour le trajet
    $sql = "SELECT available_seats FROM journeys WHERE id = :journey_id";
    $query = $pdo->prepare($sql);
    $query->bindValue(':journey_id', $journey_id, PDO::PARAM_INT);
    $query->execute();
    $journey = $query->fetch(PDO::FETCH_ASSOC);

    return $journey && $journey['available_seats'] > 0; // Retourne true si des places sont dispo
    }

function verifAndUpdateJourneyStatus($pdo, $journey_id)
{
    // Récupérer le nombre de réservations pour ce trajet
    $sql = "SELECT journeys.available_seats, COUNT(reservations.id) AS reservation_count FROM journeys 
    LEFT JOIN reservations ON journeys.id = reservations.journey_id WHERE journeys.id = :journey_id GROUP BY journeys.id";
    $query = $pdo->prepare($sql);
    $query->bindValue(':journey_id', $journey_id, PDO::PARAM_INT);
    $query->execute();
    $journey = $query->fetch(PDO::FETCH_ASSOC);

    if (!$journey) {
        return; // Si le trajet n'existe pas
    }
    $available_seats = $journey['available_seats'];
    $reservation_count = $journey['reservation_count'];
    // Calculer le nombre de places restantes
    $remaining_seats = $available_seats - $reservation_count;

    // Mettre à jour le nombre de places disponibles dans `journeys`
    $sql = "UPDATE journeys SET available_seats = :remaining_seats WHERE id = :journey_id";
    $query = $pdo->prepare($sql);
    $query->bindValue(':remaining_seats', max(0, $remaining_seats), PDO::PARAM_INT);
    $query->bindValue(':journey_id', $journey_id, PDO::PARAM_INT);
    $query->execute();

    // Mettre à jour le statut du trajet si complet
    if ($remaining_seats <= 0) {
        $sql = "UPDATE journeys SET status = 'complet' WHERE id = :journey_id";
        $query = $pdo->prepare($sql);
        $query->bindValue(':journey_id', $journey_id, PDO::PARAM_INT);
        $query->execute();
    }
}

function hasSufficientCredits($pdo, $user_id, $journey_price): bool {
    $sql = "SELECT credit FROM credits WHERE user_id = :user_id";
    $query = $pdo->prepare($sql);
    $query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $query->execute();
    $credit = $query->fetchColumn();

    return $credit >= $journey_price;
}

function addReservation($pdo, $user_id, $journey_id, $role_id)
{
    // Vérification avant l'ajout de la réservation
    if (!verifAvailableSeats($pdo, $journey_id)) {
        return false; // Pas de place dispo, on ne fait rien
    }
    $sql = "INSERT INTO reservations (user_id, journey_id, role_id, status) VALUES (:user_id, :journey_id, :role_id, 'upcoming')";
    $query = $pdo->prepare($sql);
    $query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $query->bindValue(':journey_id', $journey_id, PDO::PARAM_INT);
    $query->bindValue(':role_id', $role_id, PDO::PARAM_INT);
    $query->execute();

    // Vérifier et mettre à jour le statut du trajet
    verifAndUpdateJourneyStatus($pdo, $journey_id);
    return true;
}

function deductCredits($pdo, $user_id, $journey_price) {
    $sql = "UPDATE credits SET credit = credit - :journey_price WHERE user_id = :user_id AND credit >= :journey_price";
    $query = $pdo->prepare($sql);
    $query->bindValue(':journey_price', $journey_price, PDO::PARAM_INT);
    $query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $query->execute();

    return $query->rowCount() > 0; // Retourne vrai si une ligne a été mise à jour
}

function getReservationId(PDO $pdo, int $journey_id, int $user_id): ?int
{
    $sql = "SELECT id FROM reservations WHERE journey_id = :journey_id AND user_id = :user_id";
    $query = $pdo->prepare($sql);
    $query->bindParam(':journey_id', $journey_id, PDO::PARAM_INT);
    $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $query->execute();
    $reservation = $query->fetch(PDO::FETCH_ASSOC);
    //  si une réservation est trouvée ,retourne son id, sinon retourne null
    return $reservation ? $reservation['id'] : null;
}

function deleteReservation(PDO $pdo, int $reservation_id, int $journey_id, int $user_id): bool
{
    $sql = "DELETE FROM reservations WHERE id = :reservation_id AND journey_id = :journey_id AND user_id = :user_id";
    $query = $pdo->prepare($sql);
    $query->bindParam(':reservation_id', $reservation_id, PDO::PARAM_INT);
    $query->bindParam(':journey_id', $journey_id, PDO::PARAM_INT);
    $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    return $query->execute();
}

function getUserCredits($pdo, $user_id) {
    $sql = "SELECT credit FROM credits WHERE user_id = :user_id";
    $query = $pdo->prepare($sql);
    $query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);

    return $result ? (int) $result['credit'] : 0; // Retourne 0 si aucun crédit trouvé
}

function refundCredits($pdo, $user_id, $journey_id) {
    // Récupérer le prix du trajet directement depuis la base de données
    $sql = "SELECT price FROM journeys WHERE id = :journey_id";
    $query = $pdo->prepare($sql);
    $query->bindValue(':journey_id', $journey_id, PDO::PARAM_INT);
    $query->execute();
    $journey = $query->fetch(PDO::FETCH_ASSOC); 

    if (!$journey) {
        die("Erreur: trajet non trouvé");
    }
    $journey_price = (int) $journey['price']; // S'assurer que c'est bien un entier

    // Vérifications avant mise à jour
    echo "<script>console.log('Prix du trajet :, $journey_price');</script>";
    echo "<script>console.log('Crédits avant remboursement : " . getUserCredits($pdo, $user_id) . "');</script>";

    if ($journey_price > 0) {
        // Ajouter les crédits au compte du passager
        $sql = "UPDATE credits SET credit = credit + :journey_price WHERE user_id = :user_id";
        $query = $pdo->prepare($sql);
        $query->bindValue(':journey_price', $journey_price, PDO::PARAM_INT);
        $query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        
        $success = $query->execute();
        echo "<script>console.log('Crédits après remboursement : " . getUserCredits($pdo, $user_id) . "');</script>"; // Vérifier après
        return $success;
    }
    
    return false; // Retourne false si le prix est invalide ou non trouvé
}

function updateAvailableSeats($pdo, $journey_id, $increment = 1) {
    $sql = "UPDATE journeys SET available_seats = available_seats + :increment WHERE id = :journey_id";
    $query = $pdo->prepare($sql);
    $query->bindValue(':increment', $increment, PDO::PARAM_INT);
    $query->bindValue(':journey_id', $journey_id, PDO::PARAM_INT);
    return $query->execute();
    
    $sql = "UPDATE journeys 
        SET status = 'pending' 
        WHERE id = :journey_id 
        AND available_seats > 0 
        AND status = 'completed'"; // Vérifie que le trajet était bien marqué comme complet

$query = $pdo->prepare($sql);
$query->bindValue(':journey_id', $journey_id, PDO::PARAM_INT);
$query->execute();
}

function getPassengersEmailsByJourneyStatus($pdo, $journey_id) {
    $sql = "SELECT u.email, u.pseudo
            FROM reservations r
            JOIN users u ON r.user_id = u.id
            WHERE r.journey_id = :journey_id AND r.status = 'ongoing'";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':journey_id', $journey_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPassengersEmailsByJourney($pdo, $journey_id) {
    $sql = "SELECT u.email, u.pseudo
            FROM reservations r
            JOIN users u ON r.user_id = u.id
            WHERE r.journey_id = :journey_id ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':journey_id', $journey_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

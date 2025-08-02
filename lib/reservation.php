<?php
require_once "journey.php";

function verifAndUpdateJourneyStatus($pdo, $journey_id)
{
    // Récupérer total_seats, le nombre de réservations et le statut actuel
    $sql = "SELECT journeys.total_seats, COUNT(reservations.id) AS reservation_count, journeys.status 
            FROM journeys 
            LEFT JOIN reservations ON journeys.id = reservations.journey_id 
            WHERE journeys.id = :journey_id 
            GROUP BY journeys.id";
    
    $query = $pdo->prepare($sql);
    $query->bindValue(':journey_id', $journey_id, PDO::PARAM_INT);
    $query->execute();
    $journey = $query->fetch(PDO::FETCH_ASSOC);

    if (!$journey) return;

    $total_seats = $journey['total_seats'];
    $reservation_count = $journey['reservation_count'];
    $status = $journey['status'];

    $remaining_seats = $total_seats - $reservation_count;

    // Ne modifier le statut que si le trajet est à venir (pending ou complet)
    if ($status === 'pending' || $status === 'complet') {
        //Si nombre de place est 0 ou- status compet sinon status pending (ternaire)
        $new_status = $remaining_seats <= 0 ? 'complet' : 'pending';

        if ($new_status !== $status) {
            $sql = "UPDATE journeys SET status = :new_status WHERE id = :journey_id";
            $query = $pdo->prepare($sql);
            $query->bindValue(':new_status', $new_status, PDO::PARAM_STR);
            $query->bindValue(':journey_id', $journey_id, PDO::PARAM_INT);
            $query->execute();
        }
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

function addReservation($pdo, $user_id, $journey_id, $role_id) {
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

    if ($journey_price > 0) {
        // Ajouter les crédits au compte du passager
        $sql = "UPDATE credits SET credit = credit + :journey_price WHERE user_id = :user_id";
        $query = $pdo->prepare($sql);
        $query->bindValue(':journey_price', $journey_price, PDO::PARAM_INT);
        $query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $result = $query->execute();
        return $result;
    }
    
    return false; // Retourne false si le prix est invalide ou non trouvé
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
    $sql = "SELECT u.id, u.email, u.pseudo
            FROM reservations r
            JOIN users u ON r.user_id = u.id
            WHERE r.journey_id = :journey_id ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':journey_id', $journey_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

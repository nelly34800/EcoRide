<?php
function verifAndUpdateJourneyStatus($pdo, $journey_id)
{
    // Récupérer le nombre de places disponibles pour le trajet et le met à jour
    $sql = "SELECT number_places FROM cars 
            JOIN journeys ON cars.id = journeys.car_id 
            WHERE journeys.id = :journey_id";
    $query = $pdo->prepare($sql);
    $query->bindValue(':journey_id', $journey_id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);
    $car_id = $result['car_id'];
    $number_places = $result['number_places'];

    // Récupérer le nombre de réservations pour le trajet
    $sql = "SELECT COUNT(*) FROM reservations WHERE journey_id = :journey_id";
    $query = $pdo->prepare($sql);
    $query->bindValue(':journey_id', $journey_id, PDO::PARAM_INT);
    $query->execute();
    $reservation_count = $query->fetchColumn();

    // Mettre à jour le nombre de places disponibles
    $remaining_places = $number_places - $reservation_count;
    $sql = "UPDATE cars SET number_places = :remaining_places WHERE id = :car_id";
    $query = $pdo->prepare($sql);
    $query->bindValue(':remaining_places', $remaining_places, PDO::PARAM_INT);
    $query->bindValue(':car_id', $car_id, PDO::PARAM_INT);
    $query->execute();

    // Mettre à jour le statut (dans journeys) du trajet si complet
    if ($remaining_places <= 0) {
        $sql = "UPDATE journeys SET status = 'complet' WHERE id = :journey_id";
        $query = $pdo->prepare($sql);
        $query->bindValue(':journey_id', $journey_id, PDO::PARAM_INT);
        $query->execute();
    }
}

function addReservation($pdo, $user_id, $journey_id, $role_id)
{
    $sql = "INSERT INTO reservations (user_id, journey_id, role_id, status) VALUES (:user_id, :journey_id, :role_id, 'upcoming')";
    $query = $pdo->prepare($sql);
    $query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $query->bindValue(':journey_id', $journey_id, PDO::PARAM_INT);
    $query->bindValue(':role_id', $role_id, PDO::PARAM_INT);
    $query->execute();

    // Vérifier et mettre à jour le statut du trajet
    verifAndUpdateJourneyStatus($pdo, $journey_id);
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

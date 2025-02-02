<?php
function verifAndUpdateJourneyStatus($pdo, $journey_id)
{
    // Récupérer le nombre de places disponibles pour le trajet
    $sql = "SELECT number_places FROM cars 
            JOIN journeys ON cars.id = journeys.car_id 
            WHERE journeys.id = :journey_id";
    $query = $pdo->prepare($sql);
    $query->bindValue(':journey_id', $journey_id, PDO::PARAM_INT);
    $query->execute();
    $number_places = $query->fetchColumn();

    // Récupérer le nombre de réservations pour le trajet
    $sql = "SELECT COUNT(*) FROM reservations WHERE journey_id = :journey_id";
    $query = $pdo->prepare($sql);
    $query->bindValue(':journey_id', $journey_id, PDO::PARAM_INT);
    $query->execute();
    $reservation_count = $query->fetchColumn();

    // Mettre à jour le statut du trajet si complet
    if ($reservation_count >= $number_places) {
        $sql = "UPDATE reservations SET status = 'complet' WHERE journey_id = :journey_id";
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

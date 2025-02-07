<?php
function getUserById($pdo, $user_id)
//récupère les infos du profil
{
    $sql = "SELECT id, pseudo, image FROM users WHERE id = :user_id";
    $query = $pdo->prepare($sql);
    $query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
}

function getUserJourneysCompleted($pdo, $user_id)
//récupère l'historique
{
    $sql = "SELECT place_departure, place_arrival, departure_time, arrival_time, date FROM journeys 
            JOIN reservations ON journeys.id = reservations.journey_id 
            WHERE reservations.user_id = :user_id AND reservations.status = 'completed'
            ORDER BY journeys.date DESC";
    $query = $pdo->prepare($sql);
    $query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function getUserJourneysUpcoming($pdo, $user_id)
//récupère les trajet en attente
{
    $sql = "SELECT place_departure, place_arrival, departure_time, arrival_time, date FROM journeys 
            JOIN reservations ON journeys.id = reservations.journey_id 
            WHERE reservations.user_id = :user_id AND reservations.status = 'upcoming'";
    $query = $pdo->prepare($sql);
    $query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

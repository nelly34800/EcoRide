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
//récupère l'historique des passagers 
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
//récupère les trajet en attente des passagers
{
    $sql = "SELECT place_departure, place_arrival, departure_time, arrival_time, date FROM journeys 
            JOIN reservations ON journeys.id = reservations.journey_id 
            WHERE reservations.user_id = :user_id AND reservations.status = 'upcoming'";
    $query = $pdo->prepare($sql);
    $query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}
function getPendingCarpools($pdo, $user_id)
//récupère les trajets en attente du chauffeur
{
    $sql = "SELECT journeys.id, place_departure, place_arrival, departure_time, arrival_time, date, number_places FROM journeys 
            JOIN cars ON cars.id = journeys.car_id 
            WHERE journeys.user_id = :user_id AND journeys.status = 'pending'";
    $query = $pdo->prepare($sql);
    $query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}
function getCompletCarpools($pdo, $user_id)
//récupère les trajets complets du chauffeur
{
    $sql = "SELECT journeys.id, place_departure, place_arrival, departure_time, arrival_time, date FROM journeys 
            WHERE journeys.user_id = :user_id AND journeys.status = 'complet'";
    $query = $pdo->prepare($sql);
    $query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}
function getOngoingCarpools($pdo, $user_id)
//récupère les trajets en cours du chauffeur
{
    $sql = "SELECT journeys.id, place_departure, place_arrival, departure_time, arrival_time, date, number_places FROM journeys 
            JOIN cars ON cars.id = journeys.car_id 
            WHERE journeys.user_id = :user_id AND journeys.status = 'ongoing'";
    $query = $pdo->prepare($sql);
    $query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}
function getCompletedCarpools($pdo, $user_id)
//récupère l'historique des trajets du chauffeur
{
    $sql = "SELECT journeys.id, place_departure, place_arrival, departure_time, arrival_time, date FROM journeys 
            WHERE journeys.user_id = :user_id AND journeys.status = 'completed'";
    $query = $pdo->prepare($sql);
    $query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

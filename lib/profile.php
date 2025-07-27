<?php
function getUserById($pdo, $user_id)
//récupère les infos du profil
{
    $sql = "SELECT id, pseudo, email, image, role_id FROM users WHERE id = :user_id";
    $query = $pdo->prepare($sql);
    $query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
}

function getJourneysByStatus($pdo, $user_id, $status, $role = 'passager')
{
    // Sélection de base
    $sql = "SELECT journeys.id, place_departure, place_arrival, departure_time, arrival_time, date";

    // Ajouter total_seats si le rôle est chauffeur ou mixte
    if ($role === 'chauffeur' || $role === 'passager_chauffeur') {
        $sql .= ", journeys.total_seats";
    }

    $sql .= " FROM journeys";

    // Gestion des rôles
    if ($role === 'passager') {
        // Requête pour les passagers uniquement
        $sql .= " INNER JOIN reservations ON journeys.id = reservations.journey_id 
                  WHERE reservations.user_id = :user_id AND reservations.status = :status";
    } elseif ($role === 'chauffeur') {
        // Requête pour les chauffeurs uniquement 
            $sql .= " WHERE journeys.user_id = :user_id AND journeys.status = :status";
    } elseif ($role === 'passager_chauffeur') {
        // Requête pour les deux rôles (UNION des deux requêtes)
        $sql = "(SELECT journeys.id, place_departure, place_arrival, departure_time, arrival_time, total_seats, date
                 FROM journeys 
                 WHERE journeys.user_id = :user_id AND journeys.status = :status)
                UNION
                (SELECT journeys.id, place_departure, place_arrival, departure_time, arrival_time, date 
                 FROM journeys 
                 INNER JOIN reservations ON journeys.id = reservations.journey_id 
                 WHERE reservations.user_id = :user_id AND reservations.status = :status)
                ORDER BY date DESC";
    } else {
        return []; // Si rôle inconnu, on renvoie un tableau vide
    }

    $query = $pdo->prepare($sql);
    $query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $query->bindValue(':status', $status, PDO::PARAM_STR);
    $query->execute();

    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function getCreditById(PDO $pdo, int $user_id): int|false
{
$sql = "SELECT credit FROM credits WHERE user_id = :user_id";
$query = $pdo->prepare($sql);
$query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$query->execute();
return $query->fetchColumn();
}
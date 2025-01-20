<?php
function verifyJourneys($journeys)
{
    $errors = [];

    // Vérification du champ "place_departure"
    if (!isset($journeys["place_departure"]) || empty($journeys["place_departure"])) {
        $errors["place_departure"] = "Le champ départ est obligatoire";
    }

    // Vérification du champ "place_arrival"
    if (!isset($journeys["place_arrival"]) || empty($journeys["place_arrival"])) {
        $errors["place_arrival"] = "Le champ arrivée est obligatoire";
    }

    // Vérification du champ "date"
    if (!isset($journeys["date"]) || empty($journeys["date"])) {
        $errors["date"] = "Le champ date est obligatoire";
    }

    // Si des erreurs existent, on les retourne
    if (count($errors) > 0) {
        return $errors;
    }

    // Si aucune erreur, on retourne true
    return true;
}

function getJourneys(PDO $pdo, $place_departure, $place_arrival, $date): array
{
    //  rechercher les itinéraires avec les informations du chauffeur et de la voiture qui correspondent à la date
    $sql = "SELECT journeys.id, journeys.place_departure, journeys.place_arrival, journeys.departure_time, journeys.arrival_time, journeys.price, journeys.date,
    cars.electric_car, cars.number_places, users.pseudo, users.image
     FROM journeys
            JOIN users ON user_id = users.id
            JOIN cars ON car_id = cars.id
             WHERE place_departure = :place_departure
           AND place_arrival = :place_arrival";
    // si la date n'est pas vide on ajoute date à la requête
    if (!empty($date)) {
        $sql .= " AND date = :date";
    }
    // Préparer la requête PDO
    $query = $pdo->prepare($sql);

    // Lier les paramètres
    $query->bindParam(':place_departure', $place_departure);
    $query->bindParam(':place_arrival', $place_arrival);

    if (!empty($date)) {
        $query->bindParam(':date', $date);
    }
    // Exécuter la requête
    $query->execute();
    return  $query->fetchAll(PDO::FETCH_ASSOC);
}

function getJourneysOtherDates(PDO $pdo, $place_departure, $place_arrival, $date): array
{
    // Si aucune date trouvée, recherche des dates différentes
    $sql_other = "SELECT journeys.id, journeys.place_departure, journeys.place_arrival, journeys.departure_time, journeys.arrival_time, journeys.price, journeys.date,
            cars.electric_car, cars.number_places, users.pseudo, users.image
             FROM journeys
                    JOIN users ON user_id = users.id
                    JOIN cars ON car_id = cars.id
                     WHERE place_departure = :place_departure
                   AND place_arrival = :place_arrival
                   AND date != :date";

    // Préparer la requête pour l'itinéraire avec une date différente
    $query_date_other = $pdo->prepare($sql_other);

    // Lier les paramètres
    $query_date_other->bindParam(':place_departure', $place_departure);
    $query_date_other->bindParam(':place_arrival', $place_arrival);
    $query_date_other->bindParam(':date', $date);

    // Exécuter la requête
    $query_date_other->execute();

    // Récupérer l'itinéraire avec une date différente
    return $query_date_other->fetchAll(PDO::FETCH_ASSOC);
}

function getJourneysById(PDO $pdo, int $id): array|bool
{
    $sql = "SELECT journeys.id, journeys.place_departure, journeys.place_arrival, journeys.departure_time, journeys.arrival_time, journeys.price, journeys.date,
    cars.electric_car, cars.number_places, users.pseudo, users.image
     FROM journeys
            JOIN users ON user_id = users.id
            JOIN cars ON car_id = cars.id
            WHERE journeys.id = :id";

    $query = $pdo->prepare($sql);
    $query->bindValue(":id", $id, PDO::PARAM_INT);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
}

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
    cars.number_places, cars.energy, users.pseudo, users.image
     FROM journeys
            JOIN users ON user_id = users.id
            JOIN cars ON car_id = cars.id
             WHERE place_departure = :place_departure
           AND place_arrival = :place_arrival
           AND date = :date";

    // Préparer la requête PDO (faille injection SQL)
    $query = $pdo->prepare($sql);

    // Lier les paramètres 
    $query->bindParam(':place_departure', $place_departure);
    $query->bindParam(':place_arrival', $place_arrival);
    $query->bindParam(':date', $date);
    // Exécuter la requête
    $query->execute();
    return  $query->fetchAll(PDO::FETCH_ASSOC);
}

function getJourneysOtherDates(PDO $pdo, $place_departure, $place_arrival, $date): array
{
    // Si aucune date trouvée, recherche des dates différentes
    $sql_other = "SELECT journeys.id, journeys.place_departure, journeys.place_arrival, journeys.departure_time, journeys.arrival_time, journeys.price, journeys.date,
            cars.number_places, cars.energy, users.pseudo, users.image
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
    cars.brand, cars.model, cars.color, cars.number_places, cars.energy, users.pseudo, users.image, driver_preferences.pets, driver_preferences.smoking, driver_preferences.others
     FROM journeys
            JOIN users ON user_id = users.id
            JOIN cars ON car_id = cars.id
            JOIN driver_preferences ON preferences_id = driver_preferences.id
            WHERE journeys.id = :id";

    $query = $pdo->prepare($sql);
    $query->bindValue(":id", $id, PDO::PARAM_INT);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
}

function registerJourney(PDO $pdo, string $place_departure, string $place_arrival, string $date, string $departure_time, string $arrival_time, int $price)
{

    $sql = "INSERT INTO journeys (id, place_departure, place_arrival, date, departure_time, arrival_time, price) VALUES (NULL, :place_departure, :place_arrival, :date, :departure_time, :arrival_time, :price)";

    $query = $pdo->prepare($sql);
    $query->bindParam(':place_departure', $place_departure);
    $query->bindParam(':place_arrival', $place_arrival);
    $query->bindParam(':date', $date);
    $query->bindParam(':departure_time', $departure_time);
    $query->bindParam(':arrival_time', $arrival_time);
    $query->bindParam(':price', $price, PDO::PARAM_INT);
    return $query->execute();
}

function verifyCreatJourney($journey): array|bool
{
    $errors = [];
    if (isset($journey["place_departure"])) {
        if ($journey["place_departure"] === "") {
            $errors["place_departure"] = "Le champ lieu de départ est obligatoire";
        }
    } else {
        $errors["place_departure"] = "Le champ lieu de départ n'a pas été envoyé";
    }

    if (isset($journey["place_arrival"])) {
        if ($journey["place_arrival"] === "") {
            $errors["place_arrival"] = "Le champ lieu d'arrivée est obligatoire";
        }
    } else {
        $errors["place_arrival"] = "Le champ lieu d'arrivée n'a pas été envoyé";
    }

    if (isset($journey["date"])) {
        if ($journey["date"] === "") {
            $errors["date"] = "Le champ date est obligatoire";
        }
    } else {
        $errors["date"] = "Le champ date n'a pas été envoyé";
    }

    if (isset($journey["departure_time"])) {
        if ($journey["departure_time"] === "") {
            $errors["departure_time"] = "Le champ heure de départ est obligatoire";
        }
    } else {
        $errors["departure_time"] = "Le champ heure de départ été envoyé";
    }

    if (isset($journey["arrival_time"])) {
        if ($journey["arrival_time"] === "") {
            $errors["arrival_time"] = "Le champ heure d'arrivée est obligatoire";
        }
    } else {
        $errors["arrival_time"] = "Le champ heure d'arrivée été envoyé";
    }

    if (isset($journey["price"])) {
        if ($journey["price"] === "") {
            $errors["price"] = "Le champ prix est obligatoire";
        }
    } else {
        $errors["price"] = "Le champ prix été envoyé";
    }

    if (count($errors)) {
        return $errors;
    } else {
        return true;
    }
}

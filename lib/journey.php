<?php
require_once "utils.php";
require_once "reservation.php";
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

function searchJourneys(PDO $pdo, string $place_departure, string $place_arrival, string $date, array $filters = [], bool $exactDate = true): array
{
    $conditions = [
        "LOWER(place_departure) = LOWER(:place_departure)",
        "LOWER(place_arrival) = LOWER(:place_arrival)",
        "(journeys.total_seats - IFNULL(reservation_counts.reserved_count, 0)) > 0"
    ];

    $params = [
        ":place_departure" => $place_departure,
        ":place_arrival" => $place_arrival,
    ];

    // Filtrage sur la date
    if ($exactDate) {
        $conditions[] = "date = :date";
    } else {
        $conditions[] = "date != :date";
    }
    $params[':date'] = $date;

    // Filtrage sur le prix
    if (!empty($filters['max_price'])) {
        $conditions[] = "price <= :max_price";
        $params[':max_price'] = $filters['max_price'];
    }

    // Filtrage sur le type de carburant
    if (!empty($filters['energy']) && $filters['energy'] === "eco") {
        $conditions[] = "cars.energy IN ('éléctrique', 'hybride')";
    }

    // Filtrage sur la durée
    if (!empty($filters['max_duration'])) {
        $conditions[] = "TIMESTAMPDIFF(MINUTE, journeys.departure_time, journeys.arrival_time) <= :max_duration";
        $params[':max_duration'] = $filters['max_duration'] * 60;
    }

    $where = implode(" AND ", $conditions);

    $sql = "SELECT journeys.id, journeys.place_departure, journeys.place_arrival, journeys.departure_time, journeys.arrival_time, 
                   journeys.total_seats, journeys.price, journeys.date, 
                   cars.energy, users.pseudo, users.image,
                   (journeys.total_seats - IFNULL(reservation_counts.reserved_count, 0)) AS places_restantes
            FROM journeys
            JOIN users ON user_id = users.id
            JOIN cars ON car_id = cars.id
            LEFT JOIN (
                SELECT journey_id, COUNT(*) AS reserved_count
                FROM reservations
                WHERE status = 'upcoming'
                GROUP BY journey_id
            ) AS reservation_counts ON journeys.id = reservation_counts.journey_id
            WHERE $where";

    $query = $pdo->prepare($sql);
    foreach ($params as $key => $value) {
        $query->bindValue($key, $value);
    }
    $query->execute();

    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function getJourneysById(PDO $pdo, int $id): array|bool
{
    $sql = "SELECT journeys.id, journeys.place_departure, journeys.place_arrival, journeys.departure_time, journeys.arrival_time, journeys.total_seats, journeys.price, journeys.date,
    cars.brand, cars.model, cars.color, cars.energy, users.pseudo, users.image, driver_preferences.pets, driver_preferences.smoking, driver_preferences.others
     FROM journeys
            JOIN users ON user_id = users.id
            JOIN cars ON car_id = cars.id
            LEFT JOIN driver_preferences ON users.id = driver_preferences.user_id
            WHERE journeys.id = :id";

    $query = $pdo->prepare($sql);
    $query->bindValue(":id", $id, PDO::PARAM_INT);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
}

function registerJourney(PDO $pdo, string $place_departure, string $place_arrival, string $date, string $departure_time, string $arrival_time, int $total_seats, int $price,  int $user_id, int $car_id)
{

    $sql = "INSERT INTO journeys (id, place_departure, place_arrival, date, departure_time, arrival_time, total_seats, price, user_id, car_id) VALUES (NULL, :place_departure, :place_arrival, :date, :departure_time, :arrival_time, :total_seats, :price, :user_id, :car_id)";

    $query = $pdo->prepare($sql);
    $query->bindParam(':place_departure', $place_departure);
    $query->bindParam(':place_arrival', $place_arrival);
    $query->bindParam(':date', $date);
    $query->bindParam(':departure_time', $departure_time);
    $query->bindParam(':arrival_time', $arrival_time);
    $query->bindParam(':total_seats', $total_seats, PDO::PARAM_INT);
    $query->bindParam(':price', $price, PDO::PARAM_INT);
    $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $query->bindParam(':car_id', $car_id, PDO::PARAM_INT);
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
        $errors["departure_time"] = "Le champ heure de départ n'a pas été envoyé";
    }

    if (isset($journey["arrival_time"])) {
        if ($journey["arrival_time"] === "") {
            $errors["arrival_time"] = "Le champ heure d'arrivée est obligatoire";
        }
    } else {
        $errors["arrival_time"] = "Le champ heure d'arrivée n'a pas été envoyé";
    }
    if (isset($journey["total_seats"])) {
        if ($journey["total_seats"] === "") {
            $errors["total_seats"] = "Le champ nombre de places disponibles est obligatoire";
        }
    } else {
        $errors["total_seats"] = "Le champ nombre de places disponibles n'a pas été envoyé";
    }
    if (isset($journey["price"])) {
        if ($journey["price"] === "") {
            $errors["price"] = "Le champ prix est obligatoire";
        }
    } else {
        $errors["price"] = "Le champ prix n'a pas été envoyé";
    }

    if (count($errors)) {
        return $errors;
    } else {
        return true;
    }
}
function deleteJourney(PDO $pdo, int $journey_id, int $user_id): bool
{
    $sql = "DELETE FROM journeys WHERE id = :journey_id AND user_id = :user_id";
    $query = $pdo->prepare($sql);
    $query->bindParam(':journey_id', $journey_id, PDO::PARAM_INT);
    $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    return $query->execute();
}

function getDriverByJourney($pdo, $journey_id) {
    $sql = "SELECT u.email, u.pseudo
            FROM journeys j
            JOIN users u ON j.user_id = u.id
            WHERE j.id = :journey_id ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':journey_id', $journey_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
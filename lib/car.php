<?php
function registerCar(PDO $pdo, string $brand, string $model, string $color, int $number_places, string $energy, string $registration, string $date_first_registration, int $user_id): bool
{
    $sql = "INSERT INTO cars (id, brand, model, color, number_places, energy, registration, date_first_registration, id_user) VALUES (NULL, :brand, :model, :color, :number_places, :energy, :registration, :date_first_registration, :id_user)";

    $query = $pdo->prepare($sql);
    $query->bindParam(':brand', $brand);
    $query->bindParam(':model', $model);
    $query->bindParam(':color', $color);
    $query->bindParam(':number_places', $number_places, PDO::PARAM_INT);
    $query->bindParam(':energy', $energy);
    $query->bindParam(':registration', $registration);
    $query->bindParam(':date_first_registration', $date_first_registration);
    $query->bindParam(':id_user', $user_id, PDO::PARAM_INT);
    return $query->execute();
}

function verifyCar($car): array|bool
{
    $errors = [];
    if (isset($car["brand"])) {
        if ($car["brand"] === "") {
            $errors["brand"] = "Le champ marque est obligatoire";
        }
    } else {
        $errors["brand"] = "Le champ marque n'a pas été envoyé";
    }

    if (isset($car["model"])) {
        if ($car["model"] === "") {
            $errors["model"] = "Le champ modèle est obligatoire";
        }
    } else {
        $errors["model"] = "Le champ modèle n'a pas été envoyé";
    }

    if (isset($car["color"])) {
        if ($car["color"] === "") {
            $errors["color"] = "Le champ couleur est obligatoire";
        }
    } else {
        $errors["color"] = "Le champ couleur n'a pas été envoyé";
    }

    if (isset($car["number_places"])) {
        if ($car["number_places"] === "") {
            $errors["number_places"] = "Le champ nombre de places est obligatoire";
        }
    } else {
        $errors["number_places"] = "Le champ nombre de places n'a pas été envoyé";
    }

    if (isset($car["energy"])) {
        if ($car["energy"] === "") {
            $errors["energy"] = "Le champ énergie est obligatoire";
        }
    } else {
        $errors["energy"] = "Le champ énergie n'a pas été envoyé";
    }

    if (isset($car["registration"])) {
        if ($car["registration"] === "") {
            $errors["registration"] = "Le champ numéro de plaque d'immatriculation est obligatoire";
        }
    } else {
        $errors["color"] = "Le champ numéro de plaque d'immatriculation n'a pas été envoyé";
    }

    if (isset($car["date_first_registration"])) {
        if ($car["date_first_registration"] === "") {
            $errors["date_first_registration"] = "Le champ date de première immatriculation est obligatoire";
        }
    } else {
        $errors["date_first_registration"] = "Le champ date de première immatriculation n'a pas été envoyé";
    }

    if (count($errors)) {
        return $errors;
    } else {
        return true;
    }
}

function getCars(PDO $pdo, int $user_id): array
{
    $sql = "SELECT id, brand, model, color, number_places, energy, registration, date_first_registration, id_user FROM cars WHERE id_user = :user_id";
    $query = $pdo->prepare($sql);
    $query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
    return $car ?: null;
}

function getCarById(PDO $pdo, int $car_id)
{
    $sql = "SELECT id, brand, model, color, number_places, energy, registration, date_first_registration, id_user FROM cars WHERE id = :car_id";
    $query = $pdo->prepare($sql);
    $query->bindParam(':car_id', $car_id, PDO::PARAM_INT);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
}

function updateCar(PDO $pdo, int $car_id, string $brand, string $model, string $color, int $number_places, string $energy, string $registration, string $date_first_registration)
{
    $sql = "UPDATE cars SET brand = :brand, model = :model, color = :color, number_places = :number_places, energy = :energy, registration = :registration, date_first_registration = :date_first_registration WHERE id = :car_id";

    $query = $pdo->prepare($sql);
    $query->bindParam(':brand', $brand);
    $query->bindParam(':model', $model);
    $query->bindParam(':color', $color);
    $query->bindParam(':number_places', $number_places, PDO::PARAM_INT);
    $query->bindParam(':energy', $energy);
    $query->bindParam(':registration', $registration);
    $query->bindParam(':date_first_registration', $date_first_registration);
    $query->bindParam(':car_id', $car_id, PDO::PARAM_INT);

    return $query->execute();
}
function deleteCar(PDO $pdo, int $car_id, int $user_id): bool
{
    $sql = "DELETE FROM cars WHERE id = :car_id AND id_user = :user_id";
    $query = $pdo->prepare($sql);
    $query->bindParam(':car_id', $car_id, PDO::PARAM_INT);
    $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    return $query->execute();
}

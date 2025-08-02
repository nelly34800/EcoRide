<?php
function registerPreferences(PDO $pdo, int $pets, int $smoking, string $others, int $user_id): bool
{
    $sql = "INSERT INTO driver_preferences (id, pets, smoking, others, user_id) 
    VALUES (NULL,:pets, :smoking, :others, :user_id)";
    $query = $pdo->prepare($sql);
    $query->bindParam(':pets', $pets, PDO::PARAM_INT);
    $query->bindParam(':smoking', $smoking, PDO::PARAM_INT);
    $query->bindParam(':others', $others);
    $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    return $query->execute();
}

function verifyPreferences($preferences): array|bool
{
    $errors = [];
    if (isset($preferences["pets"])) {
        if ($preferences["pets"] === "") {
            $errors["pets"] = "Le champ j'accepte les animaux est obligatoire";
        }
    } else {
        $errors["pets"] = "Le champ j'accepte les animaux n'a pas été envoyé";
    }
    if (isset($preferences["smoking"])) {
        if ($preferences["smoking"] === "") {
            $errors["smoking"] = "Le champ j'accepte de faire des pauses pour les fumeurs est obligatoire";
        }
    } else {
        $errors["smoking"] = "Le champ j'accepte de faire des pauses pour les fumeurs n'a pas été envoyé";
    }

    if (count($errors)) {
        return $errors;
    } else {
        return true;
    }
}
function getPreferences(PDO $pdo, int $user_id)
{
    $sql = "SELECT id, pets, smoking, others, user_id
    FROM driver_preferences WHERE user_id = :user_id";
    $query = $pdo->prepare($sql);
    $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
}
function getPreferencesId(PDO $pdo, int $preferences_id)
{
    $sql = "SELECT id, pets, smoking, others, user_id 
    FROM driver_preferences 
    WHERE id = :preferences_id";
    $query = $pdo->prepare($sql);
    $query->bindParam(':preferences_id', $preferences_id, PDO::PARAM_INT);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
}
function updatePreferences(PDO $pdo, int $preferences_id, int $pets, int $smoking, string $others)
{
    $sql = "UPDATE driver_preferences SET pets = :pets, smoking = :smoking, others = :others WHERE id = :preferences_id";

    $query = $pdo->prepare($sql);
    $query->bindParam(':pets', $pets, PDO::PARAM_INT);
    $query->bindParam(':smoking', $smoking, PDO::PARAM_INT);
    $query->bindParam(':others', $others);
    $query->bindParam(':preferences_id', $preferences_id, PDO::PARAM_INT);

    return $query->execute();
}

function deletePreferences(PDO $pdo, int $preferences_id, int $user_id): bool
{
    $sql = "DELETE FROM driver_preferences WHERE id = :preferences_id AND user_id = :user_id";

    $query = $pdo->prepare($sql);
    $query->bindParam(':preferences_id', $preferences_id, PDO::PARAM_INT);
    $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    return $query->execute();
}

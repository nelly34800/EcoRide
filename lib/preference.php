<?php
function registerPreferences(PDO $pdo, int $pets, int $smoking, string $others)
{

    $sql = "INSERT INTO driver_preferences (id, pets, smoking, others) VALUES (NULL, :pets, :smoking, :others)";

    $query = $pdo->prepare($sql);
    $query->bindParam(':pets', $pets, PDO::PARAM_INT);
    $query->bindParam(':smoking', $smoking, PDO::PARAM_INT);
    $query->bindParam(':others', $others);
    var_dump($pets, $smoking, $others);
    // Ajouter var_dump pour déboguer la requête SQL et ses paramètres
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
function getPreferences(PDO $pdo, int $preferences_id)
{
    $sql = "SELECT driver_preferences.id, pets, smoking, others FROM driver_preferences JOIN users ON users.preferences_id = driver_preferences.id WHERE driver_preferences.id = :preferences_id";
    $query = $pdo->prepare($sql);
    $query->bindParam(':preferences_id', $preferences_id, PDO::PARAM_INT);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
}
function getUserPreferencesId(PDO $pdo, int $user_id)
{
    $sql = "SELECT preferences_id FROM users WHERE id = :user_id";
    $query = $pdo->prepare($sql);
    $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);

    return $result['preferences_id'] ?? null; // Retourne null si pas de préférence
}

function updatePreference(PDO $pdo, $preferences_id, $pets, $smoking, $others)
{
    $sql = "UPDATE driver_preferences JOIN users ON users.preferences_id = driver_preferences.id SET pets = :pets, smoking = :smoking, others = :others WHERE driver_preferences.id = :preferences_id";

    $query = $pdo->prepare($sql);
    $query->bindParam(':pets', $pets);
    $query->bindParam(':smoking', $smoking);
    $query->bindParam(':others', $others);
    $query->bindParam(':preferences_id', $preferences_id, PDO::PARAM_INT);

    return $query->execute();
}
function deletePreference(PDO $pdo, int $preferences_id, int $user_id): bool
{
    $sql = "DELETE driver_preferences 
            FROM driver_preferences 
            JOIN users ON users.preferences_id = driver_preferences.id 
            WHERE driver_preferences.id = :preferences_id 
            AND users.id = :user_id";

    $query = $pdo->prepare($sql);
    $query->bindParam(':preferences_id', $preferences_id, PDO::PARAM_INT);
    $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    return $query->execute();
}

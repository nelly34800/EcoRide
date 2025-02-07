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

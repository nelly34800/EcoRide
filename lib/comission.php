<?php
require_once "pdo.php";
require_once "reservation.php";
require_once "journey.php";


function registerCommission(PDO $pdo, int $journey_id): bool {
    // Vérifie si la commission a déjà été enregistrée
    $sql = "SELECT COUNT(*) FROM commissions WHERE journey_id = :journey_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':journey_id', $journey_id, PDO::PARAM_INT);
    $stmt->execute();
    $alreadyExists = $stmt->fetchColumn();

    if ($alreadyExists > 0) {
        return false; // Commission déjà appliquée
    }

    // Récupère le trajet
    $journey = getJourneysById($pdo, $journey_id);
    if (!$journey) {
        return false;
    }

    // Vérifie combien il y a de réservations et combien sont validées
    $countTotalSql = "SELECT COUNT(*) FROM reservations WHERE journey_id = :journey_id";
    $countTotalStmt = $pdo->prepare($countTotalSql);
    $countTotalStmt->bindValue(':journey_id', $journey_id, PDO::PARAM_INT);
    $countTotalStmt->execute();
    $total = $countTotalStmt->fetchColumn();

    $countValidatedSql = "SELECT COUNT(*) FROM reservations WHERE journey_id = :journey_id AND status = 'completed'";
    $countValidatedStmt = $pdo->prepare($countValidatedSql);
    $countValidatedStmt->bindValue(':journey_id', $journey_id, PDO::PARAM_INT);
    $countValidatedStmt->execute();
    $validated = $countValidatedStmt->fetchColumn();

    if ($total == 0 || $validated < $total) {
        return false; // Tous les passagers n'ont pas encore validé
    }

    // Applique la commission (déduit 2 crédits au chauffeur)
    $deductSql = "UPDATE credits SET credit = credit - :commission WHERE user_id = :user_id";
    $deductStmt = $pdo->prepare($deductSql);
    $deductStmt->bindValue(':commission', 2, PDO::PARAM_INT);
    $deductStmt->bindValue(':user_id', $journey['user_id'], PDO::PARAM_INT);
    $deductStmt->execute();

    // Enregistre la commission
    $insertSql = "INSERT INTO commissions (journey_id, amount, driver_id) VALUES (:journey_id, :amount, :driver_id)";
    $insertStmt = $pdo->prepare($insertSql);
    $insertStmt->bindValue(':journey_id', $journey_id, PDO::PARAM_INT);
    $insertStmt->bindValue(':amount', 2, PDO::PARAM_INT);
    $insertStmt->bindValue(':driver_id', $journey['user_id'], PDO::PARAM_INT);
    $insertStmt->execute();

    return true;
}
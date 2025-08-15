<?php
require_once "pdo.php";
require_once "journey.php";
require_once "reservation.php";
require_once "comission.php";
session_start();

if (!isset($_SESSION['user']['id'])) {
    header("Location: ../signin.php");
    exit();
}

$user_id = $_SESSION['user']['id'];
$journey_id = $_POST['journey_id'] ?? null;

if ($journey_id) {
    $sql = "UPDATE reservations SET status = 'completed' WHERE user_id = :user_id AND journey_id = :journey_id";
    $query = $pdo->prepare($sql);
    $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $query->bindParam(':journey_id', $journey_id, PDO::PARAM_INT);
    $query->execute();

    // récupération du trajet
    $journey = getJourneysById($pdo, $journey_id);
    if (!$journey) {
        header("Location: ../passager.php?error");
        exit();
    }

    //crédite le chauffeur
    $credit = (int) $journey['price'];
    $driver_id = (int)$journey['user_id'];

    $updateCreditSql = "UPDATE credits SET credit = credit + :amount WHERE user_id = :user_id";
    $updateCreditStmt = $pdo->prepare($updateCreditSql);
    $updateCreditStmt->bindValue(':amount', $credit, PDO::PARAM_INT);
    $updateCreditStmt->bindValue(':user_id', $driver_id, PDO::PARAM_INT);
    $updateCreditStmt->execute();

     // Appliquer la commission si tous les passagers ont validé
    registerCommission($pdo, $journey_id);

    header("Location: ../passager_chauffeur.php?success=6");
    exit();
}

header("Location: ../passager_chauffeur.php?error");
exit();

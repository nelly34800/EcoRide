<?php
require_once "../lib/pdo.php";
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

    header("Location: ../passager_chauffeur.php?success=6");
    exit();
}

header("Location: ../passager_chauffeur.php?error");
exit();

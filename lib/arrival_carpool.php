<?php
require_once "pdo.php";

if (isset($_POST['journey_id'])) {
    $journey_id = $_POST['journey_id'];
    // Met à jour du statut "terminé"
    $sql = "UPDATE journeys SET status = 'completed' WHERE id = :journey_id";
    $query = $pdo->prepare($sql);
    $query->bindValue(':journey_id', $journey_id, PDO::PARAM_INT);
    $query->execute();

    header("Location: ../chauffeur.php?success=2");  // Redirection avec succès
    exit();
} else {
    header("Location: ../chauffeur.php?error=2");  // Redirection en cas d'erreur
    exit();
}

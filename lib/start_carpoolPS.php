<?php
require_once 'pdo.php';  // Connexion à la BDD

if (isset($_POST['journey_id'])) {
    $journey_id = $_POST['journey_id'];
    // Met à jour du statut "en cours"
    $sql = "UPDATE journeys SET status = 'ongoing' WHERE id = :journey_id";
    $query = $pdo->prepare($sql);
    $query->bindValue(':journey_id', $journey_id, PDO::PARAM_INT);
    $query->execute();

    header("Location: ../passager_chauffeur.php?success=1");  // Redirection avec succès
    exit();
} else {
    header("Location: ../passager_chauffeur.php?error=1");  // Redirection en cas d'erreur
    exit();
}

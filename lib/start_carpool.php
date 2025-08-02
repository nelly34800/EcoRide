<?php
require_once 'pdo.php';  // Connexion à la BDD

// Démarrer une transaction
$pdo->beginTransaction();

try {
    // Vérifie si l'ID du trajet a été envoyé
    if (isset($_POST['journey_id'])) {
        $journey_id = $_POST['journey_id'];
        // Met à jour du statut "en cours"
        $sql = "UPDATE journeys SET status = 'ongoing' WHERE id = :journey_id";
        $query = $pdo->prepare($sql);
        $query->bindValue(':journey_id', $journey_id, PDO::PARAM_INT);
        $query->execute();
        // Met à jour les réservations des passagers associés au trajet
        $sql = "UPDATE reservations SET status = 'ongoing' WHERE journey_id = :journey_id";
        $query = $pdo->prepare($sql);
        $query->bindValue(':journey_id', $journey_id, PDO::PARAM_INT);
        $query->execute();

        // Valide la transaction
        $pdo->commit();

        // Redirection avec succès
        header("Location: ../chauffeur.php?success=1");  // Redirection avec succès
        exit();
    } else {

        // Annuler la transaction si l'ID du trajet n'est pas trouvé
        throw new Exception("ID du trajet manquant");
    }
} catch (Exception $e) {
    // En cas d'erreur, annule la transaction
    $pdo->rollBack();

    // Redirection avec erreur
    header("Location: ../chauffeur.php?error=1");  // Redirection en cas d'erreur
    exit();
}

<?php
require_once "templates/header.php";

// Récupération des paramètres
$type = $_GET['type'] ?? 'inconnu';
$status = $_GET['status'] ?? 'error';

// Liste des messages personnalisés
$messages = [
    'creation' => [
        'success' => "La création de votre covoiturage a été réalisée avec succès !",
    ],
    'reservation' => [
        'success' => "Votre réservation a bien été enregistrée!",
    ],
        'annulation' => [
        'success' => "La réservation a bien été annulée!",
        'error'   => "Échec de l'annulation de la réservation!",
    ],
    'suppression' => [
        'success' => "Le covoiturage a été supprimé avec succès!",
        'error'   => "Erreur lors de la suppression du covoiturage!",
    ],
    'problem' => [
        'success' => "Le problème a été signalé avec succès!",
    ],
];

// Choix du message
$message = $messages[$type][$status] ?? "Action inconnue ou invalide.";
?>

<div class="container p-4 text-center">
    <h1>Confirmation</h1>
    <p><?= htmlspecialchars($message) ?></p>
    <a href="index.php" class="btn btn-primary">Retour à l'accueil</a>
</div>

<?php
require_once "templates/footer.php";
?>
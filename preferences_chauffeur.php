<?php
require_once "templates/header.php";
require_once "Lib/pdo.php";
require_once "Lib/utils.php";
require_once "lib/preference.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user']['id'])) {
    header("Location: signin.php"); // Redirige vers la page de connexion si non connecté
    exit();
}

$user_id = $_SESSION['user']['id']; // Récupère l'ID de l'utilisateur connecté

// Récupérer l'ID des préférences
$preferences_id = getUserPreferencesId($pdo, $user_id);
$preferences = $preferences_id ? getPreferences($pdo, $preferences_id) : null;

if (isset($_GET['success'])): ?>
    <div class="alert alert-success">Vos préférences ont été supprimées avec succès !</div>
<?php elseif (isset($_GET['error'])): ?>
    <div class="alert alert-danger">Erreur lors de la suppression de vos préférences.</div>
<?php endif; ?>
<div class="container">
    <h1>Mes préférences</h1>
    <a class="btn btn-primary m-2" href="ajout_preferences_chauffeur.php">Ajouter mes préférences</a>

    <?php if ($preferences): ?>
        <p class="card-text">J'accepte les animaux: <?php choice($preferences['pets']); ?></p>
        <p class="card-text">J'accepte de faire des pauses pour les fumeurs: <?php choice($preferences['smoking']); ?></p>
        <p class="card-text">autres préférences: <?= htmlspecialchars($preferences['others']); ?></p>
        <div class="m-2">
            <a href="ajout_preferences_chauffeur.php?id=<?= $preferences['id']; ?>" class="btn btn-primary m-2">Modifier vos préférences</a>
            <a href="sup_preferences_chauffeur.php?id=<?= $preferences['id']; ?>" class="btn btn-dark m-2" onclick="return confirm('Êtes-vous sûr de vouloir supprimer vos préférences ?');">Supprimer vos préférences</a>
        </div>
    <?php else: ?>
        <p>Aucune préférences enregistrée.</p>
    <?php endif; ?>
</div>

<?php
require_once "templates/footer.php";
?>
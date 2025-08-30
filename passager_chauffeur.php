<?php
require_once "lib/pdo.php";
require_once "lib/user.php";
require_once "lib/utils.php";
require_once "lib/role.php";
require_once "lib/journey.php";
require_once "lib/profile.php";
require_once "templates/header.php";

$error404 = false;
// Vérifie si la session est active et récupére l'utilisateur connecté
if (isset($_SESSION['user'])) {
    // Récupérer l'ID de l'utilisateur depuis la session
    $user_id = $_SESSION['user']['id'];
    $credit = getCreditById($pdo, $user_id);
    $user = getUserById($pdo, $user_id);
    if (!$user) {
        $error404 = true;
    }
} else {
    $error404 = true; // Si l'utilisateur n'est pas connecté
}
if ($error404) {
    echo "<h1>Erreur: Utilisateur non trouvé</h1>";
    exit();
}
// Vérifier que l'utilisateur est passager et chauffeur
verifRole(6);

// Pour les trajets où l'utilisateur est passager
$passenger_upcoming = getJourneysByStatus($pdo, $user_id, 'upcoming', 'passager');
$passenger_ongoing = getJourneysByStatus($pdo, $user_id, 'ongoing', 'passager');
$passenger_completed = getJourneysByStatus($pdo, $user_id, 'completed', 'passager');

// Pour les trajets où l'utilisateur est chauffeur
$pending = getJourneysByStatus($pdo, $user_id, 'pending', 'chauffeur');
$complet = getJourneysByStatus($pdo, $user_id, 'complet', 'chauffeur');
$ongoing = getJourneysByStatus($pdo, $user_id, 'ongoing', 'chauffeur');
$completed = getJourneysByStatus($pdo, $user_id, 'completed', 'chauffeur');

if (isset($_GET['success'])): ?>
    <?php if ($_GET['success'] == 1): ?>
        <div class="alert alert-success">Trajet terminé avec succès !</div>
    <?php elseif ($_GET['success'] == 2): ?>
        <div class="alert alert-warning">Problème signalé. Un administrateur va vérifier.</div>
    <?php elseif ($_GET['success'] == 5): ?>
        <div class="alert alert-success">Covoiturage démarré avec succès !</div>
    <?php elseif ($_GET['success'] == 6): ?>
        <div class="alert alert-success">Votre trajet a été validé!</div>
    <?php endif; ?>
<?php elseif (isset($_GET['error'])): ?>
    <div class="alert alert-danger">Erreur lors de l'opération.</div>
<?php endif; ?>

    <div class="container p-4">
        <div class="row row-cols-1 row-cols-md-2">
            <div class="col-md-3 p-2">
                <h1> <?= htmlspecialchars($user["pseudo"]) ?> </h1>
                <img src="<?= htmlspecialchars(getAvatar($user['image'])); ?>" class="bd-placeholder-img rounded-circle" width="100" height="100" alt="photo de l'utilisateur"><br>
                <p>Crédits disponibles : <strong><?= htmlspecialchars($credit) ?></strong></p><br>
                <a class="text-center" href="ajout_covoiturage.php">Ajouter un covoiturage</a><br>
                <a class="text-center" href="voitures.php">Gérer mes voitures</a><br>
                <a class="text-center" href="preferences_chauffeur.php">Gérer mes préférences</a><br>
                <form action="lib/change_role.php" method="post">
                </form>
            </div>
            <div class=" col-md-9 p-2">
                <h3>Espace passager</h3>
                <div class="container p-4 d-flex flex-column">
                    <?php include "templates/carpools_list_pcp.php"; ?>
                </div>
                <h3>Espace chauffeur</h3>
                <div class="container d-flex flex-column">
                    <?php include "templates/carpools_list_pcc.php"; ?>
                </div>
            </div>
        </div>
    </div>

<?php
require_once "templates/footer.php";
?>
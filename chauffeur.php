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
// Vérifier que l'utilisateur est chauffeur
verifRole(2);

// Récupérer les trajets du chauffeur
$pending = getJourneysByStatus($pdo, $user_id, 'pending', 'chauffeur');
$complet = getJourneysByStatus($pdo, $user_id, 'complet', 'chauffeur');
$ongoing = getJourneysByStatus($pdo, $user_id, 'ongoing', 'chauffeur');
$completed = getJourneysByStatus($pdo, $user_id, 'completed', 'chauffeur');

if (isset($_GET['success'])): ?>
    <?php if ($_GET['success'] == 1): ?>
        <div class="alert alert-success">Covoiturage démarré avec succès !</div>
    <?php elseif ($_GET['success'] == 2): ?>
        <div class="alert alert-success">Trajet terminé avec succès !</div>
    <?php endif; ?>
<?php elseif (isset($_GET['error'])): ?>
    <div class="alert alert-danger">Erreur lors de l'opération.</div>
<?php endif; ?>

    <div class="container p-4">
        <div class="row m-0">
            <div class="col-md-3 p-2">
                <h1> <?= htmlspecialchars($user["pseudo"]) ?> </h1><br>
                <img src="<?= htmlspecialchars(getAvatar($user['image'])); ?>" class="bd-placeholder-img rounded-circle" width="100" height="100" alt="photo de l'utilisateur"><br>
                <p>Crédits disponibles : <strong><?= htmlspecialchars($credit) ?></strong></p><br>
                <a class="text-center" href="ajout_covoiturage.php">Ajouter un covoiturage</a><br>
                <a class="text-center" href="voitures.php">Gérer mes voitures</a><br>
                <a class="text-center" href="preferences_chauffeur.php">Gérer mes préférences</a><br>
                <form action="lib/change_role.php" method="post">
                    <button type="submit" class="btn btn-primary">Devenir passager & chauffeur</button>
                </form>
            </div>
            <div class="col-md-9 p-2">
                <div class="container d-flex flex-column">
                    <?php include "templates/carpools_list.php"; ?>
                </div>
            </div>
        </div>
    </div>

<?php
require_once "templates/footer.php";
?>
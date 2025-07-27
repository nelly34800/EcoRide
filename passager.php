<?php
require_once "lib/pdo.php";
require_once "lib/user.php";
require_once "lib/utils.php";
require_once "lib/role.php";
require_once "lib/reservation.php";
require_once "lib/profile.php";
require_once "templates/header.php";

$error404 = false;
// Vérifie si la session est active et récupére l'utilisateur connecté
if (isset($_SESSION["user"])) {
    // Récupérer l'ID de l'utilisateur depuis la session
    $user_id = $_SESSION["user"]["id"];
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
// Vérifier que l'utilisateur est passager
verifRole(3);
// Récupérer les trajets passager
$upcoming = getJourneysByStatus($pdo, $user_id, 'upcoming', 'passager');
$ongoing = getJourneysByStatus($pdo, $user_id, 'ongoing', 'passager');
$completed = getJourneysByStatus($pdo, $user_id, 'completed', 'passager');
$reported = getJourneysByStatus($pdo, $user_id, 'problem_reported', 'passager');

if (isset($_GET['success'])): ?>
    <?php if ($_GET['success'] == 1): ?>
        <div class="alert alert-success">Trajet terminé avec succès !</div>
    <?php elseif ($_GET['success'] == 2): ?>
        <div class="alert alert-success">Votre trajet a été validé !</div>
    <?php elseif ($_GET['success'] == 4): ?>
        <div class="alert alert-warning">Problème signalé. Un administrateur va vérifier.</div>
    <?php endif; ?>
<?php elseif (isset($_GET['error'])): ?>
    <div class="alert alert-danger">Erreur lors de l'opération.</div>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon compte, espace passager</title>
</head>

<body>
    <div class="container p-4">
        <div class="row row-cols-1 row-cols-md-2 align-items-center">
            <div class="col-md-3 p-2">
                <h1> <?= htmlspecialchars($user["pseudo"]) ?> </h1><br>
                <img src="<?= htmlspecialchars(getAvatar($user['image'])); ?>" class="bd-placeholder-img rounded-circle" width="100" height="100" alt="photo de l'utilisateur"><br>
                <p>Crédits disponibles : <strong><?= htmlspecialchars($credit) ?></strong></p><br>
                <form action="lib/change_role.php" method="post">
                    <button type="submit" class="btn btn-primary">Devenir passager & chauffeur</button>
                </form>
            </div>
            <div class="col-md-9 p-2">
                <?php include "templates/carpools_list_p.php"; ?>
            </div>
        </div>
    </div>
    </div>
</body>

</html>
<?php
require_once "templates/footer.php";
?>
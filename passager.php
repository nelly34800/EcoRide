<?php
require_once "lib/pdo.php";
require_once "lib/user.php";
require_once "lib/utils.php";
require_once "lib/role.php";
require_once "lib/profile.php";
require_once "templates/header.php";

// Vérifier que l'utilisateur est passager
verifRole(3);

$error404 = false;
$user_id = 11;
//if (isset($_GET["user_id"])) {
//$user_id = (int)$_GET["user_id"];
$user = getUserById($pdo, $user_id);
if (!$user) {
    $error404 = true;
}
//} else {
//  $error404 = true;
//}

if ($error404) {
    echo "<h1>Erreur: Utilisateur non trouvé</h1>";
    exit();
}
?>

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
            <div class="col-12 col-md-6">
                <h1> <?= htmlspecialchars($user["pseudo"]) ?> </h1>
                <img src="<?= htmlspecialchars(getAvatar($user['image'])); ?>" class="bd-placeholder-img rounded-circle" width="100" height="100" alt="photo de l'utilisateur">
                <p class="text-center">

                </p>
            </div>
            <div class="col-12 col-md-6">
                <a class="btn btn-primary" href="">Devenir passager/chauffeur</a>
            </div>
        </div>
    </div>

    <div class="container p-4 d-flex flex-column">
        <div class="table-responsive p-4">
            <h4>covoiturages en attente: </h4>
            <?php
            // affichage pour un utilisateur
            $upcoming = getUserJourneysUpcoming($pdo, $user_id);
            if (empty($upcoming)) {
                echo "<p>Vous n'avez pas encore de covoiturage en attente.</p>";
            } else {
            ?>
                <table class="table table-bordered table-striped">
                    <tr>
                        <th class="date-cell">Date</th>
                        <th class="d-none d-md-table-cell" colspan="2">départ</th>
                        <th class="d-none d-md-table-cell" colspan="2">arrivée</th>
                    </tr>

                    <?php foreach ($upcoming as $journey) { ?>
                        <tr>
                            <td><?= htmlspecialchars(changeDateFormat($journey['date'])); ?></td>
                            <td class="d-none d-md-table-cell"><?= htmlspecialchars($journey['place_departure']); ?></td>
                            <td class="d-none d-md-table-cell"><?= htmlspecialchars(changeHourFormat($journey['departure_time'])); ?></td>
                            <td class="d-none d-md-table-cell"><?= htmlspecialchars($journey['place_arrival']); ?></td>
                            <td class="d-none d-md-table-cell"><?= htmlspecialchars(changeHourFormat($journey['arrival_time'])); ?></td>
                            <td class="d-md-none" colspan="4">
                                Départ: <br><?php echo htmlspecialchars($journey['place_departure']); ?> - <?php echo htmlspecialchars(changeHourFormat($journey['departure_time'])); ?><br>
                                Arrivée: <br><?php echo htmlspecialchars($journey['place_arrival']); ?> - <?php echo htmlspecialchars(changeHourFormat($journey['arrival_time'])); ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } ?>
        </div>
        <div class="table-responsive p-4">
            <h1>historique des covoiturages: </h1>
            <?php
            // affichage pour l'utilisateur
            $history = getUserJourneysCompleted($pdo, $user_id);
            if (empty($history)) {
                echo "<p>Vous n'avez pas encore effectué de covoiturage.</p>";
            } else {
            ?>
                <table class="table table-bordered table-striped">
                    <tr>
                        <th class="date-cell">Date</th>
                        <th class="d-none d-md-table-cell" colspan="2">départ</th>
                        <th class="d-none d-md-table-cell" colspan="2">arrivée</th>
                    </tr>
                    <?php
                    foreach ($history as $journey) { ?>
                        <tr>
                            <td><?= htmlspecialchars(changeDateFormat($journey['date'])); ?></td>
                            <td class="d-none d-md-table-cell"><?= htmlspecialchars($journey['place_departure']); ?></td>
                            <td class="d-none d-md-table-cell"><?= htmlspecialchars(changeHourFormat($journey['departure_time'])); ?></td>
                            <td class="d-none d-md-table-cell"><?= htmlspecialchars($journey['place_arrival']); ?></td>
                            <td class="d-none d-md-table-cell"><?= htmlspecialchars(changeHourFormat($journey['arrival_time'])); ?></td>
                            <td class="d-md-none" colspan="4">
                                Départ: <br><?php echo htmlspecialchars($journey['place_departure']); ?> - <?php echo htmlspecialchars(changeHourFormat($journey['departure_time'])); ?><br>
                                Arrivée: <br><?php echo htmlspecialchars($journey['place_arrival']); ?> - <?php echo htmlspecialchars(changeHourFormat($journey['arrival_time'])); ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } ?>
        </div>
    </div>
</body>

</html>
<?php
require_once "templates/footer.php";
?>
<?php
require_once "lib/pdo.php";
require_once "lib/user.php";
require_once "lib/utils.php";
require_once "lib/role.php";
require_once "lib/profile.php";
require_once "templates/header.php";

$error404 = false;
// Vérifie si la session est active et récupére l'utilisateur connecté
if (isset($_SESSION['user'])) {
    // Récupérer l'ID de l'utilisateur depuis la session
    $user_id = $_SESSION['user']['id'];
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

if (isset($_GET['success'])): ?>
    <?php if ($_GET['success'] == 1): ?>
        <div class="alert alert-success">Covoiturage démarré avec succès !</div>
    <?php elseif ($_GET['success'] == 2): ?>
        <div class="alert alert-success">Trajet terminé avec succès !</div>
    <?php endif; ?>
<?php elseif (isset($_GET['error'])): ?>
    <div class="alert alert-danger">Erreur lors de l'opération.</div>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon compte, Espace chauffeur</title>
</head>

<body>
    <div class="container p-4">
        <div class="row m-0">
            <div class="col-md-3 p-2">
                <h1> <?= htmlspecialchars($user["pseudo"]) ?> </h1><br>
                <img src="<?= htmlspecialchars(getAvatar($user['image'])); ?>" class="bd-placeholder-img rounded-circle" width="100" height="100" alt="photo de l'utilisateur"><br>
                <a class="text-center" href="ajout_covoiturage.php">Ajouter un covoiturage</a><br>
                <a class="text-center" href="voitures.php">Gérer mes voitures</a><br>
                <a class="text-center" href="preferences_chauffeur.php">Gérer mes préférences</a><br>
                <form action="lib/change_role.php" method="post">
                    <button type="submit" class="btn btn-primary">Devenir passager & chauffeur</button>
                </form>
            </div>
            <div class=" col-md-9 p-2">
                <div class="container d-flex flex-column">
                    <div class="table-responsive p-4">
                        <h4>covoiturages à venir: </h4>
                        <?php
                        // affichage pour un utilisateur
                        $pending =  getPendingCarpools($pdo, $user_id);
                        if (empty($pending)) {
                            echo "<p>Vous n'avez pas encore de covoiturage en attente.</p>";
                        } else {
                        ?>
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th class="date-cell">Date</th>
                                    <th class="d-none d-md-table-cell" colspan="2">départ</th>
                                    <th class="d-none d-md-table-cell" colspan="2">arrivée</th>
                                    <th class="d-none d-md-table-cell">place dispo </th>
                                    <th class="d-none d-md-table-cell">Action</th>
                                </tr>

                                <?php foreach ($pending as $journey) { ?>
                                    <tr>
                                        <td><?= htmlspecialchars(changeDateFormat($journey['date'])); ?></td>
                                        <td class="d-none d-md-table-cell"><?= htmlspecialchars($journey['place_departure']); ?></td>
                                        <td class="d-none d-md-table-cell"><?= htmlspecialchars(changeHourFormat($journey['departure_time'])); ?></td>
                                        <td class="d-none d-md-table-cell"><?= htmlspecialchars($journey['place_arrival']); ?></td>
                                        <td class="d-none d-md-table-cell"><?= htmlspecialchars(changeHourFormat($journey['arrival_time'])); ?></td>
                                        <td class="d-none d-md-table-cell"><?= htmlspecialchars($journey['number_places']); ?></td>
                                        <td class="d-none d-md-table-cell">
                                            <form method="POST" action="lib/start_carpool.php">
                                                <input type="hidden" name="journey_id" value="<?= htmlspecialchars($journey['id']) ?>">
                                                <button class="btn btn-primary" type="submit">Démarrer le covoiturage</button>
                                            </form>
                                        </td>
                                        <td class="d-md-none" colspan="6">
                                            Départ: <br><?= htmlspecialchars($journey['place_departure']); ?> - <?= htmlspecialchars(changeHourFormat($journey['departure_time'])); ?><br>
                                            Arrivée: <br><?= htmlspecialchars($journey['place_arrival']); ?> - <?= htmlspecialchars(changeHourFormat($journey['arrival_time'])); ?><br>
                                            Place dispo: <?= htmlspecialchars($journey['number_places']); ?> - <br>
                                            <form method="POST" action="lib/start_carpool.php">
                                                <input type="hidden" name="journey_id" value="<?= htmlspecialchars($journey['id']) ?>">
                                                <button class="btn btn-primary" type="submit">Démarrer le covoiturage</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        <?php } ?>
                    </div>
                </div>
                <div class="container d-flex flex-column">
                    <div class="table-responsive p-4">
                        <h4>covoiturages complet: </h4>
                        <?php
                        // affichage pour un utilisateur
                        $complet =  getCompletCarpools($pdo, $user_id);
                        if (empty($complet)) {
                            echo "<p>Vous n'avez pas encore de covoiturage complet.</p>";
                        } else {
                        ?>
                            <table class="table table-bordered table-striped">
                                <tr>
                                <tr>
                                    <th class="date-cell">Date</th>
                                    <th class="d-none d-md-table-cell" colspan="2">départ</th>
                                    <th class="d-none d-md-table-cell" colspan="2">arrivée</th>
                                    <th class="d-none d-md-table-cell">Action</th>
                                </tr>

                                </tr>

                                <?php foreach ($complet as $journey) { ?>
                                    <tr>
                                        <td><?= htmlspecialchars(changeDateFormat($journey['date'])); ?></td>
                                        <td class="d-none d-md-table-cell"><?= htmlspecialchars($journey['place_departure']); ?></td>
                                        <td class="d-none d-md-table-cell"><?= htmlspecialchars(changeHourFormat($journey['departure_time'])); ?></td>
                                        <td class="d-none d-md-table-cell"><?= htmlspecialchars($journey['place_arrival']); ?></td>
                                        <td class="d-none d-md-table-cell"><?= htmlspecialchars(changeHourFormat($journey['arrival_time'])); ?></td>
                                        <td class="d-none d-md-table-cell">
                                            <form method="POST" action="lib/start_carpool.php">
                                                <input type="hidden" name="journey_id" value="<?= htmlspecialchars($journey['id']) ?>">
                                                <button class="btn btn-primary" type="submit">Démarrer le covoiturage</button>
                                            </form>
                                        </td>
                                        <td class="d-md-none" colspan="4">
                                            Départ: <br><?= htmlspecialchars($journey['place_departure']); ?> - <?= htmlspecialchars(changeHourFormat($journey['departure_time'])); ?><br>
                                            Arrivée: <br><?= htmlspecialchars($journey['place_arrival']); ?> - <?= htmlspecialchars(changeHourFormat($journey['arrival_time'])); ?><br>
                                            <form method="POST" action="lib/start_carpool.php">
                                                <input type="hidden" name="journey_id" value="<?= htmlspecialchars($journey['id']) ?>">
                                                <button class="btn btn-primary" type="submit">Démarrer le covoiturage</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        <?php } ?>
                    </div>
                </div>
                <div class="container d-flex flex-column">
                    <div class="table-responsive p-4">
                        <h4>covoiturages en cours: </h4>
                        <?php
                        // affichage pour un utilisateur
                        $ongoing =  getOngoingCarpools($pdo, $user_id);
                        if (empty($ongoing)) {
                            echo "<p>aucun covoiturage en cours.</p>";
                        } else {
                        ?>
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th class="date-cell">Date</th>
                                    <th class="d-none d-md-table-cell" colspan="2">départ</th>
                                    <th class="d-none d-md-table-cell" colspan="2">arrivée</th>
                                    <th class="d-none d-md-table-cell">place dispo </th>
                                    <th class="d-none d-md-table-cell">Action</th>
                                </tr>

                                <?php foreach ($ongoing as $journey) { ?>
                                    <tr>
                                        <td><?= htmlspecialchars(changeDateFormat($journey['date'])); ?></td>
                                        <td class="d-none d-md-table-cell"><?= htmlspecialchars($journey['place_departure']); ?></td>
                                        <td class="d-none d-md-table-cell"><?= htmlspecialchars(changeHourFormat($journey['departure_time'])); ?></td>
                                        <td class="d-none d-md-table-cell"><?= htmlspecialchars($journey['place_arrival']); ?></td>
                                        <td class="d-none d-md-table-cell"><?= htmlspecialchars(changeHourFormat($journey['arrival_time'])); ?></td>
                                        <td class="d-none d-md-table-cell"><?= htmlspecialchars($journey['number_places']); ?></td>
                                        <td class="d-none d-md-table-cell">
                                            <form method="POST" action="lib/arrival_carpool.php">
                                                <input type="hidden" name="journey_id" value="<?= htmlspecialchars($journey['id']) ?>">
                                                <button class="btn btn-primary" type="submit">Arrivée à destination</button>
                                            </form>
                                        </td>
                                        <td class="d-md-none" colspan="6">
                                            Départ: <br><?= htmlspecialchars($journey['place_departure']); ?> - <?= htmlspecialchars(changeHourFormat($journey['departure_time'])); ?><br>
                                            Arrivée: <br><?= htmlspecialchars($journey['place_arrival']); ?> - <?= htmlspecialchars(changeHourFormat($journey['arrival_time'])); ?><br>
                                            Place dispo: <?= htmlspecialchars($journey['number_places']); ?> - <br>
                                            <form method="POST" action="lib/arrival_carpool.php">
                                                <input type="hidden" name="journey_id" value="<?= htmlspecialchars($journey['id']) ?>">
                                                <button class="btn btn-primary" type="submit">Arrivée à destination</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        <?php } ?>
                    </div>
                </div>
                <div class="container d-flex flex-column">
                    <div class="table-responsive p-4">
                        <h4>historique des covoiturages: </h4>
                        <?php
                        // affichage pour un utilisateur
                        $completed =  getCompletedCarpools($pdo, $user_id);
                        if (empty($completed)) {
                            echo "<p>Vous n'avez pas encore d'historique de covoiturage.</p>";
                        } else {
                        ?>
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th class="date-cell">Date</th>
                                    <th class="d-none d-md-table-cell" colspan="2">départ</th>
                                    <th class="d-none d-md-table-cell" colspan="2">arrivée</th>
                                </tr>

                                <?php foreach ($completed as $journey) { ?>
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
            </div>
</body>

</html>
<?php
require_once "templates/footer.php";
?>
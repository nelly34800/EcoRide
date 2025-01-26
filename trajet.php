<?php
require_once "templates/header.php";
require_once "Lib/pdo.php";
require_once "lib/journey.php";
require_once "lib/utils.php";

$error404 = false;

if (isset($_GET["id"])) {
    $id = (int)$_GET["id"];
    $journey = getJourneysById($pdo, $id,);
    if (!$journey) {
        $error404 = true;
    }
} else {
    $error404 = true;
}
?>
<div class="hero-scene">
    <img src="assets/img/BanTrajet.jpg" alt="banniére décorative" width="100%">
</div>

<div class="col md-4 my-4 d-flex">
    <?php if (isset($journey) && $journey): ?>
        <div class="card w-100">
            <img src="<?= htmlspecialchars(getAvatar($journey['image'])); ?>" class="bd-placeholder-img rounded-circle" width="100" height="100" alt="photo du chauffeur">
            <div class="card-body-dark p-4">
                <h3><?= htmlspecialchars($journey['pseudo']); ?></h3>
                <p class="card-text-light">
                    <?= htmlspecialchars(changeDateFormatJour($journey['date'])); ?> <br>
                    heure départ: <?= htmlspecialchars(changeHourFormat($journey['departure_time'])); ?> <br>
                    heure arrivée prévue: <?= htmlspecialchars(changeHourFormat($journey['arrival_time'])); ?> <br>
                    durée prévue du trajet: <?= htmlspecialchars(journeyTime($journey['departure_time'], $journey['arrival_time'])); ?> <br></p>
                <p class="card-text-light">
                    place dispo: <?= htmlspecialchars($journey['number_places']); ?> <br></p>
                <p class="card-text-light">
                    voyage éco: <?php convertEnergy($journey['energy']); ?> <br>
                    marque: <?= htmlspecialchars($journey['brand']); ?> <br>
                    modèle: <?= htmlspecialchars($journey['model']); ?> <br>
                    couleur: <?= htmlspecialchars($journey['color']); ?> <br>
                    énérgie: <?= htmlspecialchars($journey['energy']); ?> <br>

                </p>
                <p class="card-text-light"> préférences: <br>
                    animal: <?php choice($journey['pets']); ?>
                    tabac: <?php choice($journey['smoking']); ?><br>
                    autre: <?= htmlspecialchars($journey['others']); ?> </p>
                <p class="card-text-light p-1">
                    tarif: <?= htmlspecialchars($journey['price']); ?> crédits<br></p>
                <a href="trajet.php" class="btn btn-primary">Participer au trajet</a>
            </div>
        </div>
    <?php else: ?>
        <h1>Le covoiturage est introuvable</h1>
    <?php endif; ?>
</div>

<?php
require_once "templates/footer.php";
?>
<?php
require_once "templates/header.php";
require_once "Lib/pdo.php";
require_once "lib/journey.php";

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

<div class="col md-4 my-2 d-flex">
    <?php if (isset($journey) && $journey): ?>
        <div class="card w-100">
            <img src="/uploads_images/<?= $journey['image'] ?>" class="bd-placeholder-img rounded-circle" width="100" height="100" alt="<?= $journey['pseudo'] ?>">
            <div class="card-body-dark">
                <h4 class="card-text-light"><?= $journey['pseudo'] ?></h4>
                <p class="card-text-light">
                    départ: <?= $journey['departure_time'] ?> - arrivée: <?= $journey['arrival_time'] ?> <br>
                    voyage éco: <img src="/assets/img/<?= $journey['electric_car'] ?>"><br>
                    place dispo: <?= $journey['number_places'] ?> <br>
                    tarif: <?= $journey['price'] ?> <br></p>

                <a href="trajet.php" class="btn btn-primary stretched-link ">Participer au trajet</a>
            </div>
        </div>
    <?php else: ?>
        <h1>Le covoiturage est introuvable</h1>
    <?php endif; ?>
</div>

<?php
require_once "templates/footer.php";
?>
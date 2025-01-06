<?php

require_once "templates/header.php";
require_once "lib/journey.php";

if (isset($_GET["id"])) {
    $id = (int)$_GET["id"];
    $journey = getJourneyById($id);
}


$journey = getJourneyById(0);

?>
<div class="hero-scene">
    <img src="assets/img/BanTrajet.jpg" alt="" width="100%">
</div>

<div class="col md-4 my-2 d-flex">
    <div class="card w-100">
        <img src="/uploads_images/<?= $journey['image'] ?>" class="bd-placeholder-img rounded-circle" width="100" height="100" alt="<?= $journey['pseudo'] ?>">
        <div class="card-body-dark">
            <h4 class="card-title"><?= $journey['pseudo'] ?></h4>
            <p class="card-text-light">
                départ: <?= $journey['h_depart'] ?> - arrivée: <?= $journey['h_arrivee'] ?> <br>
                voyage éco: <img src="/assets/img/<?= $journey['voyage_eco'] ?>"><br>
                place dispo: <?= $journey['place_dispo'] ?> <br>
                tarif: <?= $journey['tarif'] ?> <br></p>

            <a href="trajet.php" class="btn btn-primary stretched-link ">Participer au trajet</a>
        </div>
    </div>
</div>

<?php

require_once "templates/footer.php";


?>
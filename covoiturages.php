<?php
require_once "templates/header.php";
require_once "lib/journey.php";

$journeys = getJourneys();
?>

<div class="hero-scene">
    <img src="assets/img/BanCovoiturage.jpg" alt="" width="100%">
</div>

<h1>Pour votre voyage du: "date" "départ" "arrivé" "x" trajets trouvés</h1>
<!-- fair l bloc filtre-->
<div class="row">
    <div class="col-md-3">
        <form action="" method="get">
            <h2 class="filtres">Filtres</h2>

            <div class="p-3 border-bottom">
                <label for="price">Prix maximum : </label>
                <div class="input-group">
                    <input type="number" min="1" name="price" id="price" class="form-control" placeholder="tarif">
                    <span class="input-group-text">Crédits</span>
                </div>
            </div>
            <div class="p-3 border-bottom">
                <label for="drive-note">Note minimum chauffeur : </label>
                <div class="input-group">
                    <input type="number" min="1" max="5" name="drive-note" id="drive-note" class="form-control" placeholder="note">
                    <span class="input-group-text"><i class="bi bi-star"></i></span>
                </div>
            </div>
            <div class="p-3 border-bottom">
                <label for="max_duration">Durée maximale du trajet : </label>
                <div class="input-group">
                    <input type="number" min="1" max="20" name="max_duration" id="max_duration" class="form-control" placeholder="durée">
                    <span class="input-group-text">Heures</span>
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary w-100">Filtrer</button>
            </div>
    </div>
    </form>
    <div class="col-md-9">
        <div class="row">
            <?php foreach ($journeys as $key => $journey) {
                require 'templates/journey_part.php';
            } ?>
        </div>
    </div>
</div>

<?php
require_once "templates/footer.php";
?>
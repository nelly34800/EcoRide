<?php
require_once "templates/header.php";
require_once "lib/journey.php";

$journeys = getJourney();
?>

<div class="hero-scene">
    <img src="assets/img/banCovoiturage.jpg" alt="" width="100%">
</div>

<h1>Pour votr voyage du: "date" "départ" "arrivé" "x" trajets trouvés</h1>
<!-- fair l bloc filtre-->
<div class="row">
    <div class="col-md-3">
        <form action="" method="get">
            <h2 class="filtres">Filtres</h2>
            <div>
                <label for="price">Prix maximum : </label>
                <input type="text" name="price" id="price" class="form-control back" placeholder="...">
            </div>
        </form>
    </div>
    <div class="col-md-9">
        <div class="row">
            <?php foreach ($journeys as $journey) {
                require 'templates/journey_part.php';
            } ?>
        </div>

    </div>

</div>

<?php
require_once "templates/footer.php";
?>
<?php
require_once "templates/header.php";
require_once "Lib/pdo.php";
require_once "lib/journey.php";
require_once "lib/view_carpooling.php";

// Récupérer les données envoyées depuis le formulaire de `index.php`
$place_departure = isset($_GET['place_departure']) ? $_GET['place_departure'] : '';
$place_arrival = isset($_GET['place_arrival']) ? $_GET['place_arrival'] : '';
$date = isset($_GET['date']) ? $_GET['date'] : '';

$filters = [];
if (isset($_GET['max_price']) && $_GET['max_price'] !== "") {
    $filters['max_price'] = (int) $_GET['max_price'];
}
if (isset($_GET['energy']) && $_GET['energy'] !== "") {
    $filters['energy'] = $_GET['energy'];
}
if (isset($_GET['max_duration']) && $_GET['max_duration'] !== "") {
    $filters['max_duration'] = (int) $_GET['max_duration'];
}


// Appel à la fonction pour obtenir les covoiturages
$journeys = searchJourneys($pdo, $place_departure, $place_arrival, $date, $filters);
?>
<div class="hero-scene">
    <img src="assets/img/BanCovoiturage.jpg" alt="" width="100%">
</div>
<div class="container p-4">
    <div class="row m-0">
        <div class="col-md-3 p-2 cont-filters">
            <form action="" method="get">
                <h2 class="filters mt-3 mb-3">Filtres</h2>

                <!-- Inclure les valeurs de départ, arrivée et date -->
                <input type="hidden" name="place_departure" value="<?= $place_departure; ?>">
                <input type="hidden" name="place_arrival" value="<?= $place_arrival; ?>">
                <input type="hidden" name="date" value="<?= $date; ?>">

                <div class="border-bottom">
                    <label for="energy">voyage écologique: </label>
                    <div class="input-group">
                        <input type="checkbox" name="energy" id="energy" value="eco">
                        <div class="px-3"><span class="small"> Seulement les voyages écologiques</span>
                        </div>
                    </div>
                </div>
                <div class="border-bottom">
                    <label for="max_price">prix maximum: </label>
                    <div class="input-group">
                        <input type="number" min="1" name="max_price" id="max_price" class="form-control" placeholder="tarif" value="<?php if (isset($_GET["max_price"])) ?>">
                        <span class="input-group-text">Crédits</span>
                    </div>
                </div>
                <div class="border-bottom">
                    <label for="max_duration">durée maximale du trajet: </label>
                    <div class="input-group">
                        <input type="number" min="1" max="24" name="max_duration" id="max_duration" class="form-control" placeholder="durée" value="<?php if (isset($_GET["max_duration"])) ?>">
                        <span class="input-group-text">Heures</span>
                    </div>
                </div>
                <div class="mt-4 mb-3">
                    <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                </div>
            </form>
        </div>
<div class="col-md-9">
     <h1>Pour votre trajet de <?= htmlspecialchars($place_departure); ?> à <?= htmlspecialchars($place_arrival); ?> :</h1>
            <div class="row">
                <?php
                // Affichage des trajets en fonction des filtres
                showJourneys($journeys);
                if (count($journeys) == 0) {
                    $journey_other_dates = searchJourneys($pdo, $place_departure, $place_arrival, $date, $filters, false);
                    showJourneysOtherDates($journey_other_dates);
                }
                ?>
            </div>
        
        </div>
    </div>
</div>
<?php
require_once "templates/footer.php";
?>
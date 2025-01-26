<?php
require_once "templates/header.php";
require_once "Lib/pdo.php";
require_once "lib/journey.php"; // Import de journey.php pour utiliser la fonction getJourneys
require_once "lib/view_carpooling.php";

// Récupérer les données envoyées depuis le formulaire de `index.php`
$place_departure = isset($_GET['place_departure']) ? $_GET['place_departure'] : '';
$place_arrival = isset($_GET['place_arrival']) ? $_GET['place_arrival'] : '';
$date = isset($_GET['date']) ? $_GET['date'] : '';

// Appel à la fonction pour obtenir les covoiturages
$journeys = getJourneys($pdo, $place_departure, $place_arrival, $date);

?>
<div class="hero-scene">
    <img src="assets/img/BanCovoiturage.jpg" alt="" width="100%">
</div>

<div class="row m-0">
    <div class="col-md-3 mt-3 p-3">
        <form action="" method="get">
            <h2 class="filtres">Filtres</h2>

            <div class="border-bottom">
                <label for="price">Prix maximum : </label>
                <div class="input-group">
                    <input type="number" min="1" name="price" id="price" class="form-control" placeholder="tarif">
                    <span class="input-group-text">Crédits</span>
                </div>
            </div>
            <div class="border-bottom">
                <label for="drive-note">Note minimum chauffeur : </label>
                <div class="input-group">
                    <input type="number" min="1" max="5" name="drive-note" id="drive-note" class="form-control" placeholder="note">
                    <span class="input-group-text"><i class="bi bi-star"></i></span>
                </div>
            </div>
            <div class="border-bottom">
                <label for="max_duration">Durée maximale du trajet : </label>
                <div class="input-group">
                    <input type="number" min="1" max="20" name="max_duration" id="max_duration" class="form-control" placeholder="durée">
                    <span class="input-group-text">Heures</span>
                </div>
            </div>
            <div class="mt-4 mb-3">
                <button type="submit" class="btn btn-primary w-100">Filtrer</button>
            </div>
    </div>
    </form>

    <div class="col-md-9">
        <div class="row">
            <?php
            showJourneys($journeys);
            // Puis, si aucun résultat exact n'est trouvé, tu recherches d'autres trajets et les affiches
            if (count($journeys) == 0) {
                $journey_other_dates = getJourneysOtherDates($pdo, $place_departure, $place_arrival, $date);
                showJourneysOtherDates($journey_other_dates); // Si tu veux les afficher aussi
            }
            ?>
        </div>
    </div>
</div>
</div>

<?php
require_once "templates/footer.php";

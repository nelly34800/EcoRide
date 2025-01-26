<?php
require_once "templates/header.php";
require_once "Lib/pdo.php";
require_once "lib/journey.php";

$errors = [];
$journeys = [];
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['getJourneys'])) {
    // Récupérer les données envoyées par le formulaire
    $verif = verifyJourneys($_GET);
    if ($verif === true) {
        $journeys = [
            "place_departure" => $_GET['place_departure'] ?? '',
            "place_arrival" => $_GET['place_arrival'] ?? '',
            "date" => $_GET['date'] ?? '',
        ];
        header("Location: covoiturages.php?place_departure=" . urlencode($journeys['place_departure']) . "&place_arrival=" . urlencode($journeys['place_arrival']) . "&date=" . urlencode($journeys['date']));
        exit();
    } else {
        $errors = $verif;
    }
}
?>

<div class="hero-scene">
    <img src="assets/img/BanAccueil.png" alt="" width="100%">
</div>

<p>Vous êtes soucieux de l’environnement et cherchez une solution économique pour voyager.</p>
<h1> Vous êtes au bon endroit!</h1>

<div class="container p-4">
    <form class="bar row g-3" action="index.php" method="GET">
        <div class="col-auto">
            <label for="place_departure">départ: </label>
            <input class="bar-content" type="text" name="place_departure" id="place_departure" value="<?= htmlspecialchars($_GET['place_departure'] ?? ''); ?>" placeholder="...">
            <?php if (isset($errors["place_departure"])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?= $errors["place_departure"] ?>
                </div>
            <?php } ?>
        </div>

        <div class="col-auto">
            <label for="place_arrival">arrivée: </label>
            <input class="bar-content" type="text" name="place_arrival" id="place_arrival" value="<?= htmlspecialchars($_GET['place_arrival'] ?? ''); ?>" placeholder="...">
            <?php if (isset($errors["place_arrival"])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?= $errors["place_arrival"] ?>
                </div>
            <?php } ?>
        </div>

        <div class="col-auto">
            <label for="date">date: </label>
            <input class="bar-content" type="date" id="date" name="date" value="<?= htmlspecialchars($_GET['date'] ?? ''); ?>">
            <?php if (isset($errors["date"])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?= $errors["date"] ?>
                </div>
            <?php } ?>
        </div>
        <div class="col-auto">
            <input type="submit" class="btn btn-dark mb-3" value="rechercher" name="getJourneys">
        </div>
    </form>
</div>

<section>
    <article>
        <div class="container p-4">
            <h2 class="text-center">Qui somme nous?</h2>
            <div class="row row-cols-2 align-items-center">
                <div class="col">
                    <p class="text-center">
                        Nouvelle plateforme de covoiturage: notre objectif premier est de réduire l’impacte environnemental des déplacements en encourageant le covoiturage écologique.
                    </p>
                </div>
                <div class="col">
                    <img class="w-100 rounded" src="assets/img/voitRecharge.jpg" />
                </div>
            </div>
        </div>
    </article>
    <article>
        <div class="inv">
            <div class="container p-4">
                <h2 class="text-center-inv">Pourquoi covoiturer?</h2>
                <div class="row row-cols-2 align-items-center">
                    <div class="col">
                        <img class="w-100 rounded" src="assets/img/why.jpg" />
                    </div>
                    <div class="col">
                        <p class="text-center-inv">
                            Covoiturez en plus d’être plus respectueux de l’environnement, vous fait aussi économiser sur vos trajets. Cela pourrait également vous permettre de vous faire de nouveaux amis.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </article>
</section>

<h4>Réservez en toute confiance:</h4>
<p>Nous vérifions les avis et les profils de nos chauffeurs pour que vous sachiez avec qui vous allez voyager.</p>

<?php
require_once "templates/footer.php";
?>
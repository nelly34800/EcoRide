<?php

require_once "templates/header.php";
require_once "lib/journey.php";
require_once "lib/pdo.php";

$errors = [];
$journey = [
    'place_departure' => '',
    'place_arrival' => '',
    'date' => '',
    'departure_time' => '',
    'arrival_time' => '',
    'price' => '',
];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $verif = verifyCreatJourney($_POST);
    if ($verif === true) {
        $res = registerJourney($pdo, $_POST["place_departure"], $_POST["place_arrival"], $_POST["date"], $_POST["departure_time"], $_POST["arrival_time"], $_POST["price"]);
        header("Location: profil.php");
    } else {
        $errors = $verif;
    }
}
$journey = [
    'place_departure' => $_POST['place_departure'] ?? '',
    'place_arrival' => $_POST['place_arrival'] ?? '',
    'date' => $_POST['date'] ?? '',
    'departure_time' => $_POST['departure_time'] ?? '',
    'arrival_time' => $_POST['arrival_time'] ?? '',
    'price' => $_POST['price'] ?? '',
];

?>

<div class="hero-scene">
    <img src="assets/img/BanAuto.jpg" alt="" width="100%">
</div>

<div class="form-signin w-100 m-auto">
    <h1>Ajouter un covoiturage</h1>

    <form action="" method="POST">

        <div class="form-floating">
            <label class="form-label" for="place_departure">lieu de départ: </label>
            <input type="texte" name="place_departure" class="form-control" id="place_departure" value="<?= htmlspecialchars($journey['place_departure']); ?>">
            <?php if (isset($errors["place_departure"])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?= $errors["place_departure"] ?>
                </div>
            <?php } ?>
        </div>
        <div class="form-floating">
            <label class="form-label" for="place_arrival">lieu d'arrivée: </label>
            <input type="texte" name="place_arrival" class="form-control" id="place_arrival" value="<?= htmlspecialchars($journey['place_arrival']); ?>">
            <?php if (isset($errors["place_arrival"])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?= $errors["place_arrival"] ?>
                </div>
            <?php } ?>
        </div>
        <div class="form-floating">
            <label class="form-label" for="date">date: </label>
            <input type="date" name="date" class="form-control" id="date" value="<?= htmlspecialchars($journey['date']); ?>">
            <?php if (isset($errors["date"])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?= $errors["date"] ?>
                </div>
            <?php } ?>
        </div>
        <div class="mb-1">
            <label class="form-label" for="departure_time">heure de départ: </label>
            <input type="time" name="departure_time" class="form-control" id="departure_time" value="<?= htmlspecialchars($journey['departure_time']); ?>">
            <?php if (isset($errors["departure_time"])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?= $errors["departure_time"] ?>
                </div>
            <?php } ?>
        </div>
        <div class="mb-1">
            <label class="form-label" for="arrival_time">heure d'arrivée prévue: </label>
            <input type="time" name="arrival_time" class="form-control" id="arrival_time" value="<?= htmlspecialchars($journey['arrival_time']); ?>">
            <?php if (isset($errors["arrival_time"])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?= $errors["arrival_time"] ?>
                </div>
            <?php } ?>
        </div>
        <div class="mb-1">
            <label class="form-label" for="price">tarif: </label>
            <div class="input-group">
                <input type="number" min="1" name="price" id="price" class="form-control" value="<?= htmlspecialchars($journey['price']); ?>">
                <?php if (isset($errors["price"])) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $errors["price"] ?>
                    </div>
                <?php } ?>
                <span class="input-group-text">Crédits par passager: </span>

            </div>
        </div>
        <p class="small">(frais de gestion de la plateforme inclus: 2€ <br>
            par covoiturage).</p>
        <div class="mb-2">
            <input type="submit" class="btn btn-primary" name="registerJourney" value="créer trajet">
            <a class="btn btn-primary" href="ajout_voiture.php">Ajouter une voiture</a>
        </div>
    </form>
</div>


<?php

require_once "templates/footer.php";

?>
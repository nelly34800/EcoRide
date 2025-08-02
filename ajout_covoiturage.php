<?php
require_once "templates/header.php";
require_once "lib/journey.php";
require_once "lib/car.php";
require_once "lib/pdo.php";

if (!isset($_SESSION['user']['id'])) {
    die("Erreur : Utilisateur non connecté.");
}
$user_id = $_SESSION['user']['id'];
$cars = getUserCars($pdo, $user_id);

$errors = [];
$car_id = null; 
$journey = [
    'place_departure' => '',
    'place_arrival' => '',
    'date' => '',
    'departure_time' => '',
    'arrival_time' => '',
    'total_seats' => '',
    'price' => '',
    'car_id' => $car_id,
];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $car_id = isset($_POST['car_id']) ? (int) $_POST['car_id'] : null;

    if (!$car_id) {
        $errors['car_id'] = "Erreur : Vous devez choisir une voiture.";
    }
    if (!isset($_POST['total_seats']) || !is_numeric($_POST['total_seats']) || $_POST['total_seats'] < 1 || $_POST['total_seats'] > 8) {
        $errors['total_seats'] = "Le nombre de places doit être compris entre 1 et 8.";
    }

    if (empty($errors)) { // s'execute seulement s'il n'y a aucune erreur
        // Nettoyage et formatage des villes
        $_POST["place_departure"] = ucwords(strtolower(trim($_POST["place_departure"])));
        $_POST["place_arrival"] = ucwords(strtolower(trim($_POST["place_arrival"])));
        $verif = verifyCreatJourney($_POST);
        if ($verif === true) {
            registerJourney($pdo, $_POST["place_departure"], $_POST["place_arrival"], $_POST["date"], $_POST["departure_time"], $_POST["arrival_time"], $_POST["total_seats"], $_POST["price"], $user_id, $car_id);
            header("Location: confirmation.php?type=creation&status=success");
            exit();
        } else {
            $errors = $verif;
        }
    }
}

$journey = [
    'place_departure' => $_POST['place_departure'] ?? '',
    'place_arrival' => $_POST['place_arrival'] ?? '',
    'date' => $_POST['date'] ?? '',
    'departure_time' => $_POST['departure_time'] ?? '',
    'arrival_time' => $_POST['arrival_time'] ?? '',
    'total_seats' => $_POST['total_seats'] ?? '',
    'price' => $_POST['price'] ?? '',
    'car_id' => $car_id,  // Prend la valeur validée de $car_id
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
            <input type="text" name="place_departure" class="form-control" id="place_departure" value="<?= htmlspecialchars($journey['place_departure']); ?>">
            <?php if (isset($errors["place_departure"])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?= $errors["place_departure"] ?>
                </div>
            <?php } ?>
        </div>
        <div class="form-floating">
            <label class="form-label" for="place_arrival">lieu d'arrivée: </label>
            <input type="text" name="place_arrival" class="form-control" id="place_arrival" value="<?= htmlspecialchars($journey['place_arrival']); ?>">
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
            <label  class="form-label" for="total_seats">Nombre de places disponibles: </label>
            <input type="number" min="1" max="8" name="total_seats" class="form-control" id="total_seats" value="<?= htmlspecialchars($journey['total_seats']); ?>">
            <?php if (isset($errors["total_seats"])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?= $errors["total_seats"] ?>
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
        <fieldset class="mb-2">
            <legend>Sélectionner votre voiture:</legend>

            <?php if (isset($errors["car_id"])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?= $errors["car_id"] ?>
                </div>
            <?php } ?>

            <?php if (!empty($cars)) : ?>
                <?php foreach ($cars as $car) : ?>
                    <input type="radio" id="car_<?= $car['id']; ?>" name="car_id" value="<?= $car['id']; ?>"
                        <?= isset($car_id) && $journey['car_id'] == $car['id'] ? 'checked' : '' ?> required>
                    <label for="car_<?= $car['id']; ?>"><?= htmlspecialchars($car['brand'] . " " . $car['model']); ?></label><br>
                <?php endforeach; ?>
            <?php else : ?>
                <p class="text-danger">Vous n'avez aucune voiture enregistrée.</p>
            <?php endif; ?>
        </fieldset>

        <div class="mb-2">
            <input type="submit" class="btn btn-primary" name="registerJourney" value="créer trajet">
            <a class="btn btn-primary" href="ajout_voiture.php">Ajouter une voiture</a>
        </div>
    </form>
</div>

<?php
require_once "templates/footer.php";
?>
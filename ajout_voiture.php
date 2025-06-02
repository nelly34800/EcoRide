<?php
require_once "templates/header.php";
require_once "lib/car.php";
require_once "lib/pdo.php";

if (!isset($_SESSION['user']['id'])) {
    die("Erreur : Utilisateur non connecté.");
}

$user_id = $_SESSION['user']['id'];

$errors = [];
$car = [
    'brand' => '',
    'model' => '',
    'color' => '',
    'energy' => '',
    'registration' => '',
    'date_first_registration' => '',
];

$car_id = $_GET['id'] ?? null; // Vérifie si un ID est passé en paramètre

if ($car_id) {
    // Si un ID est présent, on récupère les infos de la voiture
    $car = getCarById($pdo, $car_id);
    if (!$car) {
        die("Voiture introuvable !");
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $verif = verifyCar($_POST);
    if ($verif === true) {
        if ($car_id) {
            // Si il y'en a une on modifie la voiture
            updateCar($pdo, $car_id, $_POST["brand"], $_POST["model"], $_POST["color"], $_POST["energy"], $_POST["registration"], $_POST["date_first_registration"]);
        } else { //si on ajoute une voiture
            registerCar($pdo, $_POST["brand"], $_POST["model"], $_POST["color"], $_POST["energy"], $_POST["registration"], $_POST["date_first_registration"], $user_id);
        }
        header("Location: voitures.php");
        exit();
    } else {
        $errors = $verif;
    }
}
?>

<div class="form-signin w-100 m-auto">
    <h1>Ajouter ou modifier une voiture</h1>

    <form action="" method="POST">

        <div class="form-floating">
            <label class="form-label" for="brand">marque: </label>
            <input type="texte" name="brand" class="form-control" id="brand" value="<?= htmlspecialchars($car['brand']); ?>">
            <?php if (isset($errors["brand"])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?= $errors["brand"] ?>
                </div>
            <?php } ?>
        </div>
        <div class="form-floating">
            <label class="form-label" for="model">modèle: </label>
            <input type="texte" name="model" class="form-control" id="model" value="<?= htmlspecialchars($car['model']); ?>">
            <?php if (isset($errors["model"])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?= $errors["model"] ?>
                </div>
            <?php } ?>
        </div>
        <div class="form-floating">
            <label class="form-label" for="color">couleur: </label>
            <input type="texte" name="color" class="form-control" id="color" value="<?= htmlspecialchars($car['color']); ?>">
            <?php if (isset($errors["color"])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?= $errors["color"] ?>
                </div>
            <?php } ?>
        </div>
       
        <fieldset class="mb-2">
            <legend>Énérgie: </legend>
            <input type="radio" id="electric" name="energy" value="éléctrique" />
            <label for="electric">éléctrique</label>
            <input type="radio" id="hybrid" name="energy" value="hybride" />
            <label for="hybrid">hybride</label><br>
            <input type="radio" id="gas" name="energy" value="essence" />
            <label for="gas">essence</label>
            <input type="radio" id="diesel" name="energy" value="diesel" />
            <label for="diesel">diesel</label>
            <?php if (isset($errors["energy"])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?= $errors["energy"] ?>
                </div>
            <?php } ?>
        </fieldset>
        <div class="mb-2">
            <label class="form-label" for="registration">Numéro de plaque d'immatriculation: </label>
            <input type="texte" name="registration" class="form-control" id="registration" value="<?= htmlspecialchars($car['registration']); ?>">
            <?php if (isset($errors["registration"])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?= $errors["registration"] ?>
                </div>
            <?php } ?>
        </div>
        <div class="mb-2">
            <label class="form-label" for="date_first_registration">date de première immatriculation: </label>
            <input type="date" name="date_first_registration" class="form-control" id="date_first_registration" value="<?= htmlspecialchars($car['date_first_registration']); ?>">
            <?php if (isset($errors["date_first_registration"])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?= $errors["date_first_registration"] ?>
                </div>
            <?php } ?>
        </div>
        <input type="submit" class="btn btn-primary" name="registerCar" value="<?= $car_id ? 'Modifier la voiture' : 'Ajouter une voiture'; ?>">
    </form>
</div>

<?php

require_once "templates/footer.php";

?>
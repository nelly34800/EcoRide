<?php
require_once "templates/header.php";
require_once "lib/car.php";
require_once "lib/pdo.php";

$errors = [];
$car = [
    'brand' => '',
    'model' => '',
    'color' => '',
    'registration' => '',
    'date_first_registration' => '',
];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $verif = verifyCar($_POST);
    if ($verif === true) {
        $res = registerCar($pdo, $_POST["brand"], $_POST["model"], $_POST["color"], $_POST["number_places"], $_POST["energy"], $_POST["registration"], $_POST["date_first_registration"]);
        header("Location: chauffeur.php");
    } else {
        $errors = $verif;
    }
}

$car = [
    'brand' => $_POST['brand'] ?? '',
    'model' => $_POST['model'] ?? '',
    'color' => $_POST['color'] ?? '',
    'registration' => $_POST['registration'] ?? '',
    'date_first_registration' => $_POST['date_first_registration'] ?? '',
];

?>

<div class="form-signin w-100 m-auto">
    <h1>Ajouter une voiture</h1>

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
        <div class="mb-2">
            <label for="number_places">Nombre de places disponibles: </label>
            <input type="number" min="1" max="8" name="number_places" class="form-control" id="number_places">
            <?php if (isset($errors["number_places"])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?= $errors["number_places"] ?>
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
        <input type="submit" class="btn btn-primary" name="registerCar" value="ajouter une voiture">

    </form>
</div>

<?php

require_once "templates/footer.php";

?>
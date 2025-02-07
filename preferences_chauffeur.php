<?php
require_once "templates/header.php";
require_once "lib/preference.php";
require_once "lib/pdo.php";

$errors = [];
$preferences = [
    'pets' => '',
    'smoking' => '',
    'others' => '',
];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $verif = verifyPreferences($_POST);
    if ($verif === true) {
        $res = registerPreferences($pdo, $_POST["pets"], $_POST["smoking"], $_POST["others"]);
        header("Location: chauffeur.php");
    } else {
        $errors = $verif;
    }
}

$preferences = [
    'pets' => $_POST['pets'] ?? '',
    'smoking' => $_POST['smoking'] ?? '',
    'others' => $_POST['others'] ?? '',
];
?>

<div class="form-signin w-100 m-auto">
    <h1>Entrer vos préférences:</h1>

    <form action="" method="POST">

        <fieldset class="mb-2">
            <legend>J'accepte les animaux: </legend>
            <input type="radio" id="pets_yes" name="pets" value="1" />
            <label for="pets_yes">oui</label>
            <input type="radio" id="pets_no" name="pets" value="0" />
            <label for="pets_no">non</label><br>
            <?php if (isset($errors["pets"])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?= $errors["pets"] ?>
                </div>
            <?php } ?>
        </fieldset>
        <fieldset class="mb-2">
            <legend>J'accepte de faire des pauses pour les fumeurs: </legend>
            <input type="radio" id="smoking_yes" name="smoking" value="1" />
            <label for="smoking_yes">oui</label>
            <input type="radio" id="smoking_no" name="smoking" value="0" />
            <label for="smoking_no">non</label><br>
            <?php if (isset($errors["smoking"])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?= $errors["smoking"] ?>
                </div>
            <?php } ?>
        </fieldset>

        <div class="mb-2">
            <label class="form-label" for="others">Autres préférences: </label>
            <textarea name="others" id="others" cols="30" rows="5" class="form-control"><?= htmlspecialchars($preferences['others']); ?></textarea>
            <?php if (isset($errors["others"])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?= $errors["others"] ?>
                </div>
            <?php } ?>
        </div>
        <input type="submit" class="btn btn-primary" name="RegisterPreferences" value="enregistrer les préférences">
    </form>
</div>

<?php

require_once "templates/footer.php";

?>
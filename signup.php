<?php

require_once "templates/header.php";
require_once "lib/pdo.php";
require_once "lib/user.php";

$errors = [];
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $verif = verifyUser($_POST);
    if ($verif === true) {
        $resAdd = addUser($pdo,  $_POST["pseudo"], $_POST["email"], $_POST["password"], $_POST["last_name"], $_POST["first_name"], $_POST["address"], $_POST["role"], null);
        header("Location: signgin.php");
    } else {
        $errors = $verif;
    }
}

?>

<div class="form-signin w-100 m-auto">
    <h1>Inscription</h1>

    <form action="" method="POST">
        <div class="mb-2">
            <label class="form-label" for="pseudo">Pseudo: </label>
            <input class="form-control" type="text" name="pseudo" id="pseudo">
            <?php if (isset($errors["pseudo"])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?= $errors["pseudo"] ?>
                </div>
            <?php } ?>
        </div>

        <div class="mb2">
            <label class="form-label" for="email">Email: </label>
            <input type="email" name="email" class="form-control" id="email">
            <?php if (isset($errors["email"])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?= $errors["email"] ?>
                </div>
            <?php } ?>
        </div>

        <div class="mb-2">
            <label class="form-label" for="password">Mot de passe : </label>
            <p class="small">le mot de passe doit contenir 8 caractères avec majuscule, minuscule, chiffre et caractère spécial </p>
            <input type="password" name="password" class="form-control" id="password">
            <?php if (isset($errors["password"])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?= $errors["password"] ?>
                </div>
            <?php } ?>
        </div>
        <div class="form-floating">
            <label class="form-label" for="last_name">Nom: </label>
            <input class="form-control" type="text" name="last_name" id="last_name">
            <?php if (isset($errors["last_name"])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?= $errors["last_name"] ?>
                </div>
            <?php } ?>
        </div>
        <div class="form-floating">
            <label class="form-label" for="first_name">Prénom: </label>
            <input class="form-control" type="text" name="first_name" id="first_name">
            <?php if (isset($errors["first_name"])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?= $errors["first_name"] ?>
                </div>
            <?php } ?>
        </div>
        <div class="form-floating">
            <label class="form-label" for="address">Adresse: </label>
            <input class="form-control" type="text" name="address" id="address">
            <?php if (isset($errors["address"])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?= $errors["address"] ?>
                </div>
            <?php } ?>
        </div>
        <div class="form-floating">
            <label for="role" class="form-label">Rôle: </label>
            <select name="role" id="role" class="form-select">
                <option value="3">passager</option>
                <option value="2">chauffeur</option>
                <option value="6">passager et chauffeur</option>
            </select>
        </div>
        <div class="mb-2">
            <label for="file" class="form-label">Image: </label>
            <input type="file" name="file" id="file">
        </div>
        <input type="submit" class="btn btn-primary w-100 py-2 " value="S'inscrire" name="add_user">
    </form>
</div>


<?php

require_once "templates/footer.php";

?>
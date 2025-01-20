<?php
require_once "lib/pdo.php";
require_once "lib/user.php";
require_once "templates/header.php";


$error = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = verifyUserLoginPassword($pdo, $_POST["email"], $_POST["password"]);
    if ($user) {
        session_regenerate_id(true);
        $_SESSION["user"] = [
            "id" => $user["id"],
            "pseudo" => $user["pseudo"]
        ];
        header("Location: index.php");
    } else {
        $error = "Email ou mot de passe incorrect";
    }
}
?>

<div class="hero-scene">
    <img src="assets/img/BanTrajet.jpg" alt="" width="100%">
</div>

<div class="form-signin w-100 m-auto">
    <h1>Connexion</h1>

    <form method="POST">

        <div class="form-floating">
            <label for="email">Email: </label>
            <input type="email" name="email" class="form-control" id="email">
        </div>

        <div class="form-floating">
            <label for="password">Mot de passe : </label>
            <input type="password" name="password" class="form-control" id="password">
        </div>
        <?php if ($error): ?>
            <div class="alert alert-danger" role="alert">
                <?= $error ?>
            </div>
        <?php endif; ?>
        <input class="btn btn-primary w-100 py-2" type="submit" value="Se connecter">
    </form>
</div>


<?php

require_once "templates/footer.php";

?>
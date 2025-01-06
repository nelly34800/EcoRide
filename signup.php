<?php

require_once "templates/header.php";

?>

<div class="hero-scene">
    <img src="assets/img/BanAuto.jpg" alt="" width="100%">
</div>

<div class="form-signin w-100 m-auto">
    <h1>Inscription</h1>

    <form action="" method="post">
        <div class="mb-3">
            <label class="form-label" for="username">Pseudo: </label>
            <input class="form-control" type="text" name="username" id="username">
        </div>

        <div class="mb-3">
            <label class="form-label" for="email">Email: </label>
            <input type="email" name="email" class="form-control" id="email">
        </div>

        <div class="mb-3">
            <label class="form-label" for="password">Mot de passe : </label>
            <input type="password" name="password" class="form-control" id="password">
        </div>

        <div class="mb-3">
            <label class="form-label" for="password">Confirmer mot de passe : </label>
            <input type="password" name="password" class="form-control" id="password">
        </div>

        <input type="submit" class="btn btn-primary w-100 py-2" type="submit" value="S'inscrire">
    </form>
</div>


<?php

require_once "templates/footer.php";

?>
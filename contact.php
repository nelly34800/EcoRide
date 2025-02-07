<?php
require_once "templates/header.php";
?>

<div class="hero-scene">
    <img src="assets/img/BanAuto.jpg" alt="" width="100%">
</div>

<div class="form-signin w-100 m-auto">
    <h1>Contactez-nous!</h1>

    <form action="" method="post">

        <a class="btn btn-primary" href="problème_covoiturage.php">Signaler un problème pendant un covoiturage</a>

        <div class="mb-3">
            <label class="form-label" for="title">titre du message : </label>
            <input type="text" name="title" class="form-control" id="title">
        </div>

        <div class="mb-3">
            <label class="form-label" for="description">Description : </label>
            <textarea name="description" id="description" cols="30" rows="5" class="form-control"></textarea>
        </div>
        <input type="submit" class="btn btn-primary" value="envoyer">
    </form>
</div>

<?php
require_once "templates/footer.php";
?>
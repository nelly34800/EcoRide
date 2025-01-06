<?php

require_once "templates/header.php";

?>

<div class="hero-scene">
    <img src="assets/img/BanAuto.jpg" alt="" width="100%">
</div>

<div class="form-signin w-100 m-auto">
    <h1>Ajouter un covoiturage</h1>

    <form action="" method="post">

        <div class="mb-3">
            <label class="form-label" for="place_depature">lieu de départ</label>
            <input type="texte" name="place_depature" class="form-control" id="place_depature">
        </div>
        <div class="mb-3">
            <label class="form-label" for="place_arrival">lieu d'arrivée</label>
            <input type="texte" name="place_arrival" class="form-control" id="place_arrival">
        </div>
        <div class="mb-3">
            <label class="form-label" for="depature_time">date et heure de départ </label>
            <input type="datetime-local" name="depature_time" class="form-control" id="depature_time">
        </div>
        <div class="mb-3">
            <label class="form-label" for="arrival_time">date et heure d'arrivée prévue </label>
            <input type="datetime-local" name="arrival_time" class="form-control" id="arrival_time">
        </div>
        <div class="mb-3">
            <label for="price">tarif </label>
            <div class="input-group">
                <input type="number" min="1" name="price" id="price" class="form-control">
                <span class="input-group-text">Crédits par passager</span>

            </div>
        </div>
        <p class="small">(inclu frais de gestion de la plateforme: 2€ <br>
            par covoiturage).</p>
</div>
<div>
    <input type="submit" class="btn btn-primary" type="submit" value="créer trajet">
    <a class="btn btn-primary" href="ajoutVoiture.php">Ajouter une voiture</a>
</div>
</form>
</div>


<?php

require_once "templates/footer.php";

?>
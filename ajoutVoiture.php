<?php

require_once "templates/header.php";

?>


<div class="form-signin w-100 m-auto">
    <h1>Ajouter une voiture</h1>

    <form action="" method="post">

        <div class="mb-2">
            <label class="form-label" for="brand">marque</label>
            <input type="texte" name="brand" class="form-control" id="brand">
        </div>
        <div class="mb-2">
            <label class="form-label" for="model">modèle</label>
            <input type="texte" name="model" class="form-control" id="model">
        </div>
        <div class="mb-2">
            <label class="form-label" for="color">couleur</label>
            <input type="texte" name="model" class="form-control" id="color">
        </div>
        <div class="mb-2">
            <label for="available_seats">Nombre de places disponibles</label>
            <div class="input-group">
                <input type="number" min="1" max="8" name="available_seats" id="available_seats" class="form-control">
                <span class="input-group-text">place(s)</span>
            </div>
            <fieldset class="mb-2">
                <legend>véhicule éléctrique: </legend>
                <input type="radio" id="electric-yes" name="electric" value="true" checked />
                <label for="electric-yes">oui</label>
                <input type="radio" id="electric-no" name="electric" value="false" />
                <label for="electric-no">non</label>
            </fieldset>

            <div class="mb-2">
                <label class="form-label" for="registration">Numéro de plaque d'immatriculation</label>
                <input type="texte" name="registration" class="form-control" id="registration">
            </div>
            <div class="mb-2">
                <label class="form-label" for="first-registration">date de première immatriculation </label>
                <input type="date" name="first-registration" class="form-control" id="first-registration">
            </div>
        </div>
</div>
</div>
<input type="submit" class="btn btn-primary" type="submit" value="ajouter une voiture">

</form>
</div>


<?php

require_once "templates/footer.php";

?>
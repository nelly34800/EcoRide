<?php
require_once "lib/utils.php";


?>

<div class="col-md-6 my-4 d-flex">
    <div class="card w-100">
        <img src="<?= htmlspecialchars(getAvatar($journey['image'])); ?>" class="bd-placeholder-img rounded-circle" width="100" height="100" alt="photo du chauffeur">
        <div class="card-body d-flex flex-column">
            <h4 class="card-title"><?= htmlspecialchars($journey['pseudo']); ?></h4>
            <p class="card-text">
                date: <?= htmlspecialchars(changeDateFormat($journey['date'])); ?> <br>
                départ: <?= htmlspecialchars(changeHourFormat($journey['departure_time'])); ?> - arrivée: <?= htmlspecialchars(changeHourFormat($journey['arrival_time'])); ?><br>
                voyage éco: <?php convertEnergy($journey['energy']); ?> <br>
                place dispo: <?= htmlspecialchars($journey['number_places']); ?> place(s)<br>
                tarif: <?= htmlspecialchars($journey['price']); ?> crédits<br>
            </p>
            <!--on passe la clé en paramètre pour qu'elle passe par l'id-->
            <div class=" mt-auto">
                <a href="trajet.php?id=<?= $journey['id']; ?>" class=" btn btn-primary stretched-link w-100">Détails</a>
            </div>
        </div>
    </div>
</div>
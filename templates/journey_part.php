<?php
require_once "lib/utils.php";
?>

<div class="col-md-6 my-4 d-flex">
    <div class="card w-100">
        <img src="<?= htmlspecialchars(getAvatar($journey['image'])); ?>" class="bd-placeholder-img rounded-circle" width="100" height="100" alt="photo du chauffeur">
        <div class="card-body d-flex flex-column">
            <h4 class="card-title"><?= htmlspecialchars($journey['pseudo']); ?></h4><br>
            <div id="stars">
                <?php if (!empty($journey['averageRating'])): ?>
                    <?= renderStars($journey['averageRating']); ?>
                <?php else: ?>
                    <p>Aucune évaluation pour le moment.</p>
                <?php endif; ?>
            </div>
            <p class="card-text">
                date: <?= htmlspecialchars(changeDateFormat($journey['date'])); ?> <br>
                départ: <strong><?= htmlspecialchars(changeHourFormat($journey['departure_time'])); ?></strong> 
                - arrivée: <strong><?= htmlspecialchars(changeHourFormat($journey['arrival_time'])); ?></strong> <br>
                voyage éco: <?php convertEnergy($journey['energy']); ?> <br>
                place dispo: <strong><?= htmlspecialchars($journey['total_seats']); ?></strong>  place(s)<br>
                tarif: <strong><?= htmlspecialchars($journey['price']); ?>crédits</strong> <br>
            </p>
            <!--on passe la clé en paramètre pour qu'elle passe par l'id-->
            <div class=" mt-auto">
                <a href="trajet.php?id=<?= $journey['id']; ?>" class=" btn btn-primary stretched-link w-100">Détails</a>
            </div>
        </div>
    </div>
</div>
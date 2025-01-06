<div class="col md-4 my-2 d-flex">
    <div class="card w-100">
        <img src="/uploads_images/<?= $journey['image'] ?>" class="bd-placeholder-img rounded-circle" width="100" height="100" alt="...">
        <div class="card-body">
            <h4 class="card-title"><?= $journey['pseudo'] ?></h4>
            <p class="card-text">départ: <?= $journey['h_depart'] ?> - arrivée: <?= $journey['h_arrivee'] ?> <br>
                voyage éco: <img src="/assets/img/<?= $journey['voyage_eco'] ?>"><br>
                place dispo: <?= $journey['place_dispo'] ?> <br>
                tarif: <?= $journey['tarif'] ?> <br></p>
            <!--on passe la clé en paramètre pour qu'elle passe par l'id-->
            <a href="trajet.php?id=<?= $key ?> class=" btn btn-primary stretched-link w-100">Détails</a>
        </div>
    </div>
</div>
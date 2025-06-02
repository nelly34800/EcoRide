<?php
require_once "templates/header.php";
require_once "Lib/pdo.php";
require_once "Lib/utils.php";
require_once "lib/car.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user']['id'])) {
    header("Location: signin.php"); // Redirige vers la page de connexion si non connecté
    exit();
}

$user_id = $_SESSION['user']['id']; // Récupère l'ID de l'utilisateur connecté
$cars = getCars($pdo, $user_id); // Récupérer l'ID de la voiture

if (isset($_GET['success'])): ?>
    <div class="alert alert-success">La voiture a été supprimée avec succès !</div>
<?php elseif (isset($_GET['error'])): ?>
    <div class="alert alert-danger">Erreur lors de la suppression de la voiture.</div>
<?php endif; ?>

<div class="container">
    <h1>Mes voitures</h1>
    <a class="btn btn-primary m-2" href="ajout_voiture.php">Ajouter une voiture</a>

    <?php if (count($cars) > 0): ?>
        <div class="row">
            <?php foreach ($cars as $car) { ?>
                <div class="col-md-4 my-2 d-flex">
                    <div class="card w-100">
                        <div class="card-body d-flex flex-column">
                            <p class="card-text">marque: <?= htmlspecialchars($car['brand']); ?></p>
                            <p class="card-text">modèle: <?= htmlspecialchars($car['model']); ?></p>
                            <p class="card-text">couleur: <?= htmlspecialchars($car['color']); ?></p>
                            <p class="card-text">energie: <?= htmlspecialchars($car['energy']); ?></p>
                            <p class="card-text">immatriculation: <?= htmlspecialchars($car['registration']); ?></p>
                            <p class="card-text">date de 1ère immatriculation: <br> <?= htmlspecialchars(changeDateFormat($car['date_first_registration'])); ?></p>
                            <div class="m-2">
                                <a href="ajout_voiture.php?id=<?= $car['id']; ?>" class="btn btn-primary m-2">Modifier la voiture</a>
                                <a href="sup_voiture.php?id=<?= $car['id']; ?>" class="btn btn-dark m-2" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette voiture ?');">Supprimer la voiture</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php else: ?>
            <p>Aucune voiture enregistrée.</p>
        <?php endif; ?>
        </div>

        <?php
        require_once "templates/footer.php";
        ?>
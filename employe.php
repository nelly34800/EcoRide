<?php
require_once "lib/pdo.php";
require_once "lib/user.php";
require_once "lib/role.php";
require_once "lib/utils.php";
require_once "lib/report_problem.php";
require_once "lib/review.php";
require_once "templates/header.php"; 

// Vérifier que l'utilisateur est employé 
verifRole(4); 

// Actions sur les avis
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['valider'])) {
        validateReview($_POST['idAvis']);
        $_SESSION['flash'] = ["type" => "success", "message" => "Avis validé avec succès."];
    } elseif (isset($_POST['rejeter'])) {
        rejectReview($_POST['idAvis']);
        $_SESSION['flash'] = ["type" => "danger", "message" => "Avis rejeté."];
    }
    header("Location: employe.php"); // éviter le renvoi du formulaire (F5)
    exit();
}

// Récupérer les données
$avisNonValides   = getInvalidReviews($pdo);
$problemsToDo     = getProblemsByStatus($pdo, "to do");
$problemsInProg   = getProblemsByStatus($pdo, "in progress");
$problemsCompleted = getProblemsByStatus($pdo, "completed");

// Fonction pour afficher les problèmes
function renderProblemsTable(array $problems, string $title, bool $showActions = true) {
    ?>
    <h3><?= htmlspecialchars($title) ?></h3>
    <?php if (empty($problems)): ?>
        <p>Aucun problème trouvé.</p>
        <?php return; ?>
    <?php endif; ?>

    <table class="table table-bordered">
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Description</th>
                <th>Trajet</th>
                <th>Conducteur</th>
                <th>Signalé par</th>
                <?php if ($showActions): ?><th>Action</th><?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($problems as $problem): ?>
                <tr>
                    <td><?= htmlspecialchars($problem['journey_id']) ?></td>
                    <td><?= htmlspecialchars(changeDateFormat($problem['created_at'])) ?></td>
                    <td><?= htmlspecialchars($problem['descriptive']) ?></td>
                    <td>
                        <?= htmlspecialchars($problem['place_departure']) ?> → <?= htmlspecialchars($problem['place_arrival']) ?><br>
                        <?= htmlspecialchars(changeDateFormat($problem['date'])) ?>
                    </td>
                    <td><?= htmlspecialchars($problem['driver_pseudo']) ?><br><?= htmlspecialchars($problem['driver_email']) ?></td>
                    <td><?= htmlspecialchars($problem['reporter_pseudo']) ?><br><?= htmlspecialchars($problem['reporter_email']) ?></td>
                    <?php if ($showActions): ?>
                        <td>
                            <?php if ($problem['status'] === 'to do'): ?>
                                <form action="lib/update_problem_status.php" method="post">
                                    <input type="hidden" name="problem_id" value="<?= $problem['problem_id'] ?>">
                                    <input type="hidden" name="new_status" value="in progress">
                                    <button class="btn btn-primary">Mettre en cours</button>
                                </form>
                            <?php elseif ($problem['status'] === 'in progress'): ?>
                                <form action="lib/update_problem_status.php" method="post">
                                    <input type="hidden" name="problem_id" value="<?= $problem['problem_id'] ?>">
                                    <input type="hidden" name="new_status" value="completed">
                                    <button class="btn btn-primary">Marquer comme terminé</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
}
?>

<div class="container mt-4">
    <h1 class="mb-4">Espace employé</h1>

    <!-- Message flash -->
    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="alert alert-<?= $_SESSION['flash']['type'] ?> alert-dismissible fade show" role="alert">
            <?= $_SESSION['flash']['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <!-- Onglets Bootstrap -->
   <ul class="nav nav-pills mb-3" id="employeTabs" role="tablist">
     <li class="nav-item" role="presentation">
        <button class="nav-link active" id="avis-tab" data-bs-toggle="pill" data-bs-target="#avis" type="button" role="tab">
        Avis en attente
        </button>
    </li>
    <li class="nav-item dropdown" role="presentation">
        <div class="btn-group dropup">
            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
            Problèmes
            </a>
            <ul class="dropdown-menu cont-nav">
            <li><a class="dropdown-item nav-link" data-bs-toggle="pill" href="#toDo">Problèmes à traiter</a></li>
            <li><a class="dropdown-item nav-link" data-bs-toggle="pill" href="#inProgress">Problèmes en cours</a></li>
            <li><a class="dropdown-item nav-link" data-bs-toggle="pill" href="#completed">Problèmes résolus</a></li>
            </ul>
        </div>
    </li>
</ul>

    <div class="tab-content mt-3">
        <!-- Onglet Avis -->
        <div class="tab-pane fade show active" id="avis" role="tabpanel">
            <?php if (empty($avisNonValides)): ?>
                <p>Aucun avis en attente de validation.</p>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($avisNonValides as $avis): ?>
                        <div class="col-md-4 mb-4">
                            <div class="form-signin w-100 m-auto">
                                <div class="card-body-dark p-4">
                                    <h3>chauffeur: <?= htmlspecialchars($avis['driver_pseudo']) ?></h3>
                                    <p class="card-text-light">Note: <?= htmlspecialchars($avis['rating']) ?></p>
                                    <p class="card-text-light">Commentaire: <br> "<?= htmlspecialchars($avis['comment']) ?> " </p>
                                    <cite class="card-text-light"><?= htmlspecialchars($avis['passenger_pseudo']) ?></cite>
                                    <form method="POST">
                                        <input type="hidden" name="idAvis" value="<?= $avis['id_review'] ?>">
                                        <button type="submit" class="btn btn-primary" name="valider">Valider</button>
                                        <button type="submit" class="btn btn-outline-primary" name="rejeter">Rejeter</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <!-- Onglet Problèmes -->
       <div class="tab-pane fade" id="toDo" role="tabpanel">
            <?php renderProblemsTable($problemsToDo, ""); ?>
        </div>

        <div class="tab-pane fade" id="inProgress" role="tabpanel">
            <?php renderProblemsTable($problemsInProg, ""); ?>
        </div>

        <div class="tab-pane fade" id="completed" role="tabpanel">
            <?php renderProblemsTable($problemsCompleted, "", false); ?>
        </div>
        </div>
    </div>
</div>
<?php 
require_once "templates/footer.php"; 
?>
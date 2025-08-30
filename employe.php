<?php
require_once "lib/pdo.php";
require_once "lib/user.php";
require_once "lib/role.php";
require_once "lib/utils.php";
require_once "lib/report_problem.php";
require_once "templates/header.php"; 
      
// Vérifier que l'utilisateur est employé 
verifRole(4); 

$problemsToDo = getProblemsByStatus($pdo, "to do");
$problemsInProgress = getProblemsByStatus($pdo, "in progress");
$problemsCompleted = getProblemsByStatus($pdo, "completed");

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
            <?php if ($showActions): ?>
                <th>Action</th>
            <?php endif; ?>
        </tr>

        <?php foreach ($problems as $problem): ?>
            <tr>
                <td><?= htmlspecialchars($problem['journey_id']) ?></td>
                <td><?= htmlspecialchars(changeDateFormat($problem['created_at'])) ?></td>
                <td><?= htmlspecialchars($problem['descriptive']) ?></td>
                <td><?= htmlspecialchars($problem['place_departure']) ?> → <?= htmlspecialchars($problem['place_arrival']) ?><br>
                    <?= htmlspecialchars(changeDateFormat($problem['date'])) ?></td>
                <td><?= htmlspecialchars($problem['driver_pseudo']) ?><br><?= htmlspecialchars($problem['driver_email']) ?></td>
                <td><?= htmlspecialchars($problem['reporter_pseudo']) ?><br><?= htmlspecialchars($problem['reporter_email']) ?></td>
                <?php if ($showActions): ?>
                    <td>
                        <?php if ($problem['status'] === 'to do'): ?>
                            <form action="lib/update_problem_status.php" method="post">
                                <input type="hidden" name="problem_id" value="<?= $problem['problem_id'] ?>">
                                <input type="hidden" name="new_status" value="in progress">
                                <button class="btn btn-primary btn-sm">Mettre en cours</button>
                            </form>
                        <?php elseif ($problem['status'] === 'in progress'): ?>
                            <form action="lib/update_problem_status.php" method="post">
                                <input type="hidden" name="problem_id" value="<?= $problem['problem_id'] ?>">
                                <input type="hidden" name="new_status" value="completed">
                                <button class="btn btn-primary btn-sm">Marquer comme terminé</button>
                            </form>
                        <?php endif; ?>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php } ?>

<div class="container p-4"> 
    <?php renderProblemsTable($problemsToDo, "Problèmes à traiter"); ?>
    <?php renderProblemsTable($problemsInProgress, "Problèmes en cours"); ?> 
    <?php renderProblemsTable($problemsCompleted, "Problèmes résolus", false); ?> 
</div> 

<?php 
require_once "templates/footer.php"; 
?>
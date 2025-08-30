<?php
require_once "templates/header.php";
require_once "lib/pdo.php";
require_once "lib/report_problem.php";

if (!isset($_SESSION['user']['id'])) {
    die("Erreur : Utilisateur non connecté.");
}

$id_journey = $_GET['journey_id'] ?? null;
$id_user_reporter = $_SESSION['user']['id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $descriptive = trim($_POST['descriptive'] ?? '');
    if ($descriptive === '') {
        $error = "Le champ description est obligatoire.";
    } else {
        registerProblem($pdo, $descriptive, $id_journey, $id_user_reporter);
        header("Location: confirmation.php?type=problem&status=success");
        exit();
    }
}
?>

<div class="form-signin w-100 m-auto">
    <h1>Signalement problème</h1>
    <form method="post">
        <div class="mb-3">
            <label for="descriptive" class="form-label">Description :</label>
            <textarea name="descriptive" id="descriptive" class="form-control"></textarea>
            <?= isset($error) ? "<div class='alert alert-danger'>$error</div>" : "" ?>
        </div>
        <input type="submit" class="btn btn-primary" value="Envoyer">
    </form>
</div>

<?php require_once "templates/footer.php"; ?>

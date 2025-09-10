<?php
require_once "lib/pdo.php";
require_once "lib/administrator.php";
require_once "lib/user.php";
require_once "lib/role.php";
require_once "templates/header.php";

// Vérifier que l'utilisateur est administrateur
verifRole(5);
// Récupérer les données via les fonctions
$covoituragesParJour = getJourneyByDay($pdo);
$creditsParJour = getCreditByDay($pdo);
$totalCredits = getTotalCredits($pdo);
?>
<script>
const covoituragesLabels = <?= json_encode(array_column($covoituragesParJour, 'jour')); ?>;
const covoituragesData   = <?= json_encode(array_column($covoituragesParJour, 'nb_covoiturages')); ?>;

const creditsLabels = <?= json_encode(array_column($creditsParJour, 'jour')); ?>;
const creditsData   = <?= json_encode(array_column($creditsParJour, 'credits_gagnes')); ?>;
</script>

<h1>Bienvenue administrateur</h1>

<div class="container p-4 text-center">
  <div class="row m-0">
    <div class="col-md-6">
      <h2>Covoiturages par jour</h2>
      <canvas id="covoituragesChart"></canvas>
    </div>
    <div class="col-md-6">
      <h2>Crédits gagnés par la plateforme par jour</h2>
      <canvas id="creditsChart"></canvas>
    </div>
  </div>
</div>

<h3>Total des crédits gagnés : <?= htmlspecialchars($totalCredits); ?> crédits</h3>

<div class="form-signin w-100 m-auto">
  <a href="liste_employes.php" class="btn btn-primary">Liste des employé</a>
  <a href="liste_utilisateurs.php" class="btn btn-dark">Liste des utilisateurs</a>
</div>

<?php
require_once "templates/footer.php";
?>

<script src="js/admin_charts.js"></script>
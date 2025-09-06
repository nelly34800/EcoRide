<?php
require_once "lib/pdo.php";
require_once "lib/user.php";
require_once "lib/role.php";
require_once "templates/header.php";

// Vérifier que l'utilisateur est administrateur
verifRole(5);

?>
<h1>Bienvenue administrateur</h1>

<div class="form-signin w-100 m-auto">
  <a href="liste_employes.php" class="btn btn-primary">Liste des employé</a>
  <a href="liste_utilisateurs.php" class="btn btn-dark">Liste des utilisateurs</a>
</div>


<?php
require_once "templates/footer.php";
?>
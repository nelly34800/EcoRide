<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "lib/config.php";

$currentPage = basename($_SERVER['SCRIPT_NAME']);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/override-bootstrap.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>EcoRide</title>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary ">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">
                    <img src="assets/img/logo_e.png" alt="Logo EcoRide  cercle avec à l'intérieur une voiture à coté d'un smartphone" width="52px">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon" style="background-color: var(--primary);"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0 nav nav-pills">
                        <?php foreach ($mainMenu as $key => $value) { ?>
                            <!--affiche la classe active de l'onglet quand on est sur la page-->
                            <li class="nav-item"><a href="<?= $key; ?>" class="nav-link <?php if ($currentPage === $key) {
                                                                                            echo 'active';
                                                                                        } ?>"><?= $value; ?></a></li>
                        <?php } ?>

                        <?php if (isset($_SESSION["user"])): ?>
                            <?php
                            //détermine le chemin de la page en fonction du rôle de l'utilisateur
                            $profil = 'index.php'; //valeur par défaut
                            switch ($_SESSION['user']['role_id']) {
                                case 5: //admin
                                    $profil = 'admin.php';
                                    break;
                                case 4: //employé
                                    $profil = 'employe.php';
                                    break;
                                case 2: //chauffeur
                                    $profil = 'chauffeur.php';
                                    break;
                                case 3: //passager
                                    $profil = 'passager.php';
                                    break;
                                case 6: //passager_chauffeur
                                    $profil = 'passager_chauffeur.php';
                                    break;
                            }
                            ?>
                            <li class="nav-item"><a class="btn btn-outline-primary" href="<?= htmlspecialchars($profil) ?>">Bonjour <?= htmlspecialchars($_SESSION["user"]["pseudo"]) ?> </a></li>
                            <li class="nav-item"><a class="btn btn-primary" href="signout.php">Déconnexion</a></li>
                        <?php else: ?>
                            <li class="nav-item"><a class="btn btn-outline-primary" href="signin.php">Connexion</a></li>
                            <li class="nav-item"><a class="btn btn-primary" href="signup.php">Inscription</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main>
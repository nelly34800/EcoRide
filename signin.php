<?php
require_once "lib/pdo.php";
require_once "lib/user.php";
require_once "lib/role.php";
require_once "templates/header.php";

$error = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = verifyUserLoginPassword($pdo, $_POST["email"], $_POST["password"]);

    // 1. Email ou mot de passe incorrect
    if (!$user) {
        $error = "Email ou mot de passe incorrect";
    } 
    // 2. Compte suspendu
    elseif ($user['status'] !== 'active') {
        $error = "Votre compte est suspendu. Contactez l'administrateur.";
    } 
    // 3. Tout est OK
    else {
        session_regenerate_id(true);
        $_SESSION["user"] = [
            "id" => $user["id"],
            "pseudo" => $user["pseudo"],
            "role_id" => $user["role_id"]
        ];

        // Redirection selon rôle
        switch ($_SESSION["user"]["role_id"]) {
            case 5:
                header("Location: admin.php");
                break;
            case 4:
                header("Location: employe.php");
                break;
            case 2:
                header("Location: chauffeur.php");
                break;
            case 3:
                if (isset($_SESSION['redirect_to'])) {
                    // redirige vers la page précédente ex: page de reservation pour pas refaire la recherche du covoiturage
                    $redirect_to = $_SESSION['redirect_to'];
                    unset($_SESSION['redirect_to']);
                    header("Location: $redirect_to");
                } else {
                    header("Location: passager.php");
                }
                break;
            case 6:
                if (isset($_SESSION['redirect_to'])) {
                    // idem passager
                    $redirect_to = $_SESSION['redirect_to'];
                    unset($_SESSION['redirect_to']);
                    header("Location: $redirect_to");
                } else {
                    header("Location: passager_chauffeur.php");
                }
                break;
            default:
                header("Location: index.php");
                break;
        }
        exit;
    }
}

?>
<div class="hero-scene">
    <img src="assets/img/BanTrajet.jpg" alt="" width="100%">
</div>

<div class="form-signin w-100 m-auto">
    <div class="my-4">
        <h1>Connexion</h1>
    </div>

    <form method="POST">
        <div class="my-4">
            <label for="email">Email: </label>
            <input type="email" name="email" class="form-control" id="email">
        </div>
        <div class="my-4">
            <label for="password">Mot de passe : </label>
            <input type="password" name="password" class="form-control" id="password">
        </div>
        <?php if ($error): ?>
            <div class="alert alert-danger" role="alert">
                <?= $error ?>
            </div>
        <?php endif; ?>
        <div class="my-4">
            <input class="btn btn-primary w-100 py-2" type="submit" value="Se connecter">
        </div>
    </form>
</div>
<?php
require_once "templates/footer.php";
?>
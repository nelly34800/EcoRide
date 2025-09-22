<?php
require_once "templates/header.php";
require_once "lib/pdo.php";
require_once "lib/user.php";
require_once "lib/role.php";
require_once "lib/utils.php";

// Vérifier que l'utilisateur est administrateur
verifRole(5);

$errors = [];
$messages = [];
$user = [
    'pseudo' => '',
    'email' => '',
    'password' => '',
    'last_name' => '',
    'first_name' => '',
    'address' => '',
];

// Rôle employé (fixe)
$roleEmploye = 4;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $verif = verifyUser($_POST);
    if ($verif === true) {

        // Pas de gestion d'image
        $fileName = null;

        // Si pas d’erreur → création
        if (empty($errors)) {
            $resAdd = addUser(
                $pdo,
                $_POST["pseudo"],
                $_POST["email"],
                $_POST["password"],
                $_POST["last_name"],
                $_POST["first_name"],
                $_POST["address"],
                $roleEmploye,   // rôle fixé à 4
                $fileName       // pas d'image
            );
            if ($resAdd) {
                $messages[] = "Compte employé créé avec succès !";
                header("Location: liste_employes.php");
                exit;
            } else {
                $errors[] = "Erreur lors de la création de l'utilisateur.";
            }
        }
    } else {
        $errors = $verif;
    }
}

$user = [
    'pseudo' => $_POST['pseudo'] ?? '',
    'email' => $_POST['email'] ?? '',
    'password' => $_POST['password'] ?? '',
    'last_name' => $_POST['last_name'] ?? '',
    'first_name' => $_POST['first_name'] ?? '',
    'address' => $_POST['address'] ?? '',
];
?>

<div class="form-signin w-100 m-auto">
    <h1>Créer un employé</h1>

    <?php foreach ($messages as $message) { ?>
        <div class="alert alert-success">
            <?= $message; ?>
        </div>
    <?php } ?>

    <?php foreach ($errors as $error) { ?>
        <div class="alert alert-danger">
            <?= $error; ?>
        </div>
    <?php } ?>

    <form action="" method="POST">
        <div class="form-floating">
            <label class="form-label" for="pseudo">Pseudo: </label>
            <input class="form-control" type="text" name="pseudo" id="pseudo" value="<?= htmlspecialchars($user['pseudo']); ?>">
        </div>

        <div class="form-floating">
            <label class="form-label" for="email">Email: </label>
            <input type="email" name="email" class="form-control" id="email" value="<?= htmlspecialchars($user['email']); ?>">
        </div>

        <div class="form-floating">
            <label class="form-label" for="password">Mot de passe : </label>
            <input type="password" name="password" class="form-control" id="password" value="<?= htmlspecialchars($user['password']); ?>">
        </div>
        <p class="small">Le mot de passe doit contenir 8 caractères avec majuscule, minuscule, chiffre et caractère spécial.</p>

        <div class="form-floating">
            <label class="form-label" for="last_name">Nom: </label>
            <input class="form-control" type="text" name="last_name" id="last_name" value="<?= htmlspecialchars($user['last_name']); ?>">
        </div>

        <div class="form-floating">
            <label class="form-label" for="first_name">Prénom: </label>
            <input class="form-control" type="text" name="first_name" id="first_name" value="<?= htmlspecialchars($user['first_name']); ?>">
        </div>

        <div class="mb-2">
            <label class="form-label" for="address">Adresse: </label>
            <input class="form-control" type="text" name="address" id="address" value="<?= htmlspecialchars($user['address']); ?>">
        </div>

        <!-- Champ caché pour confirmer le rôle employé -->
        <input type="hidden" name="role" value="<?= $roleEmploye ?>">

        <input type="submit" class="btn btn-primary w-100 py-2 " value="Créer l'employé" name="add_user">
    </form>
</div>

<?php
require_once "templates/footer.php";
?>

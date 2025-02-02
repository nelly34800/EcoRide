<?php

require_once "templates/header.php";
require_once "lib/pdo.php";
require_once "lib/user.php";
require_once "lib/role.php";
require_once "lib/utils.php";
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

$roles = getRoles($pdo);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $verif = verifyUser($_POST);
    if ($verif === true) {
        // Vérification et gestion de l'image
        $fileName = null;
        //si un fichier à été envoyé
        if (isset($_FILES['file']['tmp_name']) && $_FILES['file']['tmp_name'] != '') {
            //la méthode getimagesize va retourner false si le fichier n'est pas une image
            $checkImage = getimagesize($_FILES['file']['tmp_name']);
            if ($checkImage !== false) {
                // Si l'image est valide, renommer et déplacer
                $fileName = uniqid() . '-' . basename($_FILES['file']['name']);
                move_uploaded_file($_FILES['file']['tmp_name'], _AVATAR_IMG_PATH_ . $fileName);  // Assure-toi que ce dossier existe
            } else {
                //sinon on affiche un message d'erreur
                $errors[] = 'Le fichier doit être une image';
            }
        }

        // Si il n'y a pas d'erreur, on enregistre l'utilisateur
        if (empty($errors)) {
            $resAdd = addUser($pdo, $_POST["pseudo"], $_POST["email"], $_POST["password"], $_POST["last_name"], $_POST["first_name"], $_POST["address"], $_POST["role"], $fileName);
            header("Location: signin.php");
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
    <h1>Inscription</h1>

    <?php foreach ($messages as $message) { ?>
        <div class="alert alert-success">
            <?= $message; ?>
        </div>
    <?php } ?>
    <!-- ou pas-->
    <?php foreach ($errors as $error) { ?>
        <div class="alert alert-danger">
            <?= $error; ?>
        </div>
    <?php } ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <!--multipart/form-data autorise gestion des fichiers-->
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
        <p class="small">le mot de passe doit contenir 8 caractères avec majuscule, minuscule, chiffre et caractère spécial </p>
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
        <div class="form-floating">
            <label for="role" class="form-label">Rôle: </label>
            <select name="role" id="role" class="form-select">
                <?php foreach ($roles as $role) {
                    // Exclure les rôles "admin" (id = 5) et "employé" (id = 4)
                    if ($role['id'] != 5 && $role['id'] != 4) { ?>
                        <option value="<?= $role['id']; ?>"> <?= $role['role']; ?></option>
                <?php }
                } ?>
            </select>
        </div>
        <div class="mb-2">
            <label for="file" class="form-label">Image: </label>
            <input type="file" name="file" id="file">
        </div>
        <input type="submit" class="btn btn-primary w-100 py-2 " value="S'inscrire" name="add_user">
    </form>
</div>

<?php

require_once "templates/footer.php";

?>
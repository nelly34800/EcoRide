<?php
require_once "lib/pdo.php";
require_once "lib/user.php";
require_once "lib/role.php";
require_once "templates/header.php";

// Vérifier que l'utilisateur est administrateur
verifRole(5);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace administrateur</title>
</head>

<body>
    <h1>Bienvenue administrateur</h1>
</body>

</html>
<?php
require_once "templates/footer.php";
?>
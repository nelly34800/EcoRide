<?php
require_once "templates/header.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page 404</title>
</head>

<body>
    <div class="container">
        <h1>Page Non Trouvée</h1>
        <p>Apparemment, la page que vous recherchez n'existe pas.</p>
        <div class="container p-4 text-center">
            <img src="assets/img/404.png" class="img-fluid" alt="Page non trouvée">
            <p><a href="index.php" class="btn btn-primary">Retour à l'accueil</a></p>
        </div>
    </div>
</body>

</html>
<?php
require_once "templates/footer.php";
?>
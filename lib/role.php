<?php
//fonction qui recupère les rôles de la bdd pour le formulaire d'inscription
function getRoles(PDO $pdo)
{
    $sql = 'SELECT * FROM roles';
    $query = $pdo->prepare($sql);

    $query->execute();
    return $query->fetchAll();
}

function verifRole(int $required_role_id)
{
    // si l'utilisateur essaye d'accèder à une page non autorisée il est redirigé vers la page not_allowed.php
    if (!isset($_SESSION['user']) || $_SESSION['user']['role_id'] !== $required_role_id) {
        header("Location: not_allowed.php");
        exit;
    }
}

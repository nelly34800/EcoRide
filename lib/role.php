<?php
//fonction qui recupère les rôles de la bdd pour le formulaire d'inscription
function getRoles(PDO $pdo)
{
    $sql = 'SELECT * FROM roles';
    $query = $pdo->prepare($sql);

    $query->execute();
    return $query->fetchAll();
}

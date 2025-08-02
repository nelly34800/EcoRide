<?php

define('_AVATAR_IMG_PATH_', 'uploads/images/');
define('_ASSETS_IMG_PATH_', 'assets/img/');

$mainMenu = [
    'index.php' => 'Accueil',
    'covoiturages.php' => 'Covoiturages',
    'contact.php' => 'Contact'
];

function chargerEnv($fichier) {
    if (!file_exists($fichier)) return;
// ignore les '\n' et les lignes vides 
    $lignes = file($fichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lignes as $ligne) { 
        if (str_starts_with(trim($ligne), '#')) continue; // Enlève les espace au début et à la fin et ignore les commentaires
        list($cle, $valeur) = explode('=', $ligne, 2); //récupère clé et valeur séparées au = 1 seul découpage même si plusieurs =
        $_ENV[trim($cle)] = trim($valeur);// stock les variables d'environement dans $_ENV
    }
}

// Charger les variables d'environnement
chargerEnv(__DIR__ . '/../.env');


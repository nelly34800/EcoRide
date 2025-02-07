<?php
function choice($good) // Affiche l'image en fonction si la voiture est éléctrique ou non
{
    if ($good) {
        echo '<img src="/assets/img/good.png" alt="oui">';
    } else {
        echo '<img src="/assets/img/bad.png" alt="non">';
    }
}

function convertEnergy($energy)
{
    $good = false; // Valeur par défauut

    // Si l'énergie est 'electrique' ou 'hybride', on assigne True
    if (in_array($energy, ['éléctrique', 'hybride'])) {
        $good = true;
    }

    // Appeler la fonction choice pour afficher l'image
    choice($good);
}

function getAvatar(string|null $avatar) //Affiche soit l'image fournie par l'utilisateur ou une image par défaut
{
    if ($avatar === null) {
        return _ASSETS_IMG_PATH_ . 'avatar.png';
    } else {
        return _AVATAR_IMG_PATH_ . $avatar;
    }
}

function changeDateFormat($date) // Affiche date  "jour-mois-année"
{
    return date("d-m-Y", strtotime($date));
}

function changeDateFormatJour($date)
{
    // Convertit la date en timestamp
    $timestamp = strtotime($date);

    // Crée un tableau avec les noms de mois et jours en français
    $mois = [
        'janvier',
        'février',
        'mars',
        'avril',
        'mai',
        'juin',
        'juillet',
        'août',
        'septembre',
        'octobre',
        'novembre',
        'décembre'
    ];
    $jours = [
        'dimanche',
        'lundi',
        'mardi',
        'mercredi',
        'jeudi',
        'vendredi',
        'samedi'
    ];

    // Récupère les composantes de la date
    $jourSemaine = $jours[date('w', $timestamp)]; // Jour de la semaine (0 = dimanche)
    $jourMois = date('d', $timestamp); // Jour du mois
    $moisNom = $mois[date('n', $timestamp) - 1]; // Nom du mois (1 = janvier)
    $annee = date('Y', $timestamp); // Année

    // Retourne la date formatée
    return $jourSemaine . ' ' . $jourMois . ' ' . $moisNom . ' ' . $annee;
}

function changeHourFormat($hour) // Affiche date  "10h00"
{
    return date("H\hi", strtotime($hour));
}

function journeyTime($departure_time, $arrival_time)
{
    $originalTime = new DateTimeImmutable($departure_time);
    $targetTime = new DateTimeImmutable($arrival_time);
    $interval = $originalTime->diff($targetTime);
    return $interval->format("%Hh%I");
}
function slugify($text, string $divider = '-')
{
    // replace non letter or digits by divider
    $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    // trim
    $text = trim($text, $divider);

    // remove duplicate divider
    $text = preg_replace('~-+~', $divider, $text);

    // lowercase
    $text = strtolower($text);

    if (empty($text)) {
        return 'n-a';
    }

    return $text;
}

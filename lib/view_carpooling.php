<?php
// Vérifie d'abord si la variable $journeys est bien définie
if (isset($journeys) && is_array($journeys) && count($journeys) > 0) {
    // Si la variable $journeys est définie et contient des données, appelle la fonction showJourneys
    showJourneys($journeys);
}

// Fonction pour afficher les covoiturages
function showJourneys($journeys)
{
    if (count($journeys) > 0) {
        echo "<h1>Covoiturages trouvés</h1>";

        foreach ($journeys as $journey) {
            require "templates/journey_part.php"; // Affichage du covoiturage
        }
    } else {
        echo "<h1>Aucun covoiturage trouvé.</h1>";
    }
}

// Fonction pour afficher les covoiturages d'autres dates (si applicable)
function showJourneysOtherDates($journeys)
{
    if (count($journeys) > 0) {
        echo "<h1>Aucun covoiturage trouvé pour cette date, mais voici d'autres trajets :</h1>";

        foreach ($journeys as $journey) {
            require "templates/journey_part.php"; // Affichage du covoiturage
        }
    }
}

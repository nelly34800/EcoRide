<?php
function showJourneys($title, $journeys, $textBouton, $action, $showPlaces = true)
{
    echo "<div class='table-responsive p-4'>";
    echo "<h4>$title</h4>";

    if (empty($journeys)) {
        echo "<p>Aucun trajet trouvé.</p>";
    } else {
        echo "<table class='table table-bordered table-striped'>";
        // En-tête du tableau
        echo "<tr>
                <th class='date-cell'>Date</th>
                <th class='d-none d-md-table-cell' colspan='2'>Départ</th>
                <th class='d-none d-md-table-cell' colspan='2'>Arrivée</th>";
        // Affichage de la colonne "Places dispo" uniquement si $showPlaces est vrai
        if ($showPlaces) {
            echo "<th class='d-none d-md-table-cell'>Places dispo</th>";
        }
        echo "<th class='d-none d-md-table-cell'>Action</th>
    </tr>";

        foreach ($journeys as $journey) {
            // Ligne pour les écrans moyens et grands
            echo "<tr>
                    <td>" . htmlspecialchars(changeDateFormat($journey['date'])) . "</td>
                    <td class='d-none d-md-table-cell'>" . htmlspecialchars($journey['place_departure']) . "</td>
                    <td class='d-none d-md-table-cell'>" . htmlspecialchars(changeHourFormat($journey['departure_time'])) . "</td>
                    <td class='d-none d-md-table-cell'>" . htmlspecialchars($journey['place_arrival']) . "</td>
                    <td class='d-none d-md-table-cell'>" . htmlspecialchars(changeHourFormat($journey['arrival_time'])) . "</td>";
            // Affichage de la colonne "Places dispo" uniquement si $showPlaces est vrai
            if ($showPlaces) {
                echo "<td class='d-none d-md-table-cell'>" . htmlspecialchars($journey['number_places']) . "</td>";
            }

            echo "<td class='d-none d-md-table-cell'>
                        <form method='POST' action='$action'>
                            <input type='hidden' name='journey_id' value='" . htmlspecialchars($journey['id']) . "'>
                            <button class='btn btn-primary' type='submit'>$textBouton</button>
                            <a href='sup_covoiturage.php?id=" . $journey['id'] . "' class='btn btn-dark m-2' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer ce covoiturage ?\");'>Supprimer covoiturage</a>
                        </form>
                    </td>
            </tr>";
            // Ligne pour les petits écrans
            echo "<tr class='d-md-none'>
                    <td colspan='4'>
                        <strong>Date :</strong> " . htmlspecialchars(changeDateFormat($journey['date'])) . "<br>
                        <strong>Départ :</strong> " . htmlspecialchars($journey['place_departure']) . " - " . htmlspecialchars(changeHourFormat($journey['departure_time'])) . "<br>
                        <strong>Arrivée :</strong> " . htmlspecialchars($journey['place_arrival']) . " - " . htmlspecialchars(changeHourFormat($journey['arrival_time'])) . "<br>";

            if ($showPlaces) {
                echo "<strong>Places disponibles :</strong> " . htmlspecialchars($journey['number_places']) . "<br>";
            }

            echo "  <form method='POST' action='$action'>
                        <input type='hidden' name='journey_id' value='" . htmlspecialchars($journey['id']) . "'>
                        <button class='btn btn-primary btn-sm' type='submit'>$textBouton</button>
                        <a href='sup_covoiturage.php?id=" . $journey['id'] . "' class='btn btn-dark btn-sm m-2' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer ce covoiturage ?\");'>Supprimer covoiturage</a>
                    </form>
                    </td>
                  </tr>";
        }
        echo "</table>";
    }
    echo "</div>";
}
// Affichage des différentes catégories de trajets
showJourneys("Covoiturages à venir", $pending, "Démarrer covoiturage", "lib/start_carpool_pc.php", true);
showJourneys("Covoiturages complets", $complet, "Démarrer covoiturage", "lib/start_carpool_pc.php", false);
showJourneys("Covoiturages en cours", $ongoing, "Arrivée à destination", "lib/arrival_carpool_pc.php", false);
showJourneys("Historique des covoiturages", $completed, "", "", false);

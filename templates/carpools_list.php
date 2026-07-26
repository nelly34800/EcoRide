<?php
function showJourneys($title, $journeys, $showPlaces = true,  $textBouton1 = "", $action1 = "", $textBouton2 = "", $action2 = "", $textBouton3 = "", $action3 = "", $textBouton4 = "", $action4 = "")
{
    echo "<div class='table-responsive p-4'>";
    echo "<h4>$title</h4>";

    if (empty($journeys)) {
        echo "<p>Aucun trajet trouvé.</p>";
    } else {

           // Vérifie si la colonne Action est nécessaire
        $hasActions = !empty($textBouton1) || !empty($textBouton2) || !empty($textBouton3)|| !empty($textBouton4);

        echo "<table class='table table-bordered table-striped'>";
        // En-tête du tableau
        echo "<tr>
                <th class='d-none d-md-table-cell'>Date</th>
                <th class='d-none d-md-table-cell' colspan='2'>Départ</th>
                <th class='d-none d-md-table-cell' colspan='2'>Arrivée</th>";
        // Affichage de la colonne "Places dispo" uniquement si $showPlaces est vrai
        if ($showPlaces) {
            echo "<th class='d-none d-md-table-cell'>Places dispo</th>";
        }

        if ($hasActions) {
            echo "<th class='d-none d-md-table-cell'>Action</th>";
        }
        echo "</tr>";

        foreach ($journeys as $journey) {
            // Ligne pour les écrans moyens et grands
            echo "<tr class='d-none d-md-table-row'>
                    <td>" . htmlspecialchars(changeDateFormat($journey['date'])) . "</td>
                    <td class='d-none d-md-table-cell'>" . htmlspecialchars($journey['place_departure']) . "</td>
                    <td class='d-none d-md-table-cell'>" . htmlspecialchars(changeHourFormat($journey['departure_time'])) . "</td>
                    <td class='d-none d-md-table-cell'>" . htmlspecialchars($journey['place_arrival']) . "</td>
                    <td class='d-none d-md-table-cell'>" . htmlspecialchars(changeHourFormat($journey['arrival_time'])) . "</td>";
            // Affichage de la colonne "Places dispo" uniquement si $showPlaces est vrai
            if ($showPlaces) {
                echo "<td class='d-none d-md-table-cell'>" . htmlspecialchars($journey['total_seats']) . "</td>";
            }

            if ($hasActions) {
                echo "<td>";
                if (!empty($textBouton1)) {
                    echo "<form method='POST' action='$action1'>
                        <input type='hidden' name='journey_id' value='" . htmlspecialchars($journey['id']) . "'>
                        <button class='btn btn-primary btn-sm' type='submit'>$textBouton1</button>
                    </form>";
                }
                if (!empty($textBouton2)) {
                     echo "<form method='POST' action='$action2'>
                            <input type='hidden' name='journey_id' value='" . htmlspecialchars($journey['id']) . "'>
                            <button class='btn btn-dark btn-sm' type='submit' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer ce covoiturage ?\");'>$textBouton2</button>
                    </form>";
                }
                if (!empty($textBouton3)) {
                    echo "<form method='POST' action='$action3'>
                        <input type='hidden' name='journey_id' value='" . htmlspecialchars($journey['id']) . "'>
                        <button class='btn btn-primary btn-sm' type='submit'>$textBouton3</button>
                    </form>";
                }
                if (!empty($textBouton4)) {
                    echo "<form method='GET' action='$action4'>
                            <input type='hidden' name='journey_id' value='" . htmlspecialchars($journey['id']) . "'>
                            <button class='btn btn-dark btn-sm' type='submit' onclick='return confirm(\"Êtes-vous sûr de vouloir signaler un problème ?\");'>$textBouton4</button>
                    </form>";
                }
                echo "</td>";
            }
            echo "</tr>";

            // Ligne pour les petits écrans
            echo "<tr class='d-md-none'>
                    <td colspan='4'>
                        <strong>Date :</strong> " . htmlspecialchars(changeDateFormat($journey['date'])) . "<br>
                        <strong>Départ :</strong> " . htmlspecialchars($journey['place_departure']) . " - " . htmlspecialchars(changeHourFormat($journey['departure_time'])) . "<br>
                        <strong>Arrivée :</strong> " . htmlspecialchars($journey['place_arrival']) . " - " . htmlspecialchars(changeHourFormat($journey['arrival_time'])) . "<br>";

            if ($showPlaces) {
                echo "<strong>Places disponibles :</strong> " . htmlspecialchars($journey['total_seats']) . "<br>";
            }

            if ($hasActions) {
                if (!empty($textBouton1)) {
                    echo "<form method='POST' action='$action1'>
                        <input type='hidden' name='journey_id' value='" . htmlspecialchars($journey['id']) . "'>
                        <button class='btn btn-primary btn-sm' type='submit'>$textBouton1</button>
                    </form>";
                }
                if (!empty($textBouton2)) {
                     echo "<form method='POST' action='$action2'>
                            <input type='hidden' name='journey_id' value='" . htmlspecialchars($journey['id']) . "'>
                            <button class='btn btn-dark btn-sm' type='submit' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer ce covoiturage ?\");'>$textBouton2</button>
                    </form>";
                }
                if (!empty($textBouton3)) {
                    echo "<form method='POST' action='$action3'>
                        <input type='hidden' name='journey_id' value='" . htmlspecialchars($journey['id']) . "'>
                        <button class='btn btn-primary btn-sm' type='submit'>$textBouton3</button>
                    </form>";
                }
                if (!empty($textBouton4)) {
                    echo "<form method='GET' action='$action4'>
                            <input type='hidden' name='journey_id' value='" . htmlspecialchars($journey['id']) . "'>
                            <button class='btn btn-dark btn-sm' type='submit' onclick='return confirm(\"Êtes-vous sûr de vouloir signaler un problème ?\");'>$textBouton4</button>
                    </form>";
                }
                echo "</td>"; 
            }
            echo "</tr>";
                  }
        echo "</table>";
    }
    echo "</div>";
}
// Affichage des différentes catégories de trajets
showJourneys("Covoiturages à venir", $pending, true,  "", "", "", "", "Démarrer covoiturage", "lib/start_carpool.php", "suprimer covoiturage", "sup_covoiturage.php");
showJourneys("Covoiturages complets", $complet, false, "", "", "", "", "Démarrer covoiturage", "lib/start_carpool.php", "suprimer covoiturage", "sup_covoiturage.php");
showJourneys("Covoiturages en cours", $ongoing, false, "Arrivée à destination", "lib/arrival_carpool.php", "Signaler problème", "signaler_probleme.php");
showJourneys("Historique des covoiturages", $completed, false);

<?php
function showReservations($title, $journeys, $textBouton1 = "", $action1 = "", $textBouton2 = "", $action2 = "", $textBouton3 = "", $action3 = "")
{
    echo "<div class='table-responsive p-4'>";
    echo "<h4>$title</h4>";

    if (empty($journeys)) {
        echo "<p>Aucun trajet trouvé.</p>";
    } else {

        // Vérifie si la colonne Action est nécessaire
        $hasActions = !empty($textBouton1) || !empty($textBouton2) || !empty($textBouton3);

        echo "<table class='table table-bordered table-striped'>";
        echo "<tr>
            <th class='date-cell'>Date</th>
            <th class='d-none d-md-table-cell' colspan='2'>Départ</th>
            <th class='d-none d-md-table-cell' colspan='2'>Arrivée</th>";

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
                            <button class='btn btn-dark btn-sm' type='submit' onclick='return confirm(\"Êtes-vous sûr de vouloir signaler un problème ?\");'>$textBouton2</button>
                    </form>";
                }
                if (!empty($textBouton3)) {
                     echo "<form method='POST' action='$action3'>
                            <input type='hidden' name='journey_id' value='" . htmlspecialchars($journey['id']) . "'>
                            <button class='btn btn-dark btn-sm' type='submit' onclick='return confirm(\"Êtes-vous sûr de vouloir annuler votre participation à ce covoiturage ?\");'>$textBouton3</button>
                    </form>";
                }
                echo "</td>";
            }
            echo "</tr>";

            // Ligne pour les petits écrans (mobile)
            echo "<tr class='d-md-none'>
            <td colspan='4'>
                <strong>Date :</strong> " . htmlspecialchars(changeDateFormat($journey['date'])) . "<br>
                <strong>Départ :</strong> " . htmlspecialchars($journey['place_departure']) . " - " . htmlspecialchars(changeHourFormat($journey['departure_time'])) . "<br>
                <strong>Arrivée :</strong> " . htmlspecialchars($journey['place_arrival']) . " - " . htmlspecialchars(changeHourFormat($journey['arrival_time'])) . "<br>";

            // Ajout d'un autre <tr> uniquement pour les boutons
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
                            <button class='btn btn-dark btn-sm' type='submit' onclick='return confirm(\"Êtes-vous sûr de vouloir signaler un problème ?\");'>$textBouton2</button>
                    </form>";
                }
                if (!empty($textBouton3)) {
                     echo "<form method='POST' action='$action3'>
                            <input type='hidden' name='journey_id' value='" . htmlspecialchars($journey['id']) . "'>
                            <button class='btn btn-dark btn-sm' type='submit' onclick='return confirm(\"Êtes-vous sûr de vouloir annuler votre participation à ce covoiturage ?\");'>$textBouton3</button>
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
showReservations("Covoiturages à venir", $passenger_upcoming, "", "", "", "", "annuler trajet", "sup_reservation.php",);
showReservations("Covoiturages en cours", $passenger_ongoing, "Valider trajet", "lib/validate_journey.php", "Signaler problème", "lib/report_issue.php");
showReservations("Historique des covoiturages", $passenger_completed);

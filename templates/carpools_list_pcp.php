<?php
function showReservations($title, $journeys,  $textBouton1, $action1, $textBouton2, $action2)
{
    echo "<div class='table-responsive p-4'>";
    echo "<h4>$title</h4>";

    if (empty($journeys)) {
        echo "<p>Aucun trajet trouvé.</p>";
    } else {
        echo "<table class='table table-bordered table-striped'>";
        echo "<tr>
            <th class='date-cell'>Date</th>
            <th class='d-none d-md-table-cell' colspan='2'>Départ</th>
            <th class='d-none d-md-table-cell' colspan='2'>Arrivée</th>";

        echo "<th class='d-none d-md-table-cell'>Action</th>
        </tr>";

        foreach ($journeys as $journey) {
            // Ligne pour les écrans moyens et grands
            echo "<tr class='d-none d-md-table-row'>
            <td>" . htmlspecialchars(changeDateFormat($journey['date'])) . "</td>
            <td class='d-none d-md-table-cell'>" . htmlspecialchars($journey['place_departure']) . "</td>
            <td class='d-none d-md-table-cell'>" . htmlspecialchars(changeHourFormat($journey['departure_time'])) . "</td>
            <td class='d-none d-md-table-cell'>" . htmlspecialchars($journey['place_arrival']) . "</td>
            <td class='d-none d-md-table-cell'>" . htmlspecialchars(changeHourFormat($journey['arrival_time'])) . "</td>
            <td class='d-none d-md-table-cell'>";
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
            echo " <a href='sup_reservation.php?id=" . $journey['id'] . "' class='btn btn-dark btn-sm m-2' onclick='return confirm(\" Êtes-vous sûr de vouloir annuler votre participation à ce covoiturage ?\");'>annuler trajet</a>
            </td>
        </tr>";

            // Ligne pour les petits écrans (mobile)
            echo "<tr class='d-md-none'>
            <td colspan='4'>
                <strong>Date :</strong> " . htmlspecialchars(changeDateFormat($journey['date'])) . "<br>
                <strong>Départ :</strong> " . htmlspecialchars($journey['place_departure']) . " - " . htmlspecialchars(changeHourFormat($journey['departure_time'])) . "<br>
                <strong>Arrivée :</strong> " . htmlspecialchars($journey['place_arrival']) . " - " . htmlspecialchars(changeHourFormat($journey['arrival_time'])) . "<br>
                </td>
        </tr>";

            // Ajout d'un autre <tr> uniquement pour les boutons
            echo "<tr class='d-md-none'>
            <td colspan='4' class='text-center'>";
            if (!empty($textBouton1)) {
                echo "<form method='POST' action='$action1'>
                    <input type='hidden' name='journey_id' value='" . htmlspecialchars($journey['id']) . "'>
                    <button class='btn btn-primary btn-sm w-100 mt-1' type='submit'>$textBouton1</button>
                </form>";
            }

            if (!empty($textBouton2)) {
                echo "<form method='POST' action='$action2'>
                    <input type='hidden' name='journey_id' value='" . htmlspecialchars($journey['id']) . "'>
                    <button class='btn btn-dark btn-sm w-100 mt-1' type='submit' onclick='return confirm(\"Êtes-vous sûr de vouloir signaler un problème ?\");'>$textBouton2</button>
                </form>";
            }

            echo "<a href='sup_reservation.php?id=" . $journey['id'] . "' class='btn btn-dark btn-sm w-100 mt-1' onclick='return confirm(\" Êtes-vous sûr de vouloir annuler votre participation à ce covoiturage ?\");'>Annuler trajet</a>";
            echo "</td>
        </tr>";
        }
        echo "</table>";
    }
    echo "</div>";
}
// Affichage des différentes catégories de trajets
showReservations("Covoiturages à venir", $passenger_upcoming, "", "", "", "");
showReservations(
    "Covoiturages en cours",
    $passenger_ongoing,
    "Valider trajet",
    "lib/validate_journey_pc.php",
    "Signaler problème",
    "lib/report_issue_pc.php"
);
showReservations("Historique des covoiturages", $passenger_completed, "", "", "", "");

<?php
require_once "Lib/pdo.php";
require_once "Lib/reservation.php";
require_once "lib/journey.php";
require_once "lib/utils.php";
require_once "templates/header.php";

if (isset($_GET['id']) && isset($_GET['action'])) {
    $journey_id = (int)$_GET['id'];
    $action = $_GET['action'];

    // Vérifiez que l'utilisateur est connecté
    if (isset($_SESSION['user'])) {
        $user_id = $_SESSION['user']['id'];
        $role_id = $_SESSION['user']['role_id'];
    } else {
        echo "Utilisateur non connecté.";
        exit();
    }

    // Vérifiez que le trajet existe
    $journey = getJourneysById($pdo, $journey_id);
    if (!$journey) {
        echo "Le covoiturage est introuvable";
        exit();
    }

    // Affichez les détails de la réservation et proposez de confirmer ou d'annuler
    if ($action == 'confirmer') {
?>

        <div class="hero-scene">
            <img src="assets/img/BanTrajet.jpg" alt="banniére décorative" width="100%">
        </div>

        <div class="container p-4 text-center">
            <div class="col-auto my-4">
                <h1>Confirmation réservation <br> détails du trajet: </h1>
            </div>
            <div class="col-auto my-4">
                <p class="card-text">
                    trajet de: <?= htmlspecialchars($journey['place_departure']); ?> à <?= htmlspecialchars($journey['place_arrival']); ?> <br>
                    départ le <?= htmlspecialchars(changeDateFormatJour($journey['date'])); ?> à <?= htmlspecialchars(changeHourFormat($journey['departure_time'])); ?> <br>
                </p>
            </div>
            <div class="col-auto my-4">
                pour un montant de: <?= htmlspecialchars($journey['price']); ?> crédits
            </div>
            <div class="col-auto my-4">
                <a class="btn btn-dark py-2 px-2" href="Confirme_reservation.php?action=annuler&id=<?= $journey_id ?>">Annuler</a>
                <a class="btn btn-primary py-2 px-4" href="Confirme_reservation.php?action=confirmer_final&id=<?= $journey_id ?>">Confirmer</a>
            </div>
        </div>

<?php
    } elseif ($action == 'annuler') {
        // Redirection ou autre logique pour annuler la réservation
        header("Location: covoiturages.php");
        exit();
    }

    if ($action == 'confirmer_final') {
        // Logique pour ajouter une réservation
        addReservation($pdo, $user_id, $journey_id, $role_id);
        header("Location: confirmation.php?status=success");
        exit();
    }
} else {
    echo "Paramètres manquants.";
    exit();
}
?>

<?php
require_once "templates/footer.php";
?>
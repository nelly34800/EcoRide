<?php
require_once "lib/pdo.php";
require_once "lib/reservation.php";
require_once "lib/journey.php";
require_once "lib/profile.php";
require_once "lib/send_email.php";
require_once "lib/utils.php";
require_once "templates/header.php";

// Vérifie que l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    echo '<div class="alert alert-danger text-center">Utilisateur non connecté.</div>';
    exit();
}

$user_id = $_SESSION['user']['id'];
$role_id = $_SESSION['user']['role_id'] ?? null;

if (!isset($_GET['id'], $_GET['action'])) {
    echo '<div class="alert alert-danger text-center">Paramètres manquants.</div>';
    exit();
}

$journey_id = (int)$_GET['id'];
$action = $_GET['action'];

// Récupère le trajet
$journey = getJourneysById($pdo, $journey_id);
if (!$journey) {
    echo '<div class="alert alert-danger text-center">Le covoiturage est introuvable.</div>';
    exit();
}

function displayConfirmationPage($journey_id, $journey) {
    ?>
    <div class="hero-scene">
        <img src="assets/img/BanTrajet.jpg" alt="banniére décorative" width="100%">
    </div>

    <div class="container p-4 text-center">
        <h1>Confirmation réservation <br> détails du trajet:</h1>
        <p>
            trajet de: <?= htmlspecialchars($journey['place_departure']); ?> à <?= htmlspecialchars($journey['place_arrival']); ?> <br>
            départ le <?= htmlspecialchars(changeDateFormatJour($journey['date'])); ?> à <?= htmlspecialchars(changeHourFormat($journey['departure_time'])); ?> <br>
            montant: <?= htmlspecialchars($journey['price']); ?> crédits
        </p>
        <a class="btn btn-dark py-2 px-2" href="confirme_reservation.php?action=annuler&id=<?= $journey_id ?>">Annuler</a>
        <a class="btn btn-primary py-2 px-4" href="confirme_reservation.php?action=confirmer_final&id=<?= $journey_id ?>">Confirmer</a>
    </div>
    <?php
}

function sendConfirmationEmails($pdo, $user_id, $journey, $journey_id) {
    // Mail passager
    $userInfo = getUserById($pdo, $user_id);
    $email = $userInfo['email'];
    $pseudo = $userInfo['pseudo'];
    $typeMessage = "Confirmation de réservation";
    $messageHtml = "<p>Bonjour " . htmlspecialchars($pseudo) . ",</p>";
    $messageHtml .= "<p>Votre réservation pour le trajet de " 
        . htmlspecialchars($journey['place_departure']) 
        . " à " 
        . htmlspecialchars($journey['place_arrival']) 
        . " le " 
        . htmlspecialchars(changeDateFormatJour($journey['date'])) 
        . " à " 
        . htmlspecialchars(changeHourFormat($journey['departure_time'])) 
        . " a bien été confirmée !</p>";
    $messageHtml .= "<p>Merci pour votre confiance.</p>";

    if (!sendBrevoMail($email, $pseudo, $typeMessage, $messageHtml)) {
        error_log("Erreur d'envoi du mail de confirmation au passager $email");
    }

    // Mail chauffeur unique
    $driver = getDriverByJourney($pdo, $journey_id);
    if ($driver && isset($driver['email'], $driver['pseudo'])) {
        $typeMessage = "Nouvelle réservation";
        $messageHtml = "<p>Bonjour " . htmlspecialchars($driver['pseudo']) . ",</p>";
        $messageHtml .= "<p>Un passager vient de réserver une place pour votre trajet de <strong>" 
            . htmlspecialchars($journey['place_departure']) 
            . "</strong> à <strong>" 
            . htmlspecialchars($journey['place_arrival']) 
            . "</strong> le <strong>" 
            . htmlspecialchars(changeDateFormatJour($journey['date'])) 
            . "</strong> à <strong>" 
            . htmlspecialchars(changeHourFormat($journey['departure_time'])) 
            . "</strong> 🚗💬</p>";
        $messageHtml .= "<p>Bonne route !</p>";

        if (!sendBrevoMail($driver['email'], $driver['pseudo'], $typeMessage, $messageHtml)) {
            error_log("Erreur d'envoi du mail au chauffeur " . $driver['email']);
        }
    } else {
        error_log("Chauffeur introuvable ou info manquante pour trajet " . $journey['id']);
    }
}

// Traitement des actions
switch ($action) {
    case 'confirmer':
        displayConfirmationPage($journey_id, $journey);
        break;

    case 'annuler':
        echo '<div class="alert alert-success text-center">Réservation annulée.</div>';
        echo '<meta http-equiv="refresh" content="2;url=covoiturages.php">';
        break;

    case 'confirmer_final':
        // Vérifie crédits
        if (!hasSufficientCredits($pdo, $user_id, $journey['price'])) {
            echo '<div class="alert alert-danger text-center">Crédits insuffisants pour réserver ce covoiturage.</div>';
            exit();
        }

        // Déduit crédits + ajoute réservation
        if (deductCredits($pdo, $user_id, $journey['price']) && addReservation($pdo, $user_id, $journey_id, $role_id)) {
            sendConfirmationEmails($pdo, $user_id, $journey, $journey_id);
            echo '<div class="alert alert-success text-center">Votre réservation a été confirmée avec succès !</div>';
            echo '<meta http-equiv="refresh" content="2;url=confirmation.php?type=reservation&status=success">';
        } else {
            echo '<div class="alert alert-danger text-center">Erreur lors du traitement de la réservation.</div>';
        }
        break;

    default:
        echo '<div class="alert alert-danger text-center">Action inconnue.</div>';
}

require_once "templates/footer.php";
?>
<?php
require_once "lib/pdo.php";
require_once "lib/journey.php";
require_once "lib/reservation.php";
require_once "lib/review.php";
require_once "templates/header.php";

if (!isset($_SESSION['user']['id'])) {
    header("Location: ../signin.php");
    exit();
}

$user_id = $_SESSION['user']['id'];

// Récupérer le trajet depuis la session
$journey_id = $_SESSION['last_journey_id'] ?? null;

if (!$journey_id) {
    echo "Aucun trajet sélectionné.";
    exit();
}

$journey = getJourneysById($pdo, $journey_id);
if (!$journey) {
    echo "Trajet introuvable.";
    exit();
}
// Vérifier que l'utilisateur est bien un passager de ce trajet
$reservation_id = getReservationId($pdo, $journey_id, $user_id);
if (!$reservation_id) {
    echo "Vous ne pouvez pas noter ce trajet.";
    exit();
}

// Soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = isset($_POST['rating']) ? (int) $_POST['rating'] : 0;
    $comment = isset($_POST['comment']) ? htmlspecialchars(trim($_POST['comment'])) : "";
    
    // Vérification de la note
    if ($rating < 1 || $rating > 5) {
        echo "La note doit être comprise entre 1 et 5.";
        exit();
    }
    $id_driver = $journey['user_id'];
    $id_passenger = $user_id;

    $idAvis = registerReview($id_driver, $id_passenger, $rating, $comment);

     if ($idAvis) {
        // Redirection pour éviter la double soumission
        header("Location: index.php?success=1");
        exit();
    } else {
        echo "Erreur lors de l'envoi de l'avis.";
    }
}
?>

<div class="hero-scene">
    <img src="assets/img/banTrajet.jpg" alt="" width="100%">
</div>
<h1>Donner une note et un avis à votre chauffeur ! </h1>
<div class="form-signin w-100 m-auto">
    <form method="POST">
        
        <label class="form-label" for="rating">Note : </label>
        <input type="number" class="form-control" name="rating" min="1" max="5" required>
        
        <label class="form-label" for=">comment"> Commentaire : </label>
        <textarea name="comment" class="form-control"></textarea>
        
        <button type="submit" class="btn btn-primary">Envoyer l'avis</button>
    </form>
</div>

<?php
require_once "templates/footer.php";
?>
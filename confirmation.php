<?php
require_once "templates/header.php";
?>

<div class="container p-4 text-center">
    <h1>Confirmation de la réservation</h1>
    <?php if ($_GET['status'] == 'success'): ?>
        <p>Votre réservation a été confirmée avec succès !</p>
    <?php else: ?>
        <p>Une erreur s'est produite lors de la confirmation de votre réservation.</p>
    <?php endif; ?>
    <a href="covoiturages.php" class="btn btn-primary">Retour aux covoiturages</a>
</div>

<?php
require_once "templates/footer.php";
?>
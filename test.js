// ajuster le colspan sur les petits écrans
function adjustColspan() {
    document.querySelectorAll('.date-cell').forEach(function(cell) {
        if (window.innerWidth < 768) {
            cell.colSpan = 5; // Ajustez la valeur du colspan en fonction du nombre total de colonnes
        } else {
            cell.colSpan = 1;
        }
    });
}

$(document).ready(function() {
    $(".start-carpool").click(function() {
        let journeyId = $(this).data("journey-id");  
        console.log("Démarrage du covoiturage pour le trajet avec ID: " + journeyId);

        $.ajax({
            url: "../lib/start_carpool.php",
            type: "POST",
            data: { journey_id: journeyId },
            dataType: "json", // 🛑 Ajout de cette ligne pour forcer un retour JSON
            success: function(response) {
                console.log(response); // ✅ Affiche la réponse dans la console

                if (response.status === "success") {
                    alert("Covoiturage démarré !");
                } else {
                    alert("Erreur : " + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("Erreur AJAX :", xhr.responseText);
                alert("Une erreur s'est produite. Vérifiez la console.");
            }
        });
    });
});
    $(".arrival").click(function() {
        let journeyId = $(this).data("journey-id");
        let button = $(this);  // Sauvegarde le bouton dans une variable

        $.ajax({
            url: "../lib/arrival_carpool.php",  // fichier PHP pour gérer l'arrivée
            type: "POST",
            data: { journey_id: journeyId },
            success: function(response) {
                if (response === "success") {
                    button.closest('tr').remove();  // Supprimer la ligne du tableau
                    alert("Covoiturage terminé !");
                } else {
                    alert("Erreur lors de l'enregistrement de l'arrivée.");
                }
            }
        });

    // Appel de la fonction adjustColspan pour ajuster le colspan
    adjustColspan();

    // Ajoutez un écouteur d'événements pour ajuster le colspan lors du redimensionnement de la fenêtre
    window.addEventListener("resize", adjustColspan);
});

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

// Appeler la fonction lors du chargement de la page et au redimensionnement de la fenêtre
window.onload = adjustColspan;
window.onresize = adjustColspan;
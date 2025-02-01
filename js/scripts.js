document.addEventListener("DOMContentLoaded", function() {
    // Fonction pour récupérer les données des filtres
    const applyFilters = () => {
        const placeDeparture = document.getElementById("place_departure").value;
        const placeArrival = document.getElementById("place_arrival").value;
        const date = document.getElementById("date").value;
        const maxPrice = document.getElementById("max-price") ? document.getElementById("max-price").value : "";
        const energy = document.getElementById("energy") ? document.getElementById("energy").checked : false;
        const minDriveNote = document.getElementById("min-drive-note") ? document.getElementById("min-drive-note").value : "";
        const maxDuration = document.getElementById("max_duration") ? document.getElementById("max_duration").value : "";

        // Création de l'objet avec les filtres
        const filters = {
            place_departure: placeDeparture,
            place_arrival: placeArrival,
            date: date,
            "max-price": maxPrice,
            energy: energy,
            "min-drive-note": minDriveNote,
            "max-duration": maxDuration
        };

        // Envoi des filtres au serveur avec Fetch API
        fetch('covoiturages.php', {
            method: 'GET', // méthode GET pour récupérer les résultats
            headers: {
                'Content-Type': 'application/json',
            },
            // Ajout des filtres dans l'URL comme query string
            body: JSON.stringify(filters)
        })
        .then(response => response.text())
        .then(data => {
            document.querySelector(".result-container").innerHTML = data;
        })
        .catch(error => console.error("Erreur:", error));
    };

    // Appliquer les filtres lorsqu'un utilisateur soumet le formulaire
    document.querySelector("form").addEventListener("submit", (e) => {
        e.preventDefault();
        applyFilters(); // appliquer les filtres en appelant la fonction
    });
});
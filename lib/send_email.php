<?php
require_once "pdo.php";
require_once 'config.php';
function sendBrevoMail($email, $pseudo, $typeMessage, $messageHtml) {
    
    // prépare les données pour Brevo + met un peu de CSS dans l'email
    $data = [
        "sender" => ["email" => "nellyboussekhane@free.fr", "name" => "EcoRide"],
        "to" => [["email" => $email]],
        "subject" => ucfirst($typeMessage) . " - EcoRide",
        "htmlContent" => "<html>  <body style='font-family: Georgia, serif; background-color: #ffffff; padding: 20px; color: #aaee88;'>
    <div style='background-color: #004477; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);'>
      <h3 style='font-family: Verdana, sans-serif; color: #44eedd;'>Bonjour $pseudo,</h3><br>
    <p>Nous vous envoyons ce message concernant votre trajet.</p><br>
    <p>$messageHtml</p></body></html>"
];
// Appel API avec cURL
    $ch = curl_init();// Initialise la session cURL
    curl_setopt($ch, CURLOPT_URL, "https://api.brevo.com/v3/smtp/email");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // résultat retourné (et pas affiché directement)
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); // Envoie les données au format JSON
    curl_setopt($ch, CURLOPT_HTTPHEADER, [ // Ajoute les bons en-têtes
        "accept: application/json",
        "content-type: application/json",
        "api-key: " . $_ENV["BREVO_API_KEY"] //récupère la clé dans .env
    ]);

    $response = curl_exec($ch); // Exécute la requête
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch); // Ferme la session

    return $httpCode === 201;
}

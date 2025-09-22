<?php
require_once __DIR__ . '/../vendor/autoload.php';

use MongoDB\Client;
use MongoDB\BSON\UTCDateTime;
use MongoDB\BSON\ObjectId;
use Dotenv\Dotenv;

// Charger le .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

/**
 * Helper pour récupérer une collection MongoDB
 */
function getCollection($name) {
    $uri = $_ENV['MONGODB_URI'];
    $dbname = $_ENV['MONGODB_DB'];

    $client = new Client($uri);
    $db = $client->selectDatabase($dbname);

    return $db->$name;
}

/**
 * Enregistrer un nouvel avis (sécurisé)
 */
function registerReview($id_driver, $id_passenger, $rating, $comment) {
    $collection = getCollection('reviews');

    // Sécurisation des types
    $id_driver    = (string) $id_driver;
    $id_passenger = (string) $id_passenger;
    $rating       = (int) $rating;
    $comment      = (string) $comment;

    // Validation de la note
    if ($rating < 1 || $rating > 5) {
        throw new InvalidArgumentException("La note doit être comprise entre 1 et 5.");
    }

    // Nettoyage du commentaire
    $comment = trim(strip_tags($comment));

    // Préparation du document
    $review = [
        "id_driver"    => $id_driver,
        "id_passenger" => $id_passenger,
        "rating"       => $rating,
        "comment"      => $comment,
        "valide"       => false,
        "date"         => new UTCDateTime()
    ];

    $result = $collection->insertOne($review);

    return $result->getInsertedId();
}

// Récupérer les avis non validés
function getInvalidReviews($pdo) {
    $collection = getCollection('reviews');
    $cursor = $collection->find(["valide" => false]);

    $reviews = [];
    $userIds = [];

    // Parcours des reviews
    foreach ($cursor as $review) {
        $driver = $review['id_driver'] ?? null;
        $passenger = $review['id_passenger'] ?? null;

        // Ajouter aux IDs pour récupérer les pseudos
        if (!empty($driver)) $userIds[] = $driver;
        if (!empty($passenger)) $userIds[] = $passenger;

        $reviews[] = [
            'id_review'    => (string)$review['_id'],
            'rating'       => $review['rating'] ?? null,
            'comment'      => $review['comment'] ?? '',
            'date'         => isset($review['date']) && $review['date'] instanceof \MongoDB\BSON\UTCDateTime
                                ? $review['date']->toDateTime()->format('Y-m-d H:i:s')
                                : null,
            'id_driver'    => $driver,
            'id_passenger' => $passenger
        ];
    }

    // Filtrer les IDs vides
    $userIds = array_filter($userIds, fn($id) => !empty($id));

    // Récupérer les pseudos depuis SQL si au moins un ID
    $users = [];
    if (!empty($userIds)) {
        $placeholders = implode(',', array_fill(0, count($userIds), '?'));
        $sql = "SELECT id, pseudo FROM users WHERE id IN ($placeholders)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($userIds);
        $users = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // tableau id => pseudo
    }

    // Ajouter les pseudos aux reviews
    foreach ($reviews as &$review) {
        $review['driver_pseudo'] = $users[$review['id_driver']] ?? 'Chauffeur inconnu';
        $review['passenger_pseudo'] = $users[$review['id_passenger']] ?? 'Passager inconnu';
    }

    return $reviews;
}

function validateReview($id_review) {
    $collection = getCollection('reviews');

    try {
        $objectId = new ObjectId($id_review); // va lancer une exception si ID invalide
    } catch (\Exception $e) {
        throw new InvalidArgumentException("ID d'avis invalide.");
    }

    $result = $collection->updateOne(
        ["_id" => $objectId],
        ['$set' => ["valide" => true]]
    );

    return $result->getModifiedCount();
}

function rejectReview($id_review) {
    $collection = getCollection('reviews');

    try {
        $objectId = new ObjectId($id_review);
    } catch (\Exception $e) {
        throw new InvalidArgumentException("ID d'avis invalide.");
    }

    $result = $collection->deleteOne(["_id" => $objectId]);

    return $result->getDeletedCount();
}

function getAverageRating($id_driver) {
    $collection = getCollection('reviews');

    // Conversion en string pour correspondre au type dans MongoDB
    $id_driver = (string) $id_driver;

    $cursor = $collection->find([
        "id_driver" => (string) $id_driver,
        "valide"    => true // on prend que les avis validés
    ]);

    $total = 0;
    $count = 0;

    foreach ($cursor as $review) {
        if (isset($review['rating'])) {
            $total += $review['rating'];
            $count++;
        }
    }

    return $count > 0 ? round($total / $count, 1) : 0; // moyenne arrondie à 1 décimale
}

function getDriverReviews($id_driver, $pdo) {
    $collection = getCollection('reviews');
    $id_driver = (string) $id_driver; // Conversion en string

    $cursor = $collection->find(
        [
            "id_driver" => $id_driver,
            "valide"    => true
        ],
        [
            'sort' => ['date' => -1]
        ]
    );

    $reviews = [];
    $passengerIds = [];

    foreach ($cursor as $review) {
        $passengerId = isset($review['id_passenger']) ? (string)$review['id_passenger'] : null;

        if ($passengerId) {
            $passengerIds[] = $passengerId;
        }

        $reviews[] = [
            'rating'       => $review['rating'] ?? null,
            'comment'      => $review['comment'] ?? '',
            'date'         => isset($review['date']) && $review['date'] instanceof \MongoDB\BSON\UTCDateTime
                                ? $review['date']->toDateTime()->format('Y-m-d H:i:s')
                                : null,
            'id_passenger' => $passengerId
        ];
    }

    // Récupérer les pseudos SQL
    $passengerIds = array_unique($passengerIds);
    $users = [];

    if (!empty($passengerIds)) {
        $placeholders = implode(',', array_fill(0, count($passengerIds), '?'));
        $sql = "SELECT id, pseudo FROM users WHERE id IN ($placeholders)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($passengerIds);
        $users = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    // Ajouter les pseudos aux reviews
    for ($i = 0; $i < count($reviews); $i++) {
        $passengerId = $reviews[$i]['id_passenger'];
        $reviews[$i]['passenger_pseudo'] = ($passengerId && isset($users[$passengerId]))
            ? $users[$passengerId]
            : 'Passager inconnu';
    }

    return $reviews;
}

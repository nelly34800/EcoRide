<?php

function getUsersByRole($pdo, $roles) {
    // Si un entier est passé, on le transforme en tableau
    if (!is_array($roles)) {
        $roles = [$roles];
    }

    // Préparer les placeholders pour PDO
    $placeholders = implode(',', array_fill(0, count($roles), '?'));

    $sql = "SELECT * FROM users WHERE role_id IN ($placeholders)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($roles);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function suspendUser(PDO $pdo, int $id): bool {
    $sql = "UPDATE users SET status = 'suspended' WHERE id = :id";
    $query = $pdo->prepare($sql);
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    return $query->execute();
}

function activateUser(PDO $pdo, int $id): bool {
    $sql = "UPDATE users SET status = 'active' WHERE id = :id";
    $query = $pdo->prepare($sql);
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    return $query->execute();
}

function deleteUser(PDO $pdo, int $id): bool 
{
    $sql = "DELETE FROM users WHERE id = :id";
    $query = $pdo-> prepare ($sql);
     $query->bindParam(':id', $id, PDO::PARAM_INT);
     return $query->execute();
}

function getJourneyByDay(PDO $pdo): array {
    $sql = "SELECT DATE(date) as jour, COUNT(*) as nb_covoiturages
            FROM journeys
            GROUP BY DATE(date)
            ORDER BY DATE(date)";
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

function getCreditByDay(PDO $pdo): array {
    $sql = "SELECT DATE(created_at) as jour, SUM(amount) as credits_gagnes
            FROM commissions
            GROUP BY DATE(created_at)
            ORDER BY DATE(created_at)";
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

function getTotalCredits(PDO $pdo): int {
    return (int)$pdo->query("SELECT SUM(amount) FROM commissions")->fetchColumn();
}

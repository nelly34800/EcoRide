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
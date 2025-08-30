<?php

function registerProblem(PDO $pdo, string $descriptive, int $id_journey, int $id_user_reporter)
{

    $sql = "INSERT INTO problems (descriptive, id_journey, id_user_reporter) VALUES (:descriptive, :id_journey, :id_user_reporter)";

    $query = $pdo->prepare($sql);
    $query->bindParam(':descriptive', $descriptive);
    $query->bindParam(':id_journey', $id_journey,  PDO::PARAM_INT);
    $query->bindParam(':id_user_reporter', $id_user_reporter,  PDO::PARAM_INT);
    return $query->execute();
}

function getProblemsByStatus(PDO $pdo, string $status): array 
{
    $sql = "SELECT 
                p.id AS problem_id, 
                p.descriptive, 
                p.status, 
                p.created_at,
                j.id AS journey_id, 
                j.place_departure, 
                j.place_arrival, 
                j.date,
                driver.id AS driver_id, 
                driver.pseudo AS driver_pseudo, 
                driver.email AS driver_email,
                reporter.id AS reporter_id, 
                reporter.pseudo AS reporter_pseudo, 
                reporter.email AS reporter_email
            FROM problems p
            JOIN journeys j ON p.id_journey = j.id
            JOIN users driver ON j.user_id = driver.id
            JOIN users reporter ON p.id_user_reporter = reporter.id
            WHERE p.status = :status
            ORDER BY p.created_at DESC";

    $query = $pdo->prepare($sql);
    $query->bindValue(":status", $status, PDO::PARAM_STR);
    $query->execute();

    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function updateStatusProblem(PDO $pdo, int $problem_id, string $new_status)
{
$sql = "UPDATE problems SET status = :status WHERE id = :id";
        $query = $pdo->prepare($sql);
        $query->bindValue(":status", $new_status, PDO::PARAM_STR);
        $query->bindValue(":id", $problem_id, PDO::PARAM_INT);
        return $query->execute();
    }
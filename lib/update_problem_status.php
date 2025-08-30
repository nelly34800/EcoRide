<?php
require_once "pdo.php";
require_once "report_problem.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $problem_id = $_POST["problem_id"] ?? null;
    $new_status = $_POST["new_status"] ?? null;

    if ($problem_id && $new_status) {
        updateStatusProblem($pdo, (int)$problem_id, $new_status);
    }
}

// On revient sur la page employé
header("Location: ../employe.php");
exit;

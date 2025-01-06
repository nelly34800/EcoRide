<?php
function getJourneys(): array
{
    return [
        ["pseudo" => "blueSnack26", "image" => "se1.png", "h_depart" => "10h", "h_arrivee" => "17h", "voyage_eco" => "good.png", "place_dispo" => "2", "tarif" => "10 crédits"],
        ["pseudo" => "koalaCute34", "image" => "se2.png", "h_depart" => "8h30", "h_arrivee" => "16h", "voyage_eco" => "bad.png", "place_dispo" => "1", "tarif" => "8 crédits"],
        ["pseudo" => "catRina28", "image" => "se3.png", "h_depart" => "9h", "h_arrivee" => "17h", "voyage_eco" => "good.png", "place_dispo" => "2", "tarif" => "12 crédits"],
        ["pseudo" => "foxBro146", "image" => "se4.png", "h_depart" => "10", "h_arrivee" => "17h", "voyage_eco" => "bad.png", "place_dispo" => "2", "tarif" => "10 crédits"],
    ];
}
function getJourneyById(int $id): array
{
    $journeys = getJourneys();
    return $journeys[$id];
}

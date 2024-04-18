<?php
require '../BD/bd.php';
$bdd = getBDpays();

if (isset($_GET['country_id'])) {
    $countryCode = $_GET['country_id'];

    $stmt = $bdd->prepare('SELECT city_name FROM city WHERE ville_country_id = :countryCode ORDER BY city_name ASC');

    $stmt->bindParam(':countryCode', $countryCode, PDO::PARAM_STR);
    $stmt->execute();

    $villes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($villes);
} else {
    echo json_encode([]);
}

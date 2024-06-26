<?php
header("Content-Type: application/json");
require "../../BD/bd.php";

$param = $_POST['param'];

if (!empty($_POST['nameYear']) && !empty($_POST['param'])) {
    $bdd = getBD();
    $query = $bdd->prepare("SELECT name, year, month, day, hour, $param, lat, `long` FROM corrected_hurricane_data WHERE nameYear = :nameYear");//on récupère toutes les données nécessaires à l'étude
    $query->bindParam(':nameYear', $_POST["nameYear"], PDO::PARAM_STR);
    $query->execute();
    $sortie = $query->fetchAll(PDO::FETCH_ASSOC); // Utilisation de fetchAll pour récupérer toutes les lignes
    echo json_encode(['success' => true, 'data' => $sortie]); // On inclut les données dans le JSON renvoyé
    exit();
} else {
    // Les champs n'ont pas été remplis
    echo json_encode(['success' => false, 'message' => 'Veuillez remplir tous les champs.']);
    exit();
}
?>

<?php
header("Content-Type: application/json");
require "../../BD/bd.php";

$param1 = $_POST['param1'];
$param2 = $_POST['param2'];

if (!empty($_POST['name']) && !empty($_POST['param1']) && !empty($_POST['param2'])) {
    $bdd = getBD();
    $query = $bdd->prepare("SELECT name, year, month, day, hour, $param1, $param2 FROM corrected_hurricane_data WHERE name = :name");
    $query->bindParam(':name', $_POST["name"], PDO::PARAM_STR);
    $query->execute();
    $sortie = $query->fetchAll(PDO::FETCH_ASSOC); // Utilisation de fetchAll pour récupérer toutes les lignes
    echo json_encode(['success' => true, 'data' => $sortie]); // Inclure les données dans le JSON renvoyé
    exit();
} else {
    // Les champs n'ont pas été remplis
    echo json_encode(['success' => false, 'message' => 'Veuillez remplir tous les champs.']);
    exit();
}
?>

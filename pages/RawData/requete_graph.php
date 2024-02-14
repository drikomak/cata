<?php
header("Content-Type: application/json");
require "../../BD/bd.php";
$param=$_POST['param'];
if (!empty($_POST['name']) && !empty($_POST['param'])) {
    $bdd = getBD();
    $query = $bdd->prepare("SELECT :name, year, month, day, hour, $param FROM corrected_hurricane_data WHERE name = :name");
    $query->bindParam(':name', $_POST["name"], PDO::PARAM_STR);
    $query->execute();
    $utilisateur = $query->fetch();
        
    echo json_encode(['success'=>true, 'message'=>'Connexion réussie, Redirection...']);
    exit();
    
}   else {
    // Les champs n'ont pas été remplis
    echo json_encode(['success'=>false, 'message'=>'Veuillez remplir tous les champs.']);
    exit();
}

?>
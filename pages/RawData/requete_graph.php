<?php
header("Content-Type: application/json");
require "../../BD/bd.php";

if (!empty($_POST['noms']) && !empty($_POST['param'])) {
    $bdd = getBD();
    $query = $bdd->prepare("SELECT  FROM clients WHERE mail = :mail");
    $query->bindParam(':mail', $_POST["mail"], PDO::PARAM_STR);
    $query->execute();
    $utilisateur = $query->fetch();
    if ($utilisateur && password_verify($_POST['mdp'], $utilisateur['mdp'])) {
        $_SESSION['nom'] = $utilisateur['nom'];
        $_SESSION['prenom'] = $utilisateur['prenom'];
        $_SESSION['numero']= $utilisateur['numero'];
        $_SESSION['adresse'] = $utilisateur['adresse'];
        $_SESSION['mail'] = $utilisateur['mail'];
        $_SESSION['id_client'] = $utilisateur['id_client'];
        $_SESSION['id_stripe'] = $utilisateur['id_stripe'];
        
        echo json_encode(['success'=>true, 'message'=>'Connexion réussie, Redirection...']);
        exit();
    } else {
        // Identifiants incorrects
        echo json_encode(['success'=>false, 'message'=>'Identifiants incorrects.']);
        exit();
    }
} else {
    // Les champs n'ont pas été remplis
    echo json_encode(['success'=>false, 'message'=>'Veuillez remplir tous les champs.']);
    exit();
}

?>
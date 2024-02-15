<?php

session_start();
include '../../BD/bd.php';
$bdd = getBD();

if (!isset($_SESSION['user'])) {
    echo "erreur: connexion non établie";
    exit;
}

$user = $_SESSION['user'];

if (isset($_POST['field']) && isset($_POST['value'])) {
    $field = $_POST['field'];
    $value = $_POST['value'];
    echo $field;

    switch ($field) {
        case 'nom':
            $user['nom'] = $value;
            break;
        case 'prenom':
            $user['prenom'] = $value;
            break;
        case 'adresse':
            $user['adresse'] = $value;
            break;
        case 'mail':
            $user['mail'] = $value;
            break;
        default:
            echo "error: invalid_field";
            exit;
    }


    $allowedFields = ['nom', 'prenom', 'adresse', 'mail'];

    if (!in_array($field, $allowedFields)) {
        echo "error: invalid_field";
        exit;
    }
    
    //user_id marche meme si pas initialisé comme ca, jsais pas pk, a voir plus tard
    $stmt = $bdd->prepare("UPDATE user SET $field = :value WHERE id = :user_id");
    $stmt->bindParam(':value', $value);
    $stmt->bindParam(':user_id', $user['id']);

    if ($stmt->execute()) {
        $_SESSION['user'] = $user;

        echo "succes";
        
    } else {
        echo "erreur: " . implode(" ", $stmt->errorInfo());
    }
} else {
    echo "erreur: missing_parameters";
}

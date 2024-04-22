<?php

try {
    $errors = []; // Tableau pour stocker les messages d'erreur individuels

    // Validation de chaque champ
    if (empty($_POST['n'])) {
        $errors['n'] = "Le champ 'Nom' est requis.";
    }
    if (empty($_POST['p'])) {
        $errors['p'] = "Le champ 'Prénom' est requis.";
    }
    if (empty($_POST['adr'])) {
        $errors['adr'] = "L'adresse est requise.";
    }
    if (empty($_POST['Pays'])) {
        $errors['Pays'] = "Le choix du pays est requis.";
    }
    if (empty($_POST['Ville'])) {
        $errors['Ville'] = "Le choix de la ville est requis.";
    }
    if (empty($_POST['mail'])) {
        $errors['mail'] = "L'adresse email est requise.";
    }
    if (empty($_POST['mdp1'])) {
        $errors['mdp1'] = "Le mot de passe est requis.";
    }
    if (empty($_POST['mdp2'])) {
        $errors['mdp2'] = "La confirmation du mot de passe est requise.";
    }
    if ($_POST['mdp1'] != $_POST['mdp2']) {
        $errors['mdp'] = "Les mots de passe ne correspondent pas.";
    }

    // Vérifier s'il y a des erreurs accumulées dans le tableau
    if (!empty($errors)) {
        $response = array("status" => "error", "message" => "Des erreurs ont été trouvées dans le formulaire.", "errors" => $errors);
    } else {
        include '../../BD/bd.php';
        $bdd = getBD();
        $stmt = $bdd->prepare("INSERT INTO user (nom, prenom, adresse, Pays, Ville, mail, mdp) VALUES (:nom, :prenom, :adresse, :Pays, :Ville, :mail, :mdp)");
        $stmt->bindValue(':nom', $_POST['n']);
        $stmt->bindValue(':prenom', $_POST['p']);
        $stmt->bindValue(':adresse', $_POST['adr']);
        $stmt->bindValue(':Pays', $_POST['Pays']);
        $stmt->bindValue(':Ville', $_POST['Ville']);
        $stmt->bindValue(':mail', $_POST['mail']);
        $hashedPassword = password_hash($_POST['mdp1'], PASSWORD_DEFAULT);
        $stmt->bindValue(':mdp', $hashedPassword);

        $result = $stmt->execute();
        if ($result) {
            $response = array("status" => "success", "message" => "Compte créé avec succès!");
        } else {
            $response = array("status" => "error", "message" => "Une erreur s'est produite lors de l'enregistrement.");
        }
    }
} catch (Exception $e) {
    $response = array("status" => "error", "message" => "Erreur technique lors de l'enregistrement.", "errorDetail" => $e->getMessage());
}

echo json_encode($response);
?>

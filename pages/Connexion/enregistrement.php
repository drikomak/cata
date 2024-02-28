<?php

try {
    if (empty($_POST['n']) || empty($_POST['p']) || empty($_POST['adr']) || empty($_POST['mail']) || empty($_POST['mdp1']) || empty($_POST['mdp2']) || ($_POST['mdp1'] != $_POST['mdp2'])) {
        $response = array("status" => "error", "message" => "Veuillez remplir tous les champs du formulaire correctement.");
    } else {
        include '../../BD/bd.php';
        $bdd = getBD();

        $stmt = $bdd->prepare("INSERT INTO user (nom, prenom, adresse, mail, mdp) VALUES (:nom, :prenom, :adresse, :mail, :mdp)");

        $stmt->bindValue(':nom', $_POST['n']);
        $stmt->bindValue(':prenom', $_POST['p']);
        $stmt->bindValue(':adresse', $_POST['adr']);
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
    error_log("Erreur dans enregistrement.php : " . $e->getMessage());
    $response = array("status" => "error", "message" => "Une erreur s'est produite. Veuillez réessayer.");
}

// Retourne la réponse au format JSON
echo json_encode($response);
?>

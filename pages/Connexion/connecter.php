<?php
session_start();
include '../../BD/bd.php';

$bdd = getBD();

if (isset($_POST['email']) && isset($_POST['motdepasse'])) {
    $email = $_POST['email'];
    $motdepasse = $_POST['motdepasse'];

    $requete = $bdd->prepare("SELECT * FROM user WHERE mail = ?");
    $requete->execute([$email]);

    if ($requete) {
        $resultat = $requete->fetch();

        if ($resultat) {
            if (password_verify($motdepasse, $resultat['mdp'])) {
                $user = $resultat;

                $_SESSION['user'] = $user;

                echo json_encode(array('success' => true));
            } else {
                echo json_encode(array('success' => false, 'message' => 'Adresse e-mail ou mot de passe incorrects.'));
            }
        } else {
            echo json_encode(array('success' => false, 'message' => 'Adresse e-mail ou mot de passe incorrects.'));
        }
    } else {
        echo json_encode(array('success' => false, 'message' => 'Une erreur s\'est produite lors de la connexion.'));
    }
} else {
    echo json_encode(array('success' => false, 'message' => 'Les champs email et motdepasse sont requis.'));
}
?>

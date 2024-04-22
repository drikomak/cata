<?php
// Démarrage de la session
session_start();

// Vérification que les données nécessaires sont reçues
if (isset($_POST['email']) && isset($_POST['motdepasse'])) {
    $email = $_POST['email'];
    $password = $_POST['motdepasse'];

    // Inclusion du script de connexion à la base de données
    include '../../BD/bd.php';  // Assurez-vous que le chemin d'accès est correct
    $bdd = getBD();

    // Préparation de la requête pour retrouver l'utilisateur par son email
    $stmt = $bdd->prepare("SELECT id, nom, prenom, Pays, Ville, adresse, mdp FROM user WHERE mail = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérification du mot de passe
    if ($user && password_verify($password, $user['mdp'])) {
        // Si les identifiants sont corrects, stockage des informations de l'utilisateur en session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['prenom'] = $user['prenom'];
        $_SESSION['Pays'] = $user['Pays'];
        $_SESSION['Ville'] = $user['Ville'];
        $_SESSION['adresse'] = $user['adresse'];
        $_SESSION['email'] = $email;  // Email est directement pris de l'input pour éviter de manipuler inutilement
        $_SESSION['logged_in'] = true;

        // Envoi d'une réponse de succès en JSON
        echo json_encode(['status' => 'success']);
    } else {
        // Si les identifiants sont incorrects, envoi d'une réponse d'erreur
        echo json_encode(['status' => 'error', 'message' => 'Identifiants incorrects']);
    }
} else {
    // Si les données nécessaires ne sont pas reçues, envoi d'une réponse d'erreur
    echo json_encode(['status' => 'error', 'message' => 'Email et mot de passe sont requis.']);
}

// Fin du script
exit;
?>

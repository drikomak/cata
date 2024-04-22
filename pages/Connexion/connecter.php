<?php

session_start();


if (isset($_POST['email']) && isset($_POST['motdepasse'])) {
    $email = $_POST['email'];
    $password = $_POST['motdepasse'];

    
    include '../../BD/bd.php';  
    $bdd = getBD();

    
    $stmt = $bdd->prepare("SELECT id, nom, prenom, Pays, Ville, adresse, mdp FROM user WHERE mail = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    
    if ($user && password_verify($password, $user['mdp'])) {
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['prenom'] = $user['prenom'];
        $_SESSION['Pays'] = $user['Pays'];
        $_SESSION['Ville'] = $user['Ville'];
        $_SESSION['adresse'] = $user['adresse'];
        $_SESSION['email'] = $email;  
        $_SESSION['logged_in'] = true;

        
        echo json_encode(['status' => 'success', 'redirect' => 'profile.php']);

    } else {
        
        echo json_encode(['status' => 'error', 'message' => 'Identifiants incorrects']);
    }
} else {
   
    echo json_encode(['status' => 'error', 'message' => 'Email et mot de passe sont requis.']);
}


exit;
?>

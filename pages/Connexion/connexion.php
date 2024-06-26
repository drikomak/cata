<!DOCTYPE html>
<?php
session_start();  // Démarrage de la session PHP
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: profile.php');  // Rediriger vers la page de profil si déjà connecté
    exit;  // Arrêter l'exécution du script pour éviter le chargement de la page de connexion
}
?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link href="style_connexion.css" rel="stylesheet"/>
    <title>Connexion et Inscription</title>
</head>
<body id="authPage">
    <header>
        <nav class="nav-bar">
            <a href="../../main.php"><img class="logo" src="../../images/logo3.png" alt="Logo"></a>
            <div class="nav-links">
                <ul>
                    <li><a href="../Algorithme/Algorithme.php">Algorithm</a></li>
                    <li><a href="../RawData/RawData.php">Rawdata</a></li>
                    <li><a href="../Discover/Discover.php">Discover</a></li>
                    <li><a href="connexion.php">Connexion</a></li>
                </ul>
            </div>
        </nav>
    </header>
    <div class="container" id="authContainer">
    <div class="form-container login-form">
        <h1>Déjà inscrit ?</h1>
        <form id="loginForm" method="post">
            <input type="email" id="email" name="email" placeholder="Adresse e-mail" required>
            <input type="password" id="motdepasse" name="motdepasse" placeholder="Mot de passe" required>
            <button type="submit" id="loginBtn">Se connecter</button>
        </form>
    </div>
    <div class="form-container signup-form">
        <h1>Nouveau sur OuraGuessr ?</h1>
        <form id="signupForm" method="post" autocomplete="off">
            <input type="text" name="n" placeholder="Nom" required>
            <input type="text" name="p" placeholder="Prénom" required>
            <input type="text" name="adr" placeholder="Adresse" required>
            <select id="countrySelect" name="Pays" required>
                <option value="" disabled selected>Sélectionnez un pays</option>
            </select>
            <select id="citySelect" name="Ville" required>
                <option value="" disabled selected>Sélectionnez une ville</option>
            </select>
            <input type="email" name="mail" placeholder="Email" required>
            <input type="password" name="mdp1" placeholder="Mot de passe" required>
            <input type="password" name="mdp2" placeholder="Confirmez le mot de passe" required>
            <button type="submit" id="envoieBtn">Envoyer</button>
        </form>
    </div>
    <script src="weather.js"></script>
    <script>
    $(document).ready(function() {
        $('#loginForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: 'connecter.php',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.status === "success") {
                        window.location.href = 'profile.php';
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert("Une erreur s'est produite lors de la connexion. Veuillez réessayer.");
                }
            });
        });

        $('#signupForm').submit(function(e) {
    e.preventDefault();
    var formData = $(this).serialize();
    $.ajax({
        type: "POST",
        url: "enregistrement.php",
        data: formData,
        dataType: "json",
        success: function(response) {
            if (response.status === "success") {
                window.location.href = response.redirect;  // Redirigez l'utilisateur vers son profil
            } else {
                var errors = response.errors;
                var errorMessage = "Veuillez corriger les erreurs suivantes :\n\n";
                $.each(errors, function(field, message) {
                    errorMessage += message + "\n";
                });
                alert(errorMessage);
            }
        },
        error: function(xhr) {
            alert("Erreur réseau : " + xhr.status + " " + xhr.statusText);
        }
    });
});
    });
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
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
            <h1>Connexion</h1>
            <form>
                <input type="email" id="email" name="email" placeholder="Adresse e-mail" required><br>
                <input type="password" id="motdepasse" name="motdepasse" placeholder="Mot de passe" required><br>
                <button type="submit" id="loginBtn">Se connecter</button>
            </form>
        </div>
        <div class="form-container signup-form">
            <h1>Enregistrement utilisateur</h1>
            <form id="signupForm" autocomplete="off">
                <input type="text" name="n" placeholder="Nom" required>
                <input type="text" name="p" placeholder="Prénom" required>
                <input type="text" name="adr" placeholder="Adresse" required>
                <input type="text" name="mail" placeholder="Email" required>
                <input type="password" name="mdp1" placeholder="Mot de passe" required>
                <input type="password" name="mdp2" placeholder="Confirmez le mot de passe" required>
                <button type="button" id="envoieBtn">Envoyer</button>
            </form>
        </div>
    </div>
</body>
</html>

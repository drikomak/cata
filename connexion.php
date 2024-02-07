<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&display=swap" rel="stylesheet">
    <link href="styles/styles.css" rel="stylesheet" />
    <title>Connexion</title>
</head>
<body>

    <header>

    <nav class="nav-bar">
    <a href="main.php"><img class="logo" src="images/logo3.png"></a>
        <div class="nav-links">
            <ul>
                <li><a href="pages/page1.php">Algorithm</a></li>
                <li><a href="pages/page2.php">Rawdata</a></li>
                <li><a href="pages/page3.php">Discover</a></li>
                <li><a href="connexion.php">Connexion</a></li>
            </ul>
        </div>
    </nav>

    </header>
    <h1>Connexion</h1>
    <form>
        <label for="email">Adresse e-mail :</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="motdepasse">Mot de passe :</label>
        <input type="password" id="motdepasse" name="motdepasse" required><br><br>

        <input type="submit" value="Se connecter">
    </form>
    <p><a href="nouveau.php">Créer un compte</a></p>
</body>
</html>
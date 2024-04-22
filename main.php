<?php
    session_start(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200&display=swap" rel="stylesheet">
    <link href="styles/styles.css" rel="stylesheet" />
    <title>OuraGuessr</title>
</head>

<body>

    <header>


    <nav class="nav-bar">
    <a href="main.php"><img class="logo" src="images/logo3.png"></a>
        <div class="nav-links">
            <ul>
            <?php
            if (isset($_SESSION['user'])) {
                echo '<li><a href="pages/Algorithme/Algorithme.php">Algorithm</a></li>';
                echo '<li><a href="pages/RawData/RawData/php">Rawdata</a></li>';
                echo '<li><a href="pages/Discover/Discover.php">Discover</a></li>';
                echo '<li><a href="pages/Connexion/connexion.php"><img src="images/profil.png" height = 30px></a></li>'; }
                else {
                echo '<li><a href="pages/Algorithme/Algorithme.php">Algorithm</a></li>';
                echo '<li><a href="pages/RawData/RawData.php">Rawdata</a></li>';
                echo '<li><a href="pages/Discover/Discover.php">Discover</a></li>';
                echo '<li><a href="pages/Connexion/connexion.php"><img src="images/profil.png" height = 30px></a></li>'; 
            }
            ?>
            </ul>
        </div>
    </nav>

    </header>

    <div class="main-page">
        
        <h1>Ourguessr : Restez informé sans stress</h1>
        <p>
        OuraGuessr, vous offre une expérience intuitive pour anticiper les ouragans à venir. 
        En combinant les données de la NASA et des agences météorologiques locales, notre système convivial vous permet de comprendre facilement les prévisions essentielles.
        </p>
        <?php
        if (isset($_SESSION['user'])) {
            echo '<a href="pages/Discover/Discover.php"><button id="discoverButton">Discover</button></a>';
        } else {
            echo '<a href="pages/Connexion/connexion.php"><button id="createButton">Créer un compte</button></a>';
        }
        ?>
    </div>
    
    <img class="image-main" src="images/ouragan.jpg">

    <footer>
        <div class="footer">
        </div>
    </body>
</html>

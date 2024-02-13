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
                echo '<li><a href="pages/Connexion/profile.php"><img src="images/profil.png" height = 30px></a></li>'; }
                else {
                echo '<li><a href="pages/Algorithme/Algorithme.php">Algorithm</a></li>';
                echo '<li><a href="pages/RawData/RawData.php">Rawdata</a></li>';
                echo '<li><a href="pages/Discover/Discover.php">Discover</a></li>';
                echo '<li><a href="pages/Connexion/profile.php"><img src="images/profil.png" height = 30px></a></li>'; 
            }
            ?>
            </ul>
        </div>
    </nav>

    </header>

    <div class="main-page">
        
        <h1>The most user friendly Ouragan catcher</h1>
        <p>Based on multiple sources of data including Nasa and local meteorological agencies,
            we developed a state of the art algorithm to predict key parameters of the next Ouragans.
        </p>
        <?php
        if (isset($_SESSION['user'])) {
            echo '<a href="pages/Discover/Discover.php"><button id="discoverButton">Discover</button></a>';
        } else {
            echo '<a href="pages/Connexion/nouveau.php"><button id="createButton">Créer un compte</button></a>';
        }
        ?>
    </div>
    
    <img class="image-main" src="images/ouragan.jpg">

    <footer>
        <div class="footer">
            <p>© 2021 OuraGuessr</p>
        </div>
    </body>
</html>

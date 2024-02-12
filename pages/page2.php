<!DOCTYPE html>
<html lang="en">
<head>
    <?php require('../bd.php'); 
    // Appel de la fonction pour obtenir la connexion à la base de données
    $bdd = getBD();

    // Exécution de la requête SQL pour récupérer les noms
    $requete = $bdd->prepare("SELECT DISTINCT name FROM corrected_hurricane_data");
    $requete->execute();
    $noms= $requete->fetchAll(PDO::FETCH_COLUMN);
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&display=swap" rel="stylesheet">
    <link href="../styles/styles.css" rel="stylesheet">
    <title>Raw Data</title>
</head>
<body>
    <header>

    <nav class="nav-bar">
    <a href="../main.php"><img class="logo" src="../images/logo3.png"></a>
        <div class="nav-links">
            <ul>
                <li><a href="../pages/page1.php">Algorithm</a></li>
                <li><a href="../pages/page2.php">Rawdata</a></li>
                <li><a href="../pages/page3.php">Discover</a></li>
                <li><a href="../profile.php"><img src="../images/profil.png" height = 30px></a></li>
            </ul>
        </div>
    </nav>

    </header>
    <h1><span class="txt">DONNEES BRUTES</span></h1>

    <h2><span class="txt">Statistique : Des données qui concernent l'Amérique du Nord</span></h2>
    <div class="photo" ><img class="imgp2" src="../images/north_america.jpg" alt="north america map"></div>
    <p><span class=txt>Choisissez l'ouragan et le paramètre que vous voulez étudier</span></p>
    <h3><span class=txt>Tableau des ouragans</span></h3>

    
    <select name="noms" class="txt">
    <?php
    foreach($noms as $nom){
        echo "<option value='".$nom."'>".$nom."</option>";
    }
    ?>
    </select>


<h2><span class=txt>Temps réel : Utilisation d'un API</span></h2>
<div class=photo><img class="imgp2" src="../images/api.webp" alt=""> <img class="imgp2" src="../images/dataflow.webp" alt=""></div>
<p><span class=txt>Les données météorologiques sont récoltées en temps réel grâce à l'API OpenWeatherMap. Cet API nous permet de récupérer des données météorologiques sur n'importe quelle ville du monde. Ces données sont ensuite utilisées pour être analysées par notre modèle de prédiction et afficher un résultat.</span></p>
 
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&display=swap" rel="stylesheet">
    <link href="../styles/styles.css" rel="stylesheet" />
    <title>Données passées</title>
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

    <?php
    require '../bdd.php';

    $bdd = getBD();

    $rep = $bdd->query('select name from corrected_hurricane_data');

     while ($ligne = $rep ->fetch()) {
        echo $ligne['name']."<br />\n";
    }
    ?>

        <table>
            <caption>Ouragans :</caption>

            <thead> 
                <tr>
                <th>Nom</th>
                <th>année</th>
                <th>mois</th>
                <th>jour</th>
                <th>heure</th>
                </tr>
            </thead>
            <?php

             while ($ligne = $rep->fetch()) {
                 echo "<tr>";
                 echo "<td><a href=\"pages/page1.php?id_oura=" . $ligne['name'] . "\">" . $ligne['name'] . "</a></td>";
                 echo "<td>" . $ligne['year'] . "</td>";
                 echo "<td>" . $ligne['month'] . "</td>";
                 echo "<td>" . $ligne['day'] . "</td>";
                 echo "<td><img src='" . $ligne['hour'] . "'></td>";
                 echo "</tr>";
             };

             $rep->closeCursor();

            ?>
        </table>


    <div class = "main">
        <h1>Données passées</h1>
    </div>
    
</body>
</html>
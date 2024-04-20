<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&display=swap" rel="stylesheet">
    <link href="../../styles/styles.css" rel="stylesheet" />
    <title>Discover</title>
</head>
<body class="p3">
    <header> 

        <nav class="nav-bar">
        <a href="../../main.php"><img class="logo" src="../../images/logo3.png"></a>
            <div class="nav-links">
                <ul>
                    <li><a href="../Algorithme/Algorithme.php">Algorithm</a></li>
                    <li><a href="../RawData/RawData.php">Rawdata</a></li>
                    <li><a href="Discover.php">Discover</a></li>
                    <li><a href="../Connexion/profile.php"><img src="../../images/profil.png" height = 30px></a></li>
                </ul>
            </div>
        </nav>
    </header>

    <h1>Discover</h1>

    <?php

// Clé d'API du New York Times
$apiKey = 'OALu4obq4iv1eM5NA0eUkAshpnni4QJA';

// Nombre d'articles à afficher par page
$perPage = 14;

// Nombre total d'articles à récupérer
$totalArticles = 15; // Par exemple, récupérer 30 articles au total

// Termes de recherche pour les ouragans et les catastrophes naturelles
$searchTerms = 'hurricanes,disaster,natural disaster,climate, climate change';

// Boucle pour récupérer les articles par page
for ($page = 0; $page * $perPage < $totalArticles; $page++) {
    // URL de l'API du New York Times pour les articles sur les ouragans et les catastrophes naturelles,
    // triés par date de publication, avec le nombre spécifié d'articles par page et la pagination
    $url = 'https://api.nytimes.com/svc/search/v2/articlesearch.json?q=' . urlencode($searchTerms) . '&api-key=' . $apiKey . '&sort=newest&page-size=' . $perPage . '&page=' . ($page + 1);

    // Effectuer la requête à l'API
    $response = file_get_contents($url);

    // Vérifier si la requête a réussi
    if ($response === false) {
        die('Erreur lors de la récupération des données depuis l\'API.');
    }

    // Convertir la réponse JSON en tableau associatif
    $data = json_decode($response, true);

    // Vérifier si des articles ont été trouvés
    if ($data['status'] !== 'OK') {
        die('Aucun article trouvé.');
    }

    // Afficher les articles sur la page en mosaïque
    echo '<div class="mosaic">';
    foreach ($data['response']['docs'] as $article) {
        // Vérifier s'il y a une image associée à l'article
        if (isset($article['multimedia'][0]['url'])) {
            // Construire l'URL de l'image à partir de l'URL de base du New York Times
            $imageUrl = 'https://www.nytimes.com/' . $article['multimedia'][0]['url'];
            // Afficher l'image avec une classe pour la stylisation
            echo '<div class="article">';
            echo '<img class="article-image" src="' . $imageUrl . '" alt="Image article">';
            // Ajouter un lien autour du titre pour ouvrir l'article dans un nouvel onglet
            echo '<h2 class="article-title"><a href="' . $article['web_url'] . '" target="_blank">' . $article['headline']['main'] . '</a></h2>';
            echo '</div>';
        }
    }
    echo '</div>';
}

?>



<div class="premier_par">
        <img src="../../images/ouragan_photo_1.jpg" class="img_p3">
        <div>
        <h1>Un ouragan, qu'est ce que c'est ?</h1>
        <p>Les ouragans sont des phénomènes météorologiques extrêmes qui peuvent causer des dégâts considérables. 
        Ils se forment généralement dans les zones tropicales et sont caractérisés par des vents violents et des pluies torrentielles. 
        Dans cette présentation, nous allons explorer les différents types d'ouragans, leur formation et leur impact sur les populations et les infrastructures. 
        Nous allons également discuter des mesures de prévention et de sécurité à prendre en compte en cas d'ouragan.</p>
        </div>  
    </div>

    <div class="premier_par" >
        <div>
        <h1>Les conséquences</h1>
        <p>Les dégâts causés par un ouragan sont souvent dévastateurs, laissant derrière eux un paysage de destruction et de désolation. 
            Les vents violents, accompagnés de pluies torrentielles et de vagues déferlantes, ont le pouvoir de détruire des infrastructures entières, notamment des habitations, des routes et des ponts. 
            Les inondations provoquées par les fortes précipitations peuvent submerger des zones étendues, entraînant des évacuations massives et des pertes humaines. Les lignes électriques sont souvent arrachées, 
            laissant les communautés sans électricité pendant des jours, voire des semaines. Les répercussions économiques sont considérables, avec des coûts de reconstruction astronomiques. 
            Les ouragans peuvent également avoir des conséquences à long terme sur l'environnement, avec la destruction d'écosystèmes fragiles tels que les mangroves et les récifs coralliens. En fin de compte, 
            les ouragans sont des phénomènes naturels redoutables qui mettent à l'épreuve la résilience des communautés touchées, nécessitant une coordination et une aide internationales pour la reconstruction et la récupération.</p>
        </div>  
        <img src="../../images/ouragan_photo_2.jpg" class="img_p3">
    </div>
    


</body>
</html>
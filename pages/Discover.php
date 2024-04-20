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

    <div class="articles-container">
        <?php
        $api_key = 'ac80fed48965452190a7dccad0bff3ab';
        $sources_to_include = $sources_to_include = 'bbc-news,cnn,reuter,new-york-times'; // Liste des sources à inclure, séparées par des virgules        ; // Liste des sources à inclure, séparées par des virgules
        $keywords = '"natural disaster"'; // Mots-clés pour filtrer les articles
        $url = 'https://newsapi.org/v2/everything?q=' . urlencode($keywords) . '&sources=' . $sources_to_include . '&language=en&sortBy=publishedAt&apiKey=' . $api_key . '&pageSize=10';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3');
        $response = curl_exec($ch);
        curl_close($ch);

        $articles = json_decode($response, true);

        if ($articles && isset($articles['articles']) && !empty($articles['articles'])) {
            foreach ($articles['articles'] as $idx => $article) {
                // Vérifier si l'article a un titre, une URL, une image et s'il ne contient pas le contenu indésirable
                if (isset($article['title']) && isset($article['url']) && isset($article['urlToImage']) && strpos($article['title'], '[Removed]') === false) {
                    echo "<div class='article'>";
                    echo "<h3>" . " <a href=\"javascript:void(0);\" onclick=\"openArticle('".$article['url']."','".htmlspecialchars($article['title'], ENT_QUOTES)."');\">" . $article['title'] . "</a> (" . $article['source']['name'] . ")</h3>";
                    echo "<img src=\"" . $article['urlToImage'] . "\" alt=\"Article Image\">";
                    echo "<a href=\"" . $article['url'] . "\" target=\"_blank\"><button class=\"article-button\">Consulter l'article</button></a>";
                    echo "</div>";
                }
            }

            
        } else {
            echo "No articles found.";
        }
        ?>
    </div>

    <div id="article-frame">
        <span class="close-btn" onclick="closeArticle()">&times;</span>
        <iframe id="article-iframe" width="100%" height="100%" frameborder="0"></iframe>
    </div>

    <script>
        // Fonction pour ouvrir l'article dans l'iframe
        function openArticle(url, title) {
            document.getElementById('article-iframe').src = 'article_loader.php?url=' + encodeURIComponent(url);
            document.getElementById('article-frame').style.display = 'block';
            document.body.style.overflow = 'hidden'; 
            document.getElementById('article-frame').focus(); // clavier uniquement
            document.getElementById('article-frame').setAttribute('aria-label', 'Article: ' + title);
        }

        // Fonction pour fermer l'encadré
        function closeArticle() {
            document.getElementById('article-iframe').src = '';
            document.getElementById('article-frame').style.display = 'none';
            document.body.style.overflow = 'auto'; 
            document.getElementById('article-frame').removeAttribute('aria-label'); 
        }
    </script>
    
</body>
</html>

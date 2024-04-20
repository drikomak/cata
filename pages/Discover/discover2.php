<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&display=swap" rel="stylesheet">
    <style>
        /* Styles généraux */
    

        .nav-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-links ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        .nav-links ul li {
            display: inline;
            margin-right: 20px;
        }

        .nav-links ul li a {
            color: #fff;
            text-decoration: none;
        }

        .logo {
            height: 50px;
        }

        /* Styles spécifiques à la section "Discover" */
        .container h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        .articles-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-gap: 20px;
        }

        .article {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .article h3 {
            margin-top: 0;
        }

        .article img {
            width: 100%;
            height: 200px; /* Taille fixe pour toutes les images */
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
    </style>
    <title>Discover</title>
</head>
<body class="container">
    <header> 
        <nav class="nav-bar">
            <a href="../../main.php"><img class="logo" src="../../images/logo3.png"></a>
            <div class="nav-links">
                <ul>
                    <li><a href="../Algorithme/Algorithme.php">Algorithm</a></li>
                    <li><a href="../RawData/RawData.php">Rawdata</a></li>
                    <li><a href="Discover.php">Discover</a></li>
                    <li><a href="../Connexion/profile.php"><img src="../../images/profil.png" height="30px"></a></li>
                </ul>
            </div>
        </nav>
    </header>

    <h1>Discover</h1>

    <div class="articles-container">
        <?php
        $api_key = '66174e124357483a9f3cce4f3eec8281';
        $url = 'https://newsapi.org/v2/everything?q=weather&language=en&sortBy=publishedAt&apiKey=' . $api_key . '&pageSize=10';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3');
        $response = curl_exec($ch);
        curl_close($ch);

        $articles = json_decode($response, true);

        if ($articles && isset($articles['articles']) && !empty($articles['articles'])) {
            foreach ($articles['articles'] as $idx => $article) {
                echo "<div class='article'>";
                echo "<h3>" . ($idx + 1) . ". <a href=\"" . $article['url'] . "\">" . $article['title'] . "</a></h3>";
                if (isset($article['urlToImage'])) {
                    echo "<img src=\"" . $article['urlToImage'] . "\" alt=\"Article Image\">";
                }
                echo "</div>";
            }
        } else {
            echo "No articles found.";
        }
        ?>
    </div>

</body>
</html>

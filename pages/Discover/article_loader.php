<?php
if(isset($_GET['url'])) {
    // Récupérez l'URL de l'article
    $article_url = $_GET['url'];

    // Chargez le contenu de l'article à partir de l'URL
    $article_content = file_get_contents($article_url);

    echo $article_content;
} else {
    echo "Error: pas d'url";
}
?>

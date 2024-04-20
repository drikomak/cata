<?php
// Vérifiez si l'URL de l'article est passée en tant que paramètre
if(isset($_GET['url'])) {
    // Récupérez l'URL de l'article
    $article_url = $_GET['url'];

    // Chargez le contenu de l'article à partir de l'URL
    $article_content = file_get_contents($article_url);

    // Affichez le contenu de l'article
    echo $article_content;
} else {
    // Si l'URL de l'article n'est pas spécifiée, affichez un message d'erreur
    echo "Error: Article URL is not specified.";
}
?>

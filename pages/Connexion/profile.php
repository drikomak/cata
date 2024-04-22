<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: connexion.php');  // Rediriger vers la page de connexion si non connecté
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style_connexion.css">  <!-- Chemin vers votre CSS -->
    <title>Profil de l'utilisateur</title>
</head>
<body>
    <header>
        <nav class="nav-bar">
            <a href="../../main.php"><img class="logo" src="../../images/logo3.png" alt="Logo"></a>
            <div class="nav-links">
                <ul>
                    <li><a href="../Algorithme/Algorithme.php">Algorithm</a></li>
                    <li><a href="../RawData/RawData.php">Rawdata</a></li>
                    <li><a href="../Discover/Discover.php">Discover</a></li>
                    <li><a href="deconnexion.php">Déconnexion</a></li>
                </ul>
            </div>
        </nav>
    </header>
    <div class="container">
        <h1 class="h1-profile">Profil</h1>
        <table class="profile-table">
            <tr>
                <th>Nom</th>
                <td><?php echo htmlspecialchars($_SESSION['nom']); ?></td>
            </tr>
            <tr>
                <th>Prénom</th>
                <td><?php echo htmlspecialchars($_SESSION['prenom']); ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo htmlspecialchars($_SESSION['email']); ?></td>
            </tr>
            <tr>
                <th>Pays</th>
                <td><?php echo htmlspecialchars($_SESSION['Pays']); ?></td>
            </tr>
            <tr>
                <th>Ville</th>
                <td><?php echo htmlspecialchars($_SESSION['Ville']); ?></td>
            </tr>
            <tr>
                <th>Adresse</th>
                <td><?php echo htmlspecialchars($_SESSION['adresse']); ?></td>
            </tr>
        </table>
        <div id="weatherDisplay" class="weather-info"></div>
    </div>
    <script src="weather.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cityName = "<?php echo htmlspecialchars($_SESSION['Ville']); ?>";
            fetchWeatherData(cityName); // Fetch and display the weather information
        });
    </script>
</body>
</html>

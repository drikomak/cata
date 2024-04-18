<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ouraguessr - Conditions Météo</title>
</head>
<body>
    <h1>Conditions Météorologiques</h1>
    <?php echo "<p>Bonjour, voici les informations météorologiques.</p>"; ?>
    <label for="citySelect">Choisissez une ville :</label>
    <select id="citySelect">
        <option value="Paris">Paris</option>
        <option value="London">Londres</option>
        <option value="New York">New York</option>
        <option value="Tokyo">Tokyo</option>
        <option value="Berlin">Berlin</option>
    </select>
    <button onclick="fetchWeatherData(document.getElementById('citySelect').value)">Obtenir les Conditions Météo</button>

    <div id="weatherDisplay"></div>

    <script src="weather.js"></script>
</body>
</html>

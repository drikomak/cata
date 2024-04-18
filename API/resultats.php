<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ouraguessr - Météo</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</head>
<body>
    <select id="countrySelect">
        <option value="">Sélectionnez un pays</option>
    </select>
    <select id="citySelect">
        <option value="">Sélectionnez une ville</option>
    </select>

    <div id="weatherDisplay"></div>

    <script src="weather.js"></script>
</body>
</html>

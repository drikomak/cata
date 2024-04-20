<!DOCTYPE html>
<html>
<head>
    <title>Carte avec Heatmap</title>
    <!-- Inclure les bibliothèques JavaScript pour la carte -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://leaflet.github.io/Leaflet.heat/dist/leaflet-heat.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.css" />
    <!-- Ajouter du CSS pour la carte -->
    <style>
        #map {
            height: 500px;
        }
    </style>
</head>
<body>
    <div id="map"></div>

    <script>
        // Initialiser la carte
        var map = L.map('map').setView([0, 0], 2);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Exemple de données de heatmap (emplacement aléatoire pour démonstration)
        var heatMapData = [];
        for (var i = 0; i < 1000; i++) {
            heatMapData.push([
                Math.random() * 180 - 90, // Latitude
                Math.random() * 360 - 180 // Longitude
            ]);
        }

        // Ajouter la heatmap à la carte
        L.heatLayer(heatMapData, { radius: 20 }).addTo(map);
    </script>
</body>
</html>

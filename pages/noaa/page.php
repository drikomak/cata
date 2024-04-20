<!DOCTYPE html>
<html>
<head>
    <title>Carte météo du monde</title>
    <!-- Inclure les bibliothèques JavaScript pour la carte -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.js"></script>
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

        // Ajouter un marqueur pour chaque ville avec des données météorologiques
        var cities = [
            { name: 'New York', lat: 40.7128, lon: -74.0060 },
            { name: 'Paris', lat: 48.8566, lon: 2.3522 },
            // Ajoutez d'autres villes ici
        ];

        cities.forEach(function(city) {
            // Récupérer les données météorologiques pour chaque ville depuis l'API de la NOAA
            $.getJSON('https://api.weather.gov/points/' + city.lat + ',' + city.lon + '/forecast', function(data) {
                // Insérer le code pour traiter et afficher les données météorologiques
                // Par exemple, vous pouvez afficher la température actuelle ou les prévisions météorologiques pour chaque ville
            });
        });
    </script>
</body>
</html>

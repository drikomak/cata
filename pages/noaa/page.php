<!DOCTYPE html>
<html>
<head>
    <title>Carte des ouragans</title>
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
        .legend {
            position: absolute;
            bottom: 20px;
            left: 20px;
            background-color: white;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .legend-item {
            margin-bottom: 5px;
        }
        .legend-item span {
            display: inline-block;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div id="map"></div>
    <div class="legend">
        <div class="legend-item"><span style="background-color: red;"></span> Catégorie 1 (74-95 kt)</div>
        <div class="legend-item"><span style="background-color: orange;"></span> Catégorie 2 (96-110 kt)</div>
        <div class="legend-item"><span style="background-color: yellow;"></span> Catégorie 3 (111-129 kt)</div>
        <div class="legend-item"><span style="background-color: green;"></span> Catégorie 4 (130-156 kt)</div>
        <div class="legend-item"><span style="background-color: blue;"></span> Catégorie 5 (>156 kt)</div>
    </div>

    <script>
        // Initialiser la carte
        var map = L.map('map').setView([0, 0], 2);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Définition des icônes d'ouragans
        var hurricaneIcons = {
            "Category 1": L.icon({
                iconUrl: '../../images/cat1.png',
                iconSize: [50, 50], // Taille de l'icône
                iconAnchor: [15, 15] // Point d'ancrage de l'icône
            }),
            "Category 2": L.icon({
                iconUrl: '../../images/cat2.png',
                iconSize: [30, 30],
                iconAnchor: [15, 15]
            }),
            "Category 3": L.icon({
                iconUrl: '../../images/cat3.png',
                iconSize: [30, 30],
                iconAnchor: [15, 15]
            }),
            "Category 4": L.icon({
                iconUrl: '../../images/cat4.png',
                iconSize: [30, 30],
                iconAnchor: [15, 15]
            }),
            "Category 5": L.icon({
                iconUrl: '../../images/cat5.png',
                iconSize: [30, 30],
                iconAnchor: [15, 15]
            }),
            // Ajoutez d'autres catégories d'ouragan au besoin
        };

        // Récupérer les données d'ouragan depuis l'API de la NOAA
        fetch('https://api.weather.gov/alerts/active?event=Hurricane')
            .then(response => response.json())
            .then(data => {
                console.log('Les données d\'ouragan ont été récupérées avec succès !'); // Affiche une phrase dans la console lorsque les données sont récupérées
                console.log('Données détaillées :', data); // Ajout pour afficher les données détaillées dans la console
                var heatmapData = []; // Tableau pour stocker les coordonnées des ouragans
                // Afficher les alertes d'ouragan sur la carte
                data.features.forEach(function(alert) {
                    var coordinates = alert.geometry.coordinates.reverse(); // Inverser les coordonnées pour Leaflet
                    var description = alert.properties.description;
                    var windSpeed = parseFloat(description.match(/Wind: (\d+) kt/)[1]); // Extraire la vitesse du vent en nœuds
                    var category;

                    // Déterminer la catégorie de l'ouragan en fonction de la vitesse du vent
                    if (windSpeed >= 156) {
                        category = "Category 5";
                    } else if (windSpeed >= 130) {
                        category = "Category 4";
                    } else if (windSpeed >= 111) {
                        category = "Category 3";
                    } else if (windSpeed >= 96) {
                        category = "Category 2";
                    } else {
                        category = "Category 1";
                    }

                    // Ajouter les coordonnées à la heatmapData
                    heatmapData.push(coordinates);

                    // Créer le marqueur avec l'icône correspondante
                    L.marker(coordinates, { icon: hurricaneIcons[category] }).addTo(map).bindPopup(description);
                });

                // Créer la heatmap
                L.heatLayer(heatmapData, { radius: 20 }).addTo(map);
            })
            .catch(error => {
                console.error('Erreur lors de la récupération des données d\'ouragan:', error);
            });
            
    </script>

</body>
</html>

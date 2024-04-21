<!DOCTYPE html>
<html lang="fr">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link href="../../styles/styles.css" rel="stylesheet" rel="stylesheet"/>
    <meta charset="UTF-8">
    <title>Carte Plein Écran</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <nav class="nav-bar">
        <a href="../../main.php"><img class="logo" src="../../images/logo3.png"></a>
            <div class="nav-links">
                <ul>
                    <li><a href="../../main.php">Accueil</a></li>
                    <li><a href="../RawData/RawData.php">Rawdata</a></li>
                    <li><a href="../Discover/Discover.php">Discover</a></li>
                    <li><a href="../Connexion/connexion.php"><img src="../../images/profil.png" height = 30px></a></li>
                </ul>
            </div>
        </nav>
    </header>
    <style>
        body, html {
            height: 100%;  
            margin: 0;   
            padding: 0;   
        }
        #map {
            height: calc(100% - 50px); /* Réduire la hauteur de la carte pour laisser de la place pour la légende */
            width: 100%;   
        }
        .legend {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background-color: white;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            z-index: 1000; /* Assurez-vous que la légende apparaît au-dessus de la carte */
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
    <div id="no-hurricane" style="display: none; position: absolute; top: 10px; left: 50%; transform: translateX(-50%); background-color: white; padding: 10px; border-radius: 5px; border: 1px solid #ccc; z-index: 1000;">Pas d'ouragan en cours</div>
    <div class="legend">
        <div class="legend-item"><span style="background-color: red;"></span> Catégorie 1 (74-95 kt)</div>
        <div class="legend-item"><span style="background-color: orange;"></span> Catégorie 2 (96-110 kt)</div>
        <div class="legend-item"><span style="background-color: yellow;"></span> Catégorie 3 (111-129 kt)</div>
        <div class="legend-item"><span style="background-color: green;"></span> Catégorie 4 (130-156 kt)</div>
        <div class="legend-item"><span style="background-color: blue;"></span> Catégorie 5 (>156 kt)</div>
    </div>
    <script>
        var map = L.map('map', {
            zoomControl: false,
            scrollWheelZoom: false,
            doubleClickZoom: false,
            touchZoom: false,
            drag: false,
            keyboard: false
        }).setView([20.0, -60.0], 3);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
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
                iconSize: [50, 50],
                iconAnchor: [15, 15]
            }),
            "Category 3": L.icon({
                iconUrl: '../../images/cat3.png',
                iconSize: [50, 50],
                iconAnchor: [15, 15]
            }),
            "Category 4": L.icon({
                iconUrl: '../../images/cat4.png',
                iconSize: [50, 50],
                iconAnchor: [15, 15]
            }),
            "Category 5": L.icon({
                iconUrl: '../../images/cat5.png',
                iconSize: [50, 50],
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
                if(data.features.length === 0) {
                    document.getElementById('no-hurricane').style.display = 'block';
                } else {
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
                }
            })
            .catch(error => {
                console.error('Erreur lors de la récupération des données d\'ouragan:', error);
            });
    </script>
</body>
</html>

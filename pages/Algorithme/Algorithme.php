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
    <nav class="nav-bar">
        <a href="../../main.php"><img class="logo" src="../../images/logo3.png"></a>
        <div class="nav-links">
            <ul>
                <li><a href="../../main.php">Accueil</a></li>
                <li><a href="../RawData/RawData.php">Rawdata</a></li>
                <li><a href="../Discover/Discover.php">Discover</a></li>
                <li><a href="../Connexion/connexion.php"><img src="../../images/profil.png" height="30px"></a></li>
            </ul>
        </div>
    </nav>

    <div id="map"></div>
    <div id="no-hurricane" style="display: none; position: absolute; top: 10px; left: 50%; transform: translateX(-50%); background-color: white; padding: 10px; border-radius: 5px; border: 1px solid #ccc; z-index: 1000;">Pas d'ouragan en cours</div>
    <div class="legend">
        <div class="legend-item"><img src="../../images/cat1.png" alt="Catégorie 1" style="width: 20px; height: 20px; margin-right: 5px;"> Catégorie 1 (64-82 kt)</div>
        <div class="legend-item"><img src="../../images/cat2.png" alt="Catégorie 2" style="width: 20px; height: 20px; margin-right: 5px;"> Catégorie 2 (83-95 kt)</div>
        <div class="legend-item"><img src="../../images/cat3.png" alt="Catégorie 3" style="width: 20px; height: 20px; margin-right: 5px;"> Catégorie 3 (96-112 kt)</div>
        <div class="legend-item"><img src="../../images/cat4.png" alt="Catégorie 4" style="width: 20px; height: 20px; margin-right: 5px;"> Catégorie 4 (113-136 kt)</div>
        <div class="legend-item"><img src="../../images/cat5.png" alt="Catégorie 5" style="width: 20px; height: 20px; margin-right: 5px;"> Catégorie 5 (&gt;= 136 kt)</div>
    </div>
    <script>
        // parametre de la map
    var map = L.map('map', {
        zoomControl: false,
        scrollWheelZoom: true,
        doubleClickZoom: false,
        touchZoom: false,
        drag: false,
        keyboard: false
    }).setView([20.0, -60.0], 3);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Créer un cercle autour de la position de l'utilisateur
    var userCircle = L.circle([20.0, -60.0], {
        radius: 100000, // Rayon du cercle en mètres (correspondant à 100 km)
        color: 'black',
        fillColor: 'red', 
        fillOpacity: 0.5 
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
    };

    function calculateDistance(lat1, lon1, lat2, lon2) {

        // Convertir les degrés en radians
        var radLat1 = Math.PI * lat1 / 180;
        var radLat2 = Math.PI * lat2 / 180;
        var radLon1 = Math.PI * lon1 / 180;
        var radLon2 = Math.PI * lon2 / 180;

        // Calculer la différence de latitude et de longitude
        var deltaLat = radLat2 - radLat1;
        var deltaLon = radLon2 - radLon1;
        // Calculer la distance en utilisant la formule de la sphère
        var a = Math.sin(deltaLat / 2) * Math.sin(deltaLat / 2) +
                Math.cos(radLat1) * Math.cos(radLat2) *
                Math.sin(deltaLon / 2) * Math.sin(deltaLon / 2);
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

        var distance = 6371 * c; // Rayon moyen de la Terre en km
        return distance;
    }

    var userAlert = true; // Variable pour indiquer si l'utilisateur est proche d'un ouragan

    var userLatitude, userLongitude; // Variables pour stocker la position de l'utilisateur

    // Récupérer la position de l'utilisateur
    if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(function(position) {

            // Récupérer les coordonnées de la position de l'utilisateur
            userLatitude = position.coords.latitude;
            userLongitude = position.coords.longitude;

            // Placer un marqueur à la position de l'utilisateur sur la carte
            var userMarker = L.marker([userLatitude, userLongitude]).addTo(map);
            userMarker.bindPopup("Votre position actuelle").openPopup();

            // Centrer la carte sur la position de l'utilisateur
            map.setView([userLatitude, userLongitude], 10); 

            userCircle.setLatLng([userLatitude, userLongitude]).addTo(map);

            // Récupérer les données d'ouragan depuis l'API de la NOAA après avoir obtenu la position de l'utilisateur
            fetch('https://api.weather.gov/alerts/active?event=Hurricane')
                .then(response => response.json())
                .then(data => {
                    console.log('Les données d\'ouragan ont été récupérées avec succès !'); // verif console
                    console.log('Données détaillées :', data); // verif console
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
                            if (windSpeed >= 136) {
                                category = "Category 5";
                            } else if (windSpeed >= 113) {
                                category = "Category 4";
                            } else if (windSpeed >= 96) {
                                category = "Category 3";
                            } else if (windSpeed >= 83) {
                                category = "Category 2";
                            } else {
                                category = "Category 1";
                            }

                            // Ajouter les coordonnées à la heatmapData
                            heatmapData.push(coordinates);

                            // Calculer la distance entre l'utilisateur et l'ouragan
                            var distance = calculateDistance(userLatitude, userLongitude, coordinates[0], coordinates[1]);
                            
                            if (distance < 100) { // 100 kilomètres de seuil
                                userAlert = true;
                            }

                            // Marqueur ouragan
                            L.marker(coordinates, { icon: hurricaneIcons[category] }).addTo(map).bindPopup(description);
                        });

                        // Créer la heatmap
                        L.heatLayer(heatmapData, { radius: 20 }).addTo(map);

                        // Si userAlert est true, afficher lalerte
                        if (userAlert) {
                            var alertDiv = document.createElement('div');
                            alertDiv.innerHTML = 'Attention ! Vous êtes proche d\'un ouragan.';
                            alertDiv.style.backgroundColor = 'red';
                            alertDiv.style.color = 'white';
                            alertDiv.style.padding = '10px';
                            alertDiv.style.borderRadius = '5px';
                            alertDiv.style.position = 'absolute';
                            alertDiv.style.top = '10px';
                            alertDiv.style.left = '50%';
                            alertDiv.style.transform = 'translateX(-50%)';
                            alertDiv.style.zIndex = '1000';
                            document.body.appendChild(alertDiv);
                        }
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de la récupération des données d\'ouragan:', error);
                });
        }, function(error) {
            // En cas d'erreur lors de la récupération de la position de l'utilisateur
            console.error("Erreur lors de la récupération de la position de l'utilisateur :", error);
        });
    } else {
        // Si la géolocalisation n'est pas prise en charge par le navigateur
        console.log("La géolocalisation n'est pas disponible sur ce navigateur.");
    }
</script>

</body>
</html>

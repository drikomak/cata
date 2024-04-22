<!DOCTYPE html>
<html lang="fr">
    <head>
        <?php 
            require('../../BD/bd.php');  // Appel de la fonction pour obtenir la connexion à la base de données
            $bdd = getBD();

            // Exécution de la requête SQL pour récupérer les noms des ouragans dans la colonne nameYear
            $requeteListeNoms = $bdd->prepare("SELECT DISTINCT nameYear FROM corrected_hurricane_data order by nameYear asc;");
            $requeteListeNoms->execute();
            $noms= $requeteListeNoms->fetchAll(PDO::FETCH_COLUMN);
        ?>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="../../styles/stylesIlyas.css" rel="stylesheet" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200&display=swap" rel="stylesheet"> <!--Inclusion de la police d'écriture -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script><!-- on appelle la librairie Leaflet pour les études de trajectoires sur la carte  -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script><!--appel de chart.js pour générer les graphes -->
        <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
        <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment/dist/chartjs-adapter-moment.min.js"></script><!--on importe moment.js pour adapter les dates au format ISO dans le graphe-->
        <title>Données Brutes</title>
    </head>
    <body>
        <header>
            <nav class="nav-bar">
                <a href="../../main.php"><img class="logo" src="../../images/logo3.png"></a>
                <div class="nav-links">
                    <ul>
                        <li><a href="../Algorithme/Algorithme.php">Algorithm</a></li>
                        <li><a href="RawData.php">Rawdata</a></li>
                        <li><a href="../Discover/Discover.php">Discover</a></li>
                        <li><a href="../Connexion/connexion.php"><img src="../../images/profil.png" height = 30px></a></li>
                    </ul>
                </div>
            </nav>
        </header>
        <h1 class=txt>DONNEES BRUTES</h1>
        <div class="premier_par">
            <img class="gif" src="../../images/earth.gif" alt="">
            <div>
                <h2 class=txt>Statistique : Des données qui concernent l'Amérique du Nord</h2>
                <p class=txt>Dans un but éducatif, nous avons réalisé une interface permettant d'étudier les paramètres météorologiques 
                d'ouragans ayant frappé les côtes Est et Ouest de l'Amérique du nord. En effet, cette zone géographique étant
                particulièrement sujette aux intempéries et aux incidents météorologiques, elle bénéficie d'une attention spéciale
                vis à vis de ce genre de phénomènes, et donc d'une riche base de données d'études et de statistiques.<br>
                Vous pouvez choisir un ouragan et un paramètre à étudier. Les données seront ensuite affichées sous forme de graphique.
                </p>
            </div>
        </div>
        
        <h2><span class=txt>Choisissez l'ouragan et le paramètre que vous voulez étudier</span></h2>

        <div class=graph>
            <form action="requete_graph.php" method="post">
                <label for="ouragan">Ouragan :</label>
                <select name="nameYear">
                    <option value="David (1979)" selected="selected">David (1979)</option>
                    <?php
                        foreach($noms as $nom){
                            echo "<option value='".$nom."'>".$nom."</option>"; // on affiche les noms des ouragans dans une liste déroulante
                        }
                    ?>
                </select>
                <br>
                <label for="param">Paramètre :</label>
                <select name="param">
                    <option value="wind">Vent</option>
                    <option value="pressure">Pression</option>
                    <option value="exact_sst_anomaly">Anomalie de température surface</option>
                </select>
                <br>
                <input type="submit" value="Soumettre" class=txt>
            </form>
        </div>
        <canvas id="myChart" width="900" height="500"></canvas>
        <div class=container><a href="RawData2Par.php" class=boutonPar>Testez avec 2 paramètres !</a></div>
        <div id="map" style="height: 600px;"></div>
        <script>
            var map = L.map('map').setView([0, 0], 2); //on centre la carte sur le monde entier avec un zoom de 2
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map); // on ajoute une couche de tuiles OpenStreetMap
        </script>

        
    </body>
    <script>
        $(document).ready(function() {
            $("form").on("submit", function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({//début de la requête AJAX
                url: 'requete_graph.php',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // on crée le graphique avec les données reçues
                        console.log(response.data);
                        create2dChart(response.data);
                        var latitudes = response.data.map(entry => entry.lat);
                        var longitudes = response.data.map(entry => entry.long);//récupération des coordonnées géographiques

                        // on crée un tableau de points de latitude et de longitude en tant que coordonnées de ligne pour Leaflet
                        var trajetOuragan = latitudes.map(function(lat, index) {
                            return [lat, longitudes[index]];
                        });

                        // on crée une ligne reliant les points de latitude et de longitude sur la carte
                        var latLngs = trajetOuragan.map(function(coord) {
                            return L.latLng(coord[0], coord[1]);
                        });
                        map.eachLayer(function(layer) {
                            if (layer instanceof L.Polyline || layer instanceof L.Marker) {
                                map.removeLayer(layer);
                            }
                        });
                        var polylineShadow5 = L.polyline(latLngs, { color: 'white', weight: 18, opacity: 0.4 }).addTo(map);
                        var polylineShadow4 = L.polyline(latLngs, { color: 'white', weight: 16, opacity: 0.6 }).addTo(map);
                        var polylineShadow3 = L.polyline(latLngs, { color: 'white', weight: 14, opacity: 1 }).addTo(map);
                        var polylineShadow2 = L.polyline(latLngs, { color: 'grey', weight: 12, opacity: 0.3 }).addTo(map);
                        var polylineShadow1 = L.polyline(latLngs, { color: 'lightblue', weight: 10, opacity: 1 }).addTo(map);
                        var polyline = L.polyline(latLngs, { color: 'blue', weight: 5 , opacity: 0.7 }).addTo(map);

                        // Ajouter des marqueurs pour le point de départ et le point d'arrivée
                        var startPoint = L.marker([latitudes[0], longitudes[0]]).addTo(map);
                        startPoint.bindPopup("Départ").openPopup(); // Ajouter une étiquette au marqueur de départ

                        var endPoint = L.marker([latitudes[latitudes.length - 1], longitudes[longitudes.length - 1]]).addTo(map);
                        endPoint.bindPopup("Arrivée").openPopup(); // Ajouter une étiquette au marqueur d'arrivée

                        // Ajuster le zoom et la vue de la carte pour afficher la trajectoire de l'ouragan avec les marqueurs
                        var bounds = L.latLngBounds([latitudes[0], longitudes[0]], [latitudes[latitudes.length - 1], longitudes[longitudes.length - 1]]);
                        map.fitBounds(bounds);
                        $("#myChart").show();
                    } else {
                        alert(response.message); // Affiche un message d'erreur
                    }
                },
                error: function() {
                    alert("Une erreur s'est produite lors de la connexion.");
                }
            });
        });
        function create2dChart(data) {
        // Récupère le canvas
        var ctx = document.getElementById('myChart').getContext('2d');

        // On vérifie si un graphique existe déjà
        if (window.myChart instanceof Chart) {
            // Si oui, on le détruit
            window.myChart.destroy();
        }
        // on récupère la valeur du paramètre sélectionné
        var param = $('select[name="param"]').val();
        var nom = $('select[name="name"]').val();

        // Extraction des données pour le graphique
        var labels = data.map(entry => moment.utc(entry.year + '-' + entry.month + '-' + entry.day + ' ' + entry.hour + 'h', 'YYYY-M-D H[h]').toISOString());//conversion des dates au format ISO
        var values = data.map(entry => entry[param]);

        window.myChart = new Chart(ctx, {
            type: 'line',//graphique de type ligne
            data: {
                labels: labels,
                datasets: [{
                    label: param,
                    data: values,
                    fill: false,
                    borderColor: 'rgb(0, 0, 128)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: false, // Désactive la réponse au changement de taille de la fenêtre
                maintainAspectRatio: true,
                scales: {
                    x: {
                        type: 'time', // Utilise un axe de type 'time' pour les dates
                        time: {
                            unit: 'day' // Définit l'unité de temps (jour)
                        },
                        title: {
                            display: true,
                            text: 'Date',
                            color: 'white' // Couleur de l'axe x en blanc
                        },
                        ticks: {
                            color: 'white' // Couleur des marques de l'axe x en blanc
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: param,
                            color: 'white' // Couleur de l'axe y en blanc
                        },
                        ticks: {
                            color: 'white' // Couleur des marques de l'axe y en blanc
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: 'white' // Couleur des légendes en blanc
                        }
                    }
                }
            }
        });
    }
        $(".txt").addClass("visible"); //sert à l'animation des textes au chargement de la page
        });
    </script>
</html>

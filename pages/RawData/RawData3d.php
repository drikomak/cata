<!DOCTYPE html>
<html lang="en">
<head>
<?php require('../../BD/bd.php'); 
    // Appel de la fonction pour obtenir la connexion à la base de données
    $bdd = getBD();

    // Exécution de la requête SQL pour récupérer les noms
    $requeteListeNoms = $bdd->prepare("SELECT DISTINCT nameYear FROM corrected_hurricane_data order by nameYear asc;");
    $requeteListeNoms->execute();
    $noms= $requeteListeNoms->fetchAll(PDO::FETCH_COLUMN);
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital@0;1&display=swap" rel="stylesheet">
    <link href="../../styles/styles.css" rel="stylesheet" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment/dist/chartjs-adapter-moment.min.js"></script>
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
                    <li><a href="../Connexion/profile.php"><img src="../../images/profil.png" height = 30px></a></li>
            </ul>
        </div>
    </nav>
    </header>


    <h1><span class="txt">DONNEES BRUTES</span></h1>

    <div class="premier_par">
        <img class="img_p2" src="../../images/north_america.jpg" alt="north america map">
        <div>
        <h2 class="txt">Statistique : Des données qui concernent l'Amérique du Nord</h2>
        <p class=txt>Dans un but éducatif, nous avons réalisé une interface permettant d'étudier les paramètres météorologiques 
        d'ouragans ayant frappé les côtes Est et Ouest de l'Amérique du nord. En effet, cette zone géographique étant
        particulièrement sujette aux intempéries et aux incidents météorologiques, elle bénéficie d'une attention spéciale
        vis à vis de ce genre de phénomènes, et donc d'une riche base de données d'études et de statistiques.<br>
        Vous pouvez choisir un ouragan et un paramètre à étudier. Les données seront ensuite affichées sous forme de graphique.
        </p></div>
    </div>
    
    <h2 class=txt>Choisissez l'ouragan et les paramètres que vous voulez étudier</h2>

    <div class=graph>
        <form action="requete_graph3d.php" method="post">
        <label for="ouragan">Ouragan :</label>
        <select name="nameYear" class="txt">
        <?php
        foreach($noms as $nom){
            echo "<option value='".$nom."'>".$nom."</option>";
        }
        ?>
        </select>
        <br><br>
        <label for="param1">Paramètre 1 :</label>
        <select name="param1" class=txt>
        <option value="wind">Vent</option>
        <option value="pressure">Pression</option>
        <option value="exact_sst_anomaly">Anomalie de température surface</option>
        </select>
        <label for="param2">Paramètre 2 :</label>
        <select name="param2" class=txt>
        <option value="wind">Vent</option>
        <option value="pressure">Pression</option>
        <option value="exact_sst_anomaly">Anomalie de température surface</option>
        </select>
        <input type="submit" value="Soumettre" class=txt>
        </form>
    </div>
    <canvas id="myChart"></canvas>
    <a href="RawData.php" class=txt>Testez avec 1 paramètre !</a>

    <script>
$(document).ready(function() {
    $("form").on("submit", function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: 'requete_graph3d.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    createScatterPlot(response.data);
                } else {
                    alert(response.message); // Afficher un message d'erreur
                }
            },
            error: function() {
                alert("Une erreur s'est produite lors de la connexion.");
            }
        });
    });
});

function createScatterPlot(data) {
    // Récupérer le canvas
    var ctx = document.getElementById('myChart').getContext('2d');

    // Vérifier si un graphique existe déjà
    if (window.myChart instanceof Chart) {
        // Si oui, le détruire
        window.myChart.destroy();
    }

    // Récupérer la valeur du paramètre sélectionné
    var param1 = $('select[name="param1"]').val();
    var param2 = $('select[name="param2"]').val();

    // Extraction des données pour le graphique
// Extraction des données pour le graphique
    var labels = data.map(entry => moment.utc(entry.year + '-' + entry.month + '-' + entry.day + ' ' + entry.hour + 'h', 'YYYY-M-D H[h]').toISOString());
    var valuesParam1 = data.map(entry => entry[param1]);
    var valuesParam2 = data.map(entry => entry[param2]);

    // Calculer les rayons des points en fonction des valeurs de paramètre 2
    var pointRadii = valuesParam2.map(value => {
        // Normaliser les valeurs du paramètre 2 entre une plage de rayon de points, par exemple entre 3 et 10
        return 3 + (value - Math.min(...valuesParam2)) * (10 - 3) / (Math.max(...valuesParam2) - Math.min(...valuesParam2));
    });

    window.myChart = new Chart(ctx, {
        type: 'scatter',
        data: {
            labels: labels,
            datasets: [{
                label: 'Valeurs de ' + param1,
                data: valuesParam1,
                backgroundColor: 'rgba(0, 0, 200, 0.5)', // Couleur de remplissage des points en bleu avec opacité 0.5
                borderColor: 'rgba(0, 0, 255, 1)', // Couleur de contour des points
                borderWidth: 1,
                pointRadius: pointRadii, // Taille des points basée sur les valeurs de paramètre 2
                pointHoverRadius: 8 // Taille des points au survol
            }]
        },
        options: {
            responsive: false, // Désactiver la réponse au changement de taille de la fenêtre
            maintainAspectRatio: false,
            scales: {
                x: {
                    type: 'time', // Utiliser un axe de type 'time' pour les dates
                    time: {
                        unit: 'day' // Définir l'unité de temps (jour, mois, année, etc.)
                    },
                    title: {
                        display: true,
                        text: 'Date'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: param1
                    }
                }
            }
        }
    });
}

</script>

<div class="premier_par">
        <div>
        <h2 class="txt">Temps réel : Utilisation d'un API</h2>
        <p class=txt>Les données météorologiques sont récoltées en temps réel grâce à l'API OpenWeatherMap.<br>
        Cet API nous permet de récupérer des données météorologiques sur n'importe quelle ville du monde.<br>
        Ces données sont ensuite utilisées pour être analysées par notre modèle de prédiction et afficher un résultat.
        </p></div>
        <img class="img_p2" src="../../images/OpenWeather-Logo.jpg" alt="">
    </div>

    <a href="../Algorithme/Algorithme.php" class=txt>Consultez notre algorithme !</a>
</body>
<script>
$(document).ready(function() {
    $("body").css({
        "background": "linear-gradient(135deg, black, darkblue)",
        "background-repeat": "no-repeat",
        "background-size": "cover",
    });
    $("html").css("background", "black");
    $("header").css("margin-bottom", "1.5em");
    $(".txt").addClass("visible");
});
</script>
</html>
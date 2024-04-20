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
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">
    <link href="../../styles/stylesIlyas.css" rel="stylesheet" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment/dist/chartjs-adapter-moment.min.js"></script>
    <title>Données Brutes</title>
    <style>
        .txt {
            opacity: 0;
            transition: opacity 1s ease-in-out; /* Transition de 1 seconde avec une fonction d'accélération */
        }
        .txt.visible {
            opacity: 1;
        }
    </style>
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
    
    <h2 class=txt>Choisissez l'ouragan et le paramètre que vous voulez étudier</h2>

    <div class=graph>
        <form action="requete_graph.php" method="post">
        <label for="ouragan">Ouragan :</label>
        <select name="nameYear" class="txt">
        <?php
        foreach($noms as $nom){
            echo "<option value='".$nom."' class=txt>".$nom."</option>";
        }
        ?>
        </select>
        <br>
        <label for="param">Paramètre :</label>
        <select name="param" class=txt>
        <option value="wind" class=txt>Vent</option>
        <option value="pressure" class=txt>Pression</option>
        <option value="exact_sst_anomaly" class=txt>Anomalie de température surface</option>
        </select>
        <br>
        <input type="submit" value="Soumettre" class=txt>
        </form>
    </div>
    <canvas id="myChart"></canvas>
    <a href="RawData3d.php" class=txt>Testez avec 2 paramètres !</a>

<script>
$(document).ready(function() {
    $("form").on("submit", function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: 'requete_graph.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Créer le graphique avec les données reçues
                    console.log(response.data);
                    create2dChart(response.data);
                    $("#myChart").show();
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
function updateChartSize() {
    var chartCanvas = document.getElementById('myChart');
    chartCanvas.style.width = '900px';
    chartCanvas.style.height = '600px';
}
function create2dChart(data) {
    // Récupérer le canvas
    var ctx = document.getElementById('myChart').getContext('2d');

    // Vérifier si un graphique existe déjà
    if (window.myChart instanceof Chart) {
        // Si oui, le détruire
        window.myChart.destroy();
    }
    updateChartSize();

    // Récupérer la valeur du paramètre sélectionné
    var param = $('select[name="param"]').val();
    var nom = $('select[name="name"]').val();

    // Extraction des données pour le graphique
    var labels = data.map(entry => moment.utc(entry.year + '-' + entry.month + '-' + entry.day + ' ' + entry.hour + 'h', 'YYYY-M-D H[h]').toISOString());
    var values = data.map(entry => entry[param]);

    window.myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Valeurs de ' + param,
                data: values,
                fill: false,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        },
        options: {
            responsive: false, // Désactiver la réponse au changement de taille de la fenêtre
            maintainAspectRatio: true,
            scales: {
                x: {
                    type: 'time', // Utiliser un axe de type 'time' pour les dates
                    time: {
                        unit: 'day' // Définir l'unité de temps (jour, mois, année, etc.)
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
    $("body").css("background", "linear-gradient(135deg, black, darkblue)");
    $("body").css("background-repeat", "no-repeat");
    $("body").css("background-size", "cover");
    $("html").css("background", "black");
    $("header").css("margin-bottom", "1.5em");
    $(".txt").addClass("visible");
    $(".txt").css("border","none");
});
</script>

</html>

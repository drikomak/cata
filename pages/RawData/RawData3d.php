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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&display=swap" rel="stylesheet">
    <link href="../../styles/styles.css" rel="stylesheet" rel="stylesheet">
    <title>Données Brutes</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>

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
    <a href="RawData.php" class=txt>Testez avec une variable</a>

    <h2><span class="txt">Statistique : Des données qui concernent l'Amérique du Nord</span></h2>
    <div class="photo" ><img class="imgp2" src="../../images/north_america.jpg" alt="north america map"></div>
    
    
    
    <h3><span class=txt>Tableau des ouragans</span></h3>

    <p><span class=txt>Choisissez l'ouragan et le paramètre que vous voulez étudier</span></p>
    <div class=txt>
        <form action="requete_graph.php" method="post">
        <label for="ouragan">Ouragan :</label>
        <select name="name" class="txt">
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
                    create3dChart(response.data);
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

function create3dChart(data) {
    // Récupérer les valeurs des paramètres sélectionnés
    var param1 = $('select[name="param1"]').val();
    var param2 = $('select[name="param2"]').val();
    var nom = $('select[name="name"]').val();
    
    // Extraction des données pour le graphe 3D
    var xValues = data.map(entry => entry[param1]);
    var yValues = data.map(entry => entry[param2]);
    var zValues = data.map(entry => entry.year + '-' + entry.month + '-' + entry.day + ' ' + entry.hour);

    // Création de la trace pour le nuage de points 3D
    var trace = {
        x: xValues,
        y: yValues,
        z: zValues,
        mode: 'markers',
        marker: {
            size: 5,
            opacity: 0.8
        },
        type: 'scatter3d'
    };

    // Création de la mise en page
    var layout = {
        scene: {
            xaxis: { title: param1 },
            yaxis: { title: param2 },
            zaxis: { title: 'Date et heure' }
        },
        margin: { l: 0, r: 0, b: 0, t: 0 } // Marges pour ajuster la taille du graphe
    };

    // Affichage du graphe 3D
    Plotly.newPlot('myChart', [trace], layout);
}

</script>


<h2><span class=txt>Temps réel : Utilisation d'un API</span></h2>
<div class=photo><img class="imgp2" src="../../images/api.webp" alt=""> <img class="imgp2" src="../../images/dataflow.webp" alt=""></div>
<p><span class=txt>Les données météorologiques sont récoltées en temps réel grâce à l'API OpenWeatherMap. Cet API nous permet de récupérer des données météorologiques sur n'importe quelle ville du monde. Ces données sont ensuite utilisées pour être analysées par notre modèle de prédiction et afficher un résultat.</span></p>

</body>
<script>
$(document).ready(function() {
    $("body").css("background", "linear-gradient(to bottom, black, rgb(100,100,100)");
});
</script>

</html>

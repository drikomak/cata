<!DOCTYPE html>
<html lang="en">
<head>
    <?php require('../../BD/bd.php'); 
    // Appel de la fonction pour obtenir la connexion à la base de données
    $bdd = getBD();

    // Exécution de la requête SQL pour récupérer les noms
    $requeteListeNoms = $bdd->prepare("SELECT DISTINCT name FROM corrected_hurricane_data order by name asc;");
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
        <label for="param">Paramètre :</label>
        <select name="param" class=txt>
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
                    createChart(response.data);
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

function createChart(data) {
    // Récupérer la valeur du paramètre sélectionné
    var param = $('select[name="param"]').val();
    var nom = $('select[name="name"]').val();

    // Assembler les données de date en une chaîne de caractères
    var labels = data.map(entry => entry.year + '-' + entry.month + '-' + entry.day + ' ' + entry.hour);
    var values = data.map(entry => entry[param]);

    // Configuration du graphique
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
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
            scales: {
                x: {
                    type: 'category' // Utiliser l'axe x de type 'category' pour afficher les chaînes de caractères comme des étiquettes
                }
            }
        }
    });
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

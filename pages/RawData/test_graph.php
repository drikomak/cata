<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Graphique de la fonction de deux variables</title>
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
</head>
<body>
    <div id="graph-container" style="width: 100%; height: 400px;"></div>

    <script>
        // Définir la fonction
        function f(x, y) {
            return x + y;
        }

        // Générer des données pour le tracé
        var data = [];
        var step = 0.1;
        for (var x = -5; x <= 5; x += step) {
            var row = [];
            for (var y = -5; y <= 5; y += step) {
                row.push(f(x, y));
            }
            data.push(row);
        }

        // Définir les coordonnées x et y
        var xValues = [];
        for (var x = -5; x <= 5; x += step) {
            xValues.push(x);
        }

        var yValues = [];
        for (var y = -5; y <= 5; y += step) {
            yValues.push(y);
        }

        // Définir la trace
        var trace = {
            x: xValues,
            y: yValues,
            z: data,
            type: 'surface'
        };

        // Définir la disposition
        var layout = {
            title: 'Graphique de la fonction de deux variables f(x, y) = x + y',
            scene: {
                xaxis: { title: 'X' },
                yaxis: { title: 'Y' },
                zaxis: { title: 'Z' }
            }
        };

        // Tracer le graphique
        Plotly.newPlot('graph-container', [trace], layout);
    </script>
</body>
</html>

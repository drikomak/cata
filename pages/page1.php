<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&display=swap" rel="stylesheet">
    <link href="../styles/styles.css" rel="stylesheet" />
    <title>Données passées</title>
    <style>
        body {
            background-image: url('../images/image_fond_page_algorithme.jpg');
            background-size: cover;
            background-repeat: no-repeat;
        }

        .select-container {
            padding-top: 200px;
            width: 100%;
            max-width: 400px;
            margin: auto;
            padding: 20px;
            background-color: #f0f0f0;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1); 
            border-radius: 8px; 
            text-align: center; 
        }

        .select-container select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px; 
            border-radius: 5px; 
            border: 1px solid #ccc; 
        }

        .select-container option,label,select {
            font-size: 16px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <header>
        <nav class="nav-bar">
            <a href="../main.php"><img class="logo" src="../images/logo3.png"></a>
            <div class="nav-links">
                <ul>
                    <li><a href="../pages/page1.php">Algorithm</a></li>
                    <li><a href="../pages/page2.php">Rawdata</a></li>
                    <li><a href="../pages/page3.php">Discover</a></li>
                    <li><a href="../connexion.php">Connexion</a></li>
                </ul>
            </div>
        </nav>
    </header>
    <div class="select-container">
        <label for="countrySelect">Pays :</label>
        <select id="countrySelect" name="country">
            <option value="">Sélectionnez un pays</option>
            
        </select>

        <label for="citySelect">Ville :</label>
        <select id="citySelect" name="city">
            <option value="">Sélectionnez d'abord un pays</option>
            
        </select>
    </div>
    <script src="../Interaction_menu_deroulant.js"></script>
</body>
</html>

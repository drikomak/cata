<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <title>Connexion et Inscription</title>
    <style>
        body {
            background-image: url('../../images/background.jpg'); /* Adjust path as necessary */
            background-position: center center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
            font-family: 'Poppins', sans-serif;
        }

        .container {
            display: flex;
            justify-content: space-around;
            margin-top: 50px;
        }

        .form-container {
            background-color: rgba(255, 255, 255, 0.5); /* White background with medium opacity */
            padding: 20px;
            border-radius: 8px;
            width: 300px; /* Fixed width for each form */
            margin: auto;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* Optional: adds shadow to the form container */
        }

        input[type="email"], input[type="password"], input[type="text"], button {
            width: 100%; /* Makes input take full width of the form */
            padding: 8px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box; /* Includes padding and border in element's width */
        }

        button {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        h1 {
            text-align: center;
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <nav class="nav-bar">
            <a href="../../main.php"><img class="logo" src="../../images/logo3.png" alt="Logo"></a>
            <div class="nav-links">
                <ul>
                    <li><a href="../Algorithme/Algorithme.php">Algorithm</a></li>
                    <li><a href="../RawData/RawData.php">Rawdata</a></li>
                    <li><a href="../Discover/Discover.php">Discover</a></li>
                    <li><a href="connexion.php">Connexion</a></li>
                </ul>
            </div>
        </nav>
    </header>
    <div class="container">
        <div class="form-container">
            <h1>Connexion</h1>
            <form>
                <input type="email" id="email" name="email" placeholder="Adresse e-mail" required><br>
                <input type="password" id="motdepasse" name="motdepasse" placeholder="Mot de passe" required><br>
                <button type="submit" id="loginBtn">Se connecter</button>
            </form>
        </div>
        <div class="form-container">
            <h1>Enregistrement utilisateur</h1>
            <form id="signupForm" autocomplete="off">
                <input type="text" name="n" placeholder="Nom" required>
                <input type="text" name="p" placeholder="Prénom" required>
                <input type="text" name="adr" placeholder="Adresse" required>
                <input type="text" name="mail" placeholder="Email" required>
                <input type="password" name="mdp1" placeholder="Mot de passe" required>
                <input type="password" name="mdp2" placeholder="Confirmez le mot de passe" required>
                <button type="button" id="envoieBtn">Envoyer</button>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('form').submit(function(e) {
                e.preventDefault();
                var formData = {
                    'email': $('#email').val(),
                    'motdepasse': $('#motdepasse').val()
                };
                $.ajax({
                    type: 'POST',
                    url: 'connecter.php',
                    data: formData,
                    dataType: 'json',
                    encode: true
                }).done(function(response) {
                    if (response.success) {
                        window.location.href = 'main.php';
                    } else {
                        alert(response.message);
                    }
                });
            });

            $("#envoieBtn").click(function() {
                $.ajax({
                    type: "POST",
                    url: "enregistrement.php",
                    data: $("#signupForm").serialize(),
                    dataType: "json",
                    success: function(response) {
                        if (response.status === "success") {
                            alert("Compte créé avec succès!");
                            window.location.href = "../../main.php";
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function() {
                        alert("Une erreur s'est produite. Veuillez réessayer.");
                    }
                });
            });
        });
    </script>
</body>
</html>

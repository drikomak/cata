<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link href="styles/styles.css" rel="stylesheet" />
    <title>Nouveau</title>
</head>
<body>

    <?php
        $n = isset($_GET['n']) ? $_GET['n'] : '';
        $p = isset($_GET['p']) ? $_GET['p'] : '';
        $adr = isset($_GET['adr']) ? $_GET['adr'] : '';
        $mail = isset($_GET['mail']) ? $_GET['mail'] : '';
    ?>

    <header>
        <nav class="nav-bar">
            <a href="main.php"><img class="logo" src="images/logo3.png"></a>
            <div class="nav-links">
                <ul>
                    <li><a href="pages/page1.php">Algorithm</a></li>
                    <li><a href="pages/page2.php">Rawdata</a></li>
                    <li><a href="pages/page3.php">Discover</a></li>
                    <li><a href="../connexion.php">Connexion</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <h1>Enregistrement utilisateur</h1>

    <form id="signupForm" autocomplete="off">
        <p>Nom : <input type="text" name="n" value="<?php echo $n; ?>"></p>
        <p>Prénom : <input type="text" name="p" value="<?php echo $p; ?>"></p>
        <p>Adresse : <input type="text" name="adr" value="<?php echo $adr; ?>"></p>
        <p>Email : <input type="text" name="mail" value="<?php echo $mail; ?>"></p>
        <p>Mot de passe : <input type="password" name="mdp1"></p>
        <p>Confirmez le mot de passe : <input type="password" name="mdp2"></p>
        <p><button type="button" id="envoieBtn">Envoyer</button></p>

        <div id="message"></div>
    </form>

    <script>
        $(document).ready(function() {
            $("#envoieBtn").click(function(e) {
                e.preventDefault();

                if (
                    $("input[name='n']").val() !== "" &&
                    $("input[name='p']").val() !== "" &&
                    $("input[name='adr']").val() !== "" &&
                    $("input[name='mail']").val() !== ""
                ) {
                    $.ajax({
                        type: "POST",
                        url: "enregistrement.php",
                        data: $("#signupForm").serialize(),
                        dataType: "json",
                        success: function(response) {
                            if (response.status === "success") {
                                $("#message").html("<p style='color:green;'>Compte créé avec succès!</p>");
                                setTimeout(function() {
                                    window.location.href = "main.php";
                                }, 1000);
                            } else {
                                $("#message").html("<p style='color:red;'>" + response.message + "</p>");
                            }
                        },
                        error: function() {
                            $("#message").html("<p style='color:red;'>Une erreur s'est produite. Veuillez réessayer.</p>");
                        }
                    });
                }
            });
        });
    </script>

</body>
</html>

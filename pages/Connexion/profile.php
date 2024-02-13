<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200&display=swap" rel="stylesheet">
    <link href="styles/styles.css" rel="stylesheet" />    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>
    
</body>
</html>

<header>
        <nav class="nav-bar">
            <a href="main.php"><img class="logo" src="images/logo3.png"></a>
            <div class="nav-links">
                <ul>
                    <li><a href="pages/page1.php">Algorithm</a></li>
                    <li><a href="pages/page2.php">Rawdata</a></li>
                    <li><a href="pages/page3.php">Discover</a></li>
                    <li><a href="deconnexion.php">Déconnexion</a></li>
                </ul>
            </div>
        </nav>
    </header>


<h1>Profil</h1>    
<?php

session_start();
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit;
}
$user = $_SESSION['user'];



echo "Nom : <span class='editable' data-field='nom'>" . $user['nom'] . " <a href='#' class='edit-link' data-field='nom'>Modifier</a></span><br>";
echo "Prenom : <span class='editable' data-field='prenom'>" . $user['prenom'] . " <a href='#' class='edit-link' data-field='prenom'>Modifier</a></span><br>";
echo "Adresse : <span class='editable' data-field='adresse'>" . $user['adresse'] . " <a href='#' class='edit-link' data-field='adresse'>Modifier</a></span><br>";
echo "Email : <span class='editable' data-field='mail'>" . $user['mail'] . " <a href='#' class='edit-link' data-field='mail'>Modifier</a></span><br>";

?>

<p><a href="deconnexion.php">Se deconnecter</a></p>

<script>
    $(document).ready(function () {
        $(".editable").click(function () {
            var field = $(this).data("field");
            var newValue = prompt("Modification du champ " + field + ":");
            
            if (newValue !== null) {
                $.ajax({
                    type: "POST",
                    url: "modif_profile.php",
                    data: { field: field, value: newValue },
                    success: function (response) {
                        if (response === "success") {
                            $(this).text(newValue);
                        } else {
                            alert("Failed to update " + field);
                        }
                    }
                });
            }
        });
    });
</script>

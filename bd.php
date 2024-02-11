<?php

function getBD(){
    $bdd = new PDO('mysql:host=localhost;dbname=Ouraguessr;charset=utf8', 'root', '');

    return $bdd;
}

function getBDpays(){
    $bdd = new PDO('mysql:host=localhost;dbname=mondial;charset=utf8', 'root', '');

    return $bdd;
}

?>
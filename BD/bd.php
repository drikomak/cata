<?php

function getBD(){
    $bdd = new PDO('mysql:host=localhost;dbname=ouraguessr;charset=utf8', 'root', 'root');

    return $bdd;
}

function getBDpays(){
    $bdd = new PDO('mysql:host=localhost;dbname=mondial;charset=utf8', 'root', 'root');

    return $bdd;
}

?>

<?php
require 'bd.php'; 
$bdd = getBDpays();

$query = $bdd->query('SELECT DISTINCT country_name,country_id FROM country ORDER BY country_name ASC');
$pays = $query->fetchAll(PDO::FETCH_ASSOC);



echo json_encode($pays);


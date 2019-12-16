<?php

require_once("player.php");
require_once("mysql_tables.php");

//$playerDriver = $_GET["Driver"];



$season = new season(2019,2);

$season->get_race_data(1);

$season->simulate_season();



?>


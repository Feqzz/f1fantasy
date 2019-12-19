<?php

require_once("mysql_tables.php");
require_once("season.php");

//$playerDriver = $_GET["Driver"];



$season = new season(2019,2);

$season->get_race_data(1);

$season->simulate_season();



?>


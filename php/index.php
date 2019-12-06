<?php

require_once("parser.php");

echo "Hello World";

getRaceData("2019","14");

$driverObject = new driver(44,25,"HAM","Lewis","Hamilton",
    "02-09-1999","british","hamilton","mercedes");

$array = array();
array_push($array, $driverObject);

echo $array[0]->getDriverId();

?>
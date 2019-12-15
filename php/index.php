<?php

require_once("player.php");
require_once("mysql_tables.php");

//$playerDriver = $_GET["Driver"];
$playerDriver = "vettel";
$playerDriverExists = false;

$season = new season(2019,2);

$season->get_race_data(1);

for ($i = 0; $i < count($season->get_drivers()); $i++)
{
    if ($season->get_drivers()[$i]->get_driver_id() == $playerDriver)
    {
        $playerDriverExists = true;
        $playerDriver = $season->get_drivers()[$i];
        break;
    }
}

if (!$playerDriverExists)
{
    print "Driver doesn't exist.";
    return;
}
$player = new player($playerDriver);

$season->simulate_season();
$player->updatePoints();

echo $player->getPoints();

?>


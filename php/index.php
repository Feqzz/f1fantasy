<?php

require_once("player.php");

$playerDriver = $_GET["Driver"];
$playerDriverExists = false;

$season = new season(2019,21);

$season->getRaceData(1);

for ($i = 0; $i < count($season->getDrivers()); $i++)
{
    if ($season->getDrivers()[$i]->getDriverId() == $playerDriver)
    {
        $playerDriverExists = true;
        $playerDriver = $season->getDrivers()[$i];
        break;
    }
}

if (!$playerDriverExists)
{
    print "Driver doesn't exist.";
    return;
}
$player = new player($playerDriver);

$season->simulateSeason();
$player->updatePoints();

echo $player->getPoints();

?>


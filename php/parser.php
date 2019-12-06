<?php

require_once("driver.php");

function getRaceData($season, $raceNumber)
{
//Downloads the website to file output.xml
    $website = "https://ergast.com/api/f1/" . $season . "/" . $raceNumber . "/results";
    $xml = file_get_contents($website);
    file_put_contents("output.xml", $xml);
}

function parse()
{
    $file = file_get_contents("output.xml");

    $MRData = new SimpleXMLElement($file);

    $season = $MRData->RaceTable->attributes();
    $round = "";


    echo $MRData->RaceTable->Race->RaceName;
}

parse();

?>
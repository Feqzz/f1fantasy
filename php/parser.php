<?php

require_once("race.php");

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

    //Getting race information and making a race object.

    $season = $MRData->RaceTable->attributes()->{'season'};
    $round = $MRData->RaceTable->attributes()->{'round'};
    $raceName = $MRData->RaceTable->Race->RaceName;
    $circuitId = $MRData->RaceTable->Race->Circuit->attributes()->{'circuitId'};
    $circuitName = $MRData->RaceTable->Race->Circuit->CircuitName;
    $country = $MRData->RaceTable->Race->Circuit->Location->Country;
    $date = $MRData->RaceTable->Race->Date;

    $race = new race($season,$round,$raceName,$circuitId, $circuitName, $country, $date);


    echo $race->getRound();
}

parse();

?>
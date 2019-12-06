<?php

require_once("race.php");

class season
{
    public function __construct($year, $numberOfRaces)
    {
        $this->year = $year;
        $this->numberOfRaces = $numberOfRaces;
    }

    function getRaceData()
    {
        $raceNumber = 15; //Will fix this later. Now it will only be an example.
        //Downloads the website to file output.xml
        $website = "https://ergast.com/api/f1/" . $this->year . "/" . $raceNumber . "/results";
        $xml = file_get_contents($website);
        file_put_contents("output.xml", $xml);

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

        if(!in_array($race, $this->races))
        {
            array_push($this->races, $race);
        }
        else
        {
            echo "Already in array";
            return;
        }





        foreach($MRData->RaceTable->Race->ResultsList->Result as $Result)
        {
            $permanentNumber =  $Result->Driver->PermanentNumber;
            $points;
            $code;
            $givenName;
            $familyName;
            $dateOfBirth;
            $nationality;
            $driverId;
            $constructorName;

            echo $permanentNumber . PHP_EOL;
        }


    }

    public function addDriver($driver)
    {
        array_push($this->drivers, $driver);
    }

    public function addConstructor($constructor)
    {
        if(!in_array($constructor, $this->constructors))
        {
            array_push($this->constructors, $constructor);
        }
    }

    public function addRace($race)
    {
        if(!in_array($race, $this->races))
        {
            array_push($this->races, $race);
        }
    }
    private $numberOfRaces;
    private $year;
    private $drivers = array();
    private $constructors = array();
    private $races = array();
}

?>
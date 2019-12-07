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

        $season = (string)$MRData->RaceTable->attributes()->{'season'};
        $round = (int)$MRData->RaceTable->attributes()->{'round'};
        $raceName = (string)$MRData->RaceTable->Race->RaceName;
        $circuitId = (string)$MRData->RaceTable->Race->Circuit->attributes()->{'circuitId'};
        $circuitName = (string)$MRData->RaceTable->Race->Circuit->CircuitName;
        $country = (string)$MRData->RaceTable->Race->Circuit->Location->Country;
        $date = (string)$MRData->RaceTable->Race->Date;

        $race = new race($season,$round,$raceName,$circuitId, $circuitName, $country, $date);

        for ($i = 0; $i < count($this->races); $i++)
        {
            if ($this->races[$i]->getRound() == $round)
            {
                return;
            }
        }

        array_push($this->races, $race);

        //Getting driver data



        foreach($MRData->RaceTable->Race->ResultsList->Result as $Result)
        {
            $position = (int)$Result->attributes()->{'position'};
            $points = (int)$Result->attributes()->{'points'};
            $permanentNumber =  (int)$Result->Driver->PermanentNumber;
            $driverId = (string)$Result->Driver->attributes()->{'driverId'};
            $code = (string)$Result->Driver->attributes()->{'code'};
            $givenName = (string)$Result->Driver->GivenName;
            $familyName = (string)$Result->Driver->FamilyName;
            $dateOfBirth = (string)$Result->Driver->DateOfBirth;
            $nationality = (string)$Result->Driver->Nationality;
            $constructorId = (string)$Result->Constructor->attributes()->{'constructorId'};
            $constructorName = (string)$Result->Constructor->Name;
            $constructorNationality = (string)$Result->Constructor->Nationality;
            $fastestLapRank = (int)$Result->FastestLap->attributes()->{'rank'};
            $fastestLapTime = (string)$Result->FastestLap->Time;

            $constructorAlreadyExists = false;
            $driverAlreadyExists = false;
            $driverConstructor = null;
            $currentDriver = null;

            for ($i = 0; $i < count($this->constructors); $i++)
            {
                if ($this->constructors[$i]->getConstructorId() == $constructorId)
                {
                    $constructorAlreadyExists = true;
                    $driverConstructor = $this->constructors[$i];
                }
            }

            if (!$constructorAlreadyExists)
            {
                $driverConstructor = new constructor($constructorId, $constructorName, $constructorNationality);
                array_push($this->constructors, $driverConstructor);
            }

            $race->addConstructor($driverConstructor);

            for ($i = 0; $i < count($this->drivers); $i++)
            {
                if ($this->drivers[$i]->getDriverId() == $driverId)
                {
                    $currentDriver = $this->drivers[$i];
                    if ($this->drivers[$i]->getConstructor() != $driverConstructor)
                    {
                        $this->drivers[$i]->changeConstructor($driverConstructor);
                    }
                    $this->drivers[$i]->increasePoints($points);
                    $driverAlreadyExists = true;
                }
            }

            if(!$driverAlreadyExists)
            {
                $currentDriver = new driver($permanentNumber, $points, $code, $givenName, $familyName, $dateOfBirth, $nationality, $driverId, $driverConstructor);
                array_push($this->drivers, $currentDriver);
            }

            $race->addDriver($currentDriver);

            $raceResult = new raceResult($currentDriver, $driverConstructor, $position, $points, $fastestLapRank, $fastestLapTime);

            $race->addRaceResult($raceResult);

            if ($fastestLapRank == 1)
            {
                $race->setFastestLapTime($fastestLapTime, $currentDriver);
            }
        }
    }

    public function printAllRaceResults()
    {
        for ($i = 0; $i < count($this->races); $i++)
        {
            $r = $this->races[$i]->getRaceResults();
            for ($j = 0; $j < count($r); $j++)
            {
                $r[$j]->printRaceResult();
            }
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
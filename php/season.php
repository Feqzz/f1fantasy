<?php

require_once("race.php");

class season
{
    public function __construct($year, $number_of_races)
    {
        $this->year = $year;
        $this->number_of_races = $number_of_races;

        require_once("dbh.php");
        $link = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($link->connect_error)
        {
            die("Connection failed " . $link->connect_error);
        }

        $query =
            "
                INSERT IGNORE INTO season(number_of_races, year)
                VALUES ('$this->number_of_races', '$this->year')
            ";

        mysqli_query($link, $query);
        mysqli_close($link);
    }

    function get_race_data($race_number_)
    {
        $race_number = $race_number_; //Will fix this later. Now it will only be an example.
        //Downloads the website to file output.xml
        $website = "https://ergast.com/api/f1/" . $this->year . "/" . $race_number . "/results";
        $xml = file_get_contents($website);
        file_put_contents("output.xml", $xml);

        $file = file_get_contents("output.xml");

        $MRData = new SimpleXMLElement($file);

        //Getting race information and making a race object.

        $season = (int)$MRData->RaceTable->attributes()->{'season'};
        $round = (int)$MRData->RaceTable->attributes()->{'round'};
        $race_name = (string)$MRData->RaceTable->Race->RaceName;
        $circuitId = (string)$MRData->RaceTable->Race->Circuit->attributes()->{'circuitId'};
        $circuitName = (string)$MRData->RaceTable->Race->Circuit->CircuitName;
        $country = (string)$MRData->RaceTable->Race->Circuit->Location->Country;
        $date = (string)$MRData->RaceTable->Race->Date;

        for ($i = 0; $i < count($this->races); $i++)
        {
            if ($this->races[$i]->getRound() == $round)
            {
                //Race already exists
                return;
            }
        }


        //Getting driver data

        foreach($MRData->RaceTable->Race->ResultsList->Result as $Result)
        {
            $position = (int)$Result->attributes()->{'position'};
            $points = (int)$Result->attributes()->{'points'};
            $driver_id = (string)$Result->Driver->attributes()->{'driverId'};
            $code = (string)$Result->Driver->attributes()->{'code'};
            $permanent_number =  (int)$Result->Driver->PermanentNumber;
            $given_name= (string)$Result->Driver->GivenName;
            $family_name = (string)$Result->Driver->FamilyName;
            $date_of_birth = (string)$Result->Driver->DateOfBirth;
            $nationality = (string)$Result->Driver->Nationality;
            $constructor_id = (string)$Result->Constructor->attributes()->{'constructorId'};
            $constructor_name = (string)$Result->Constructor->Name;
            $constructor_nationality = (string)$Result->Constructor->Nationality;
            $laps = (int)$Result->Laps;
            if ($laps > 20)
            {
                $fastestLapRank = (int)$Result->FastestLap->attributes()->{'rank'};
                $fastestLapTime = (string)$Result->FastestLap->Time;
            }
            else
            {
                $fastestLapRank = -1;
                $fastestLapTime = "Retired from race too early";
            }
            $constructor_already_exists = false;
            $driver_already_exists = false;
            $driver_constructor = null;
            $current_driver = null;

            for ($i = 0; $i < count($this->constructors); $i++)
            {
                if ($this->constructors[$i]->get_constructor_id() == $constructor_id)
                {
                    $constructor_already_exists = true;
                    $driver_constructor = $this->constructors[$i];
                    break;
                }
            }

            if (!$constructor_already_exists)
            {
                $driver_constructor = new constructor($constructor_id, $constructor_name, $constructor_nationality, $season);
                array_push($this->constructors, $driver_constructor);
            }

            for ($i = 0; $i < count($this->drivers); $i++)
            {
                if ($this->drivers[$i]->get_driver_id() == $driver_id)
                {
                    $current_driver = $this->drivers[$i];
                    if ($this->drivers[$i]->get_constructor() != $driver_constructor)
                    {
                        $this->drivers[$i]->change_constructor($driver_constructor);
                    }
                    $this->drivers[$i]->increase_points($points);
                    $driver_already_exists = true;
                    break;
                }
            }

            if(!$driver_already_exists)
            {
                $current_driver = new driver($permanent_number, $points, $code, $given_name, $family_name, $date_of_birth, $nationality, $driver_id, $season);
                $current_driver->set_constructor($driver_constructor);
                array_push($this->drivers, $current_driver);
            }

            $race = new race($season,$round,$race_name,$circuitId, $circuitName, $country, $date);

            $race->addDriver($current_driver);
            $race->addConstructor($driver_constructor);
            $raceResult = new raceResult($current_driver, $driver_constructor, $position, $points, $fastestLapRank, $fastestLapTime, $race->get_race_id());
            $race->addRaceResult($raceResult);

            if ($fastestLapRank == 1)
            {
                $race->setFastestLapTime($fastestLapTime, $current_driver);
            }

            $current_driver->change_price();
            array_push($this->races, $race);
        }
    }

    public function print_all_race_results()
    {
        for ($i = 0; $i < count($this->races); $i++)
        {
            $r = $this->races[$i]->getRaceResults();
            print $this->races[$i]->getRaceName . "<br>\n";
            for ($j = 0; $j < count($r); $j++)
            {
                $r[$j]->printRaceResult();

            }
            echo "<br>\n";
        }
    }

    public function simulate_season()
    {
        for ($i = 2; $i <= $this->number_of_races; $i++)
        {
            $this->get_race_data($i);
        }
    }

    public function add_driver($driver)
    {
        array_push($this->drivers, $driver);
    }

    public function add_constructor($constructor)
    {
        if(!in_array($constructor, $this->constructors))
        {
            array_push($this->constructors, $constructor);
        }
    }

    public function add_race($race)
    {
        if(!in_array($race, $this->races))
        {
            array_push($this->races, $race);
        }
    }

    public function get_drivers()
    {
        return $this->drivers;
    }

    private $number_of_races;
    private $year;
    private $drivers = array();
    private $constructors = array();
    private $races = array();
}
?>
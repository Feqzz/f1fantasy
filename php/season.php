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

    public function get_race_data($race_number_)
    {
        $race_number = $race_number_; //Will fix this later. Now it will only be an example.

        //Downloads the website to file output.xml
        $website = "https://ergast.com/api/f1/" . $this->year . "/" . $race_number . "/results";
        $xml = file_get_contents($website);
        file_put_contents("output.xml", $xml);

        $file = file_get_contents("output.xml");

        $MRData = new SimpleXMLElement($file);

        //Getting season and information from the file first. Then I check if it already exists in the database.
        $circuit_id = (string)$MRData->RaceTable->Race->Circuit->attributes()->{'circuitId'};
        $season = (int)$MRData->RaceTable->attributes()->{'season'};

        require_once("dbh.php");
        $link = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($link->connect_error)
        {
            die("Connection failed " . $link->connect_error);
        }

        $races_array = array();
        $resource = $link->query("SELECT * FROM races");
        while ($row = $resource->fetch_assoc())
        {
            $circuit_id_db = "{$row['race_id']}";
            $season_db = "{$row['season']}";
            array_push($races_array, array($season_db, $circuit_id_db));
        }

        for ($i = 0; $i < count($races_array); $i++)
        {
            if (($season == $races_array[$i][0]) && ($circuit_id == $races_array[$i][1]))
            {
                //Race already exists in database..
                return;
            }
        }

        $round = (int)$MRData->RaceTable->attributes()->{'round'};
        $race_name = (string)$MRData->RaceTable->Race->RaceName;
        $circuit_name = (string)$MRData->RaceTable->Race->Circuit->CircuitName;
        $country = (string)$MRData->RaceTable->Race->Circuit->Location->Country;
        $date = (string)$MRData->RaceTable->Race->Date;

        $race = new race($season,$round,$race_name,$circuit_id, $circuit_name, $country, $date);


        //Getting data from database. Using it for checking duplicate drivers and constructors.
        $drivers_array = array();
        $constructor_array = array();
        $resource = $link->query("SELECT * FROM drivers");
        while ($row = $resource->fetch_assoc())
        {
            $driver_id_db = "{$row['driver_id']}";
            $constructor_id_db = "{$row['constructor_id']}";
            $season_db = "{$row['season']}";
            array_push($drivers_array, array($season_db, $driver_id_db, $constructor_id_db));
        }
        $resource = $link->query("SELECT * FROM constructors");
        while ($row = $resource->fetch_assoc())
        {
            $constructor_id_db = "{$row['constructor_id']}";
            $season_db = "{$row['season']}";
            array_push($constructor_array, array($season_db, $constructor_id_db));
        }

        //Getting driver data from the file and checking it with the array of drivers from the database.
        $driver_already_exists = false;
        $constructor_already_exists = false;

        foreach($MRData->RaceTable->Race->ResultsList->Result as $Result)
        {
            $driver_id = (string)$Result->Driver->attributes()->{'driverId'};
            $constructor_id = (string)$Result->Constructor->attributes()->{'constructorId'};
            for ($i = 0; $i < count($drivers_array); $i++)
            {
                if (($season == $drivers_array[$i][0]) && ($driver_id == $drivers_array[$i][1]))
                {
                    if ($constructor_id == $drivers_array[$i][2])
                    {
                        $driver_already_exists = true;
                        break;
                    }
                    else
                    {
                        $query =
                            "
                                UPDATE drivers
                                SET 
                                    constructor_id = '$constructor_id'
                                WHERE
                                    driver_id = '$driver_id'
                            ";
                        mysqli_query($link, $query);
                    }
                }
            }
            $position = (int)$Result->attributes()->{'position'};
            $points = (int)$Result->attributes()->{'points'};
            $code = (string)$Result->Driver->attributes()->{'code'};
            $permanent_number =  (int)$Result->Driver->PermanentNumber;
            $given_name= (string)$Result->Driver->GivenName;
            $family_name = (string)$Result->Driver->FamilyName;
            $date_of_birth = (string)$Result->Driver->DateOfBirth;
            $nationality = (string)$Result->Driver->Nationality;
            $constructor_name = (string)$Result->Constructor->Name;
            $constructor_nationality = (string)$Result->Constructor->Nationality;
            $laps = (int)$Result->Laps;
            if ($laps > 20)
            {
                $fastest_lap_rank = (int)$Result->FastestLap->attributes()->{'rank'};
                $fastest_lap_time = (string)$Result->FastestLap->Time;
            }
            else
            {
                $fastest_lap_rank = -1;
                $fastest_lap_time = "Retired from race too early";
            }

            for ($i = 0; $i < count($constructor_array); $i++)
            {
                if (($season == $constructor_array[$i][0]) && ($constructor_id == $constructor_array[$i][1]))
                {
                    $constructor_already_exists = true;
                    break;
                }
            }

            if(!$constructor_already_exists)
            {
                $drivers_constructor = new constructor($constructor_id, $constructor_name,
                    $constructor_nationality, $season);
            }

            if(!$driver_already_exists)
            {
                $driver = new driver($permanent_number, $points, $code, $given_name,
                    $family_name, $date_of_birth, $nationality, $driver_id, $season);
            }

            $race_id = $race->get_race_id();

            $race_result = new race_result($driver_id, $constructor_id, $position, $points,
                $fastest_lap_rank, $fastest_lap_time, $race_id, $season);

            if ($fastest_lap_rank == 1)
            {
                $race->setFastestLapTime($fastest_lap_time, $driver_id);
            }
        }
        mysqli_close($link);
        $this->update_player_results($race);
    }

    private function update_player_results($race)
    {
        require_once("dbh.php");
        $link = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($link->connect_error)
        {
            die("Connection failed " . $link->connect_error);
        }
        $race_id = $race->get_race_id();

        $resource = $link->query("SELECT * FROM player_race_results");
        while ($row = $resource->fetch_assoc())
        {
            $id = "{$row['id']}";
            $query =
                "
                UPDATE player_race_results
                SET 
                    race_id = '$race_id'
                WHERE
                    id = '$id'
            ";
            mysqli_query($link, $query);
        }
        mysqli_close($link);
    }

    private function change_driver_price($driver_id, $position)
    {
        require_once("dbh.php");
        $link = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($link->connect_error)
        {
            die("Connection failed " . $link->connect_error);
        }
        $resource = $link->query("SELECT * FROM drivers WHERE driver_id='$driver_id'");
        while ($row = $resource->fetch_assoc())
        {
            $price = "{$row['price']}";
        }

        //Må finne på en algoritme her som tar hensyn til at noen komboer ikke er alt for OP.






        mysqli_close($link);
    }

    private $number_of_races;
    private $year;
}
?>
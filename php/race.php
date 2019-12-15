<?php

require_once("raceResult.php");

class race
{
    public function __construct($season, $round, $raceName, $circuitId, $circuitName, $country, $date)
    {
        $this->season = $season;
        $this->round = $round;
        $this->raceName = $raceName;
        $this->circuitId = $circuitId;
        $this->circuitName = $circuitName;
        $this->country = $country;
        $this->date = $date;
        $this->race_id = $season . "_" . $circuitId;

        require_once("dbh.php");

        $link = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($link->connect_error)
        {
            die("Connection failed " . $link->connect_error);
        }

        $query =
            "
                INSERT IGNORE INTO races (race_id, round, race_name, circuit_id,
                                          circuit_name, country, date, season)
                VALUES ('$this->race_id', '$this->round','$this->raceName',
                        '$this->circuitId', '$this->circuitName',
                        '$this->country', '$this->date', '$this->season')
            ";

        mysqli_query($link, $query);
        $link->close();
    }

    public function addDriver($driver)
    {
        array_push($this->drivers, $driver);
    }

    public function addConstructor($constructor)
    {
        array_push($this->constructors, $constructor);
    }

    public function setFastestLapTime($lapTime, $driver)
    {
        $this->fastestLapTime = $lapTime;
        $this->fastestLapDriver = $driver;

        $driver_id = $this->fastestLapDriver->get_driver_id();

        require_once("dbh.php");

        $link = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($link->connect_error)
        {
            die("Connection failed " . $link->connect_error);
        }

        $query =
            "
                UPDATE races
                SET 
                    fastest_lap_driver_id = '$driver_id',
                    fastest_lap_time = '$lapTime'
                WHERE
                    race_id = '$this->race_id';
            ";

        mysqli_query($link, $query);

        $link->close();
    }

    public function addRaceResult($raceResult)
    {
        array_push($this->raceResults, $raceResult);
    }

    public function getRound()
    {
        return $this->round;
    }

    public function getRaceResults()
    {
        return $this->raceResults;
    }

    public function getRaceName()
    {
        return $this->raceName;
    }

    public function get_race_id()
    {
        return $this->race_id;
    }

    private $race_id;
    private $season;
    private $round;
    private $raceName;
    private $circuitId;
    private $circuitName;
    private $country;
    private $date;
    private $fastestLapTime;
    private $fastestLapDriver;
    private $drivers = array();
    private $constructors = array();
    private $raceResults = array();

}

?>
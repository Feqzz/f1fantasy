<?php

require_once("race_result.php");

class race
{
    public function __construct($season, $round, $raceName, $circuitId, $circuitName, $country, $date)
    {
        $this->season = $season;
        $this->round = $round;
        $this->race_name = $raceName;
        $this->circuit_id = $circuitId;
        $this->circuit_name = $circuitName;
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
                VALUES ('$this->race_id', '$this->round','$this->race_name',
                        '$this->circuit_id', '$this->circuit_name',
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

    public function setFastestLapTime($lap_time, $driver_id)
    {
        $this->fastest_lap_time = $lap_time;
        $this->fastest_lap_driver_id = $driver_id;

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
                    fastest_lap_time = '$lap_time'
                WHERE
                    race_id = '$this->race_id';
            ";

        mysqli_query($link, $query);
        mysqli_close($link);
    }

    public function addRaceResult($raceResult)
    {
        array_push($this->race_results, $raceResult);
    }

    public function getRound()
    {
        return $this->round;
    }

    public function get_race_results()
    {
        return $this->race_results;
    }

    public function get_race_name()
    {
        return $this->race_name;
    }

    public function get_race_id()
    {
        return $this->race_id;
    }

    private $race_id;
    private $season;
    private $round;
    private $race_name;
    private $circuit_id;
    private $circuit_name;
    private $country;
    private $date;
    private $fastest_lap_time;
    private $fastest_lap_driver_id;
    private $drivers = array();
    private $constructors = array();
    private $race_results = array();
}

?>
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

        require_once(dirname(__FILE__). "/../dbh.php");

        $link = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($link->connect_error)
        {
            die("Connection failed " . $link->connect_error);
        }

        $query =
            "
                INSERT IGNORE INTO races (circuit_id, season, round, race_name,
                                          circuit_name, country, date)
                VALUES ('$this->circuit_id', '$this->season','$this->round',
                        '$this->race_name', '$this->circuit_name',
                        '$this->country', '$this->date')
            ";

        mysqli_query($link, $query);
        mysqli_close($link);
    }

    public function set_fastest_lap_time($lap_time, $driver_id)
    {
        $this->fastest_lap_time = $lap_time;
        $this->fastest_lap_driver_id = $driver_id;

        require_once(dirname(__FILE__). "/../dbh.php");

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
                    (circuit_id = '$this->circuit_id') and
                    (season = '$this->season')
            ";

        mysqli_query($link, $query);
        mysqli_close($link);
    }
    private $season;
    private $round;
    private $race_name;
    private $circuit_id;
    private $circuit_name;
    private $country;
    private $date;
    private $fastest_lap_time;
    private $fastest_lap_driver_id;
}
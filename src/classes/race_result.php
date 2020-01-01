<?php

require_once("driver.php");

class race_result
{
    public function __construct($circuit_id, $season, $driver_id, $constructor_id, $position, $points, $fastest_lap_rank, $fastest_lap_time)
    {
        $this->circuit_id = $circuit_id;
        $this->season = $season;
        $this->driver_id = $driver_id;
        $this->constructor_id = $constructor_id;
        $this->position = $position;
        $this->points = $points;
        $this->fastest_lap_rank = $fastest_lap_rank;
        $this->fastest_lap_time = $fastest_lap_time;

        require_once(dirname(__FILE__). "/../dbh.php");
        $link = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($link->connect_error)
        {
            die("Connection failed " . $link->connect_error);
        }

        $query =
            "
                INSERT IGNORE INTO race_results (circuit_id, season, driver_id, constructor_id, position, points, fastest_lap_rank, fastest_lap_time)
                VALUES ('$this->circuit_id', '$this->season', '$this->driver_id', '$this->constructor_id', '$this->position', '$this->points',
                        '$this->fastest_lap_rank', '$this->fastest_lap_time')
            ";

        mysqli_query($link, $query);
        echo mysqli_error($link);
        mysqli_close($link);
    }
    private $circuit_id;
    private $driver_id;
    private $constructor_id;
    private $position;
    private $points;
    private $fastest_lap_rank;
    private $fastest_lap_time;
    private $season;
}
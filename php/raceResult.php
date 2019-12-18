<?php

require_once("driver.php");

class raceResult
{
    public function __construct($driver, $constructor, $position, $points, $fastest_lap_rank, $fastest_lap_time, $race_id)
    {
        $this->race_id = $race_id;
        $this->driver_id = $driver->get_driver_id();
        $this->constructor_id = $constructor->get_constructor_id();
        $this->position = $position;
        $this->points = $points;
        $this->fastest_lap_rank = $fastest_lap_rank;
        $this->fastest_lap_time = $fastest_lap_time;

        require_once("dbh.php");
        $link = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($link->connect_error)
        {
            die("Connection failed " . $link->connect_error);
        }

        $query =
            "
                INSERT IGNORE INTO race_results (race_id, driver_id, constructor_id, position, points, fastest_lap_rank, fastest_lap_time)
                VALUES ('$this->race_id', '$this->driver_id', '$this->constructor_id', '$this->position', '$this->points',
                        '$this->fastest_lap_rank', '$this->fastest_lap_time')
            ";

        mysqli_query($link, $query);

        $link->close();
    }

    public function print_race_result()
    {
        if ($this->position < 10)
        {
            print "0";
        }
        print $this->position . " | " . $this->driver->getFullName() . " | " . $this->constructor->getName() . " | " . $this->points . " | " . $this->fastest_lap_time . "<br>\n";
    }

    public function set_race_id($race_id)
    {
        $this->race_id = $race_id;
    }

    private $race_id;
    private $driver_id;
    private $constructor_id;
    private $position;
    private $points;
    private $fastest_lap_rank;
    private $fastest_lap_time;
}
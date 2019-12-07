<?php

require_once("driver.php");

class raceResult
{
    public function __construct($driver, $constructor, $position, $points, $fastestLapRank, $fastestLapTime)
    {
        $this->driver = $driver;
        $this->constructor = $constructor;
        $this->position = $position;
        $this->points = $points;
        $this->fastestLapRank = $fastestLapRank;
        $this->fastestLapTime = $fastestLapTime;
    }

    public function printRaceResult()
    {
        echo $this->driver->getFullName() . PHP_EOL;
    }

    private $driver;
    private $constructor;
    private $position;
    private $points;
    private $fastestLapRank;
    private $fastestLapTime;
}
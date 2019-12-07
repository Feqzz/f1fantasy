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
        if ($this->position < 10)
        {
            print "0";
        }
        print $this->position . " | " . $this->driver->getFullName() . " | " . $this->constructor->getName() . " | " . $this->points . " | " . $this->fastestLapTime . "<br>\n";
    }

    private $driver;
    private $constructor;
    private $position;
    private $points;
    private $fastestLapRank;
    private $fastestLapTime;
}
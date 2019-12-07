<?php

require_once("season.php");

class player
{
    public function __construct($driver)
    {
        $this->driver = $driver;
    }

    public function updatePoints()
    {
        $this->points = $this->driver->getPoints();
    }

    public function getPoints()
    {
        return $this->points;
    }
    private $driver;
    private $points;

}
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
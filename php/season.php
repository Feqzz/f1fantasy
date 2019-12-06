<?php


class season
{
    function addDriver($driver)
    {
        array_push($this->drivers, $driver);
    }

    function addConstructor($constructor)
    {
        if(!in_array($constructor, $this->constructors))
        {
            array_push($this->constructors, $constructor);
        }
    }

    function addRace($race)
    {
        if(!in_array($race, $this->races))
        {
            array_push($this->races, $race);
        }
    }

    private $drivers = array();
    private $constructors = array();
    private $races = array();
}

?>
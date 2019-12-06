<?php

class driver
{
    function __construct($permanentNumber, $points, $code, $givenName, $familyName,
                         $dateOfBirth, $nationality, $driverId, $constructor)
    {
        $this->permanentNumber = $permanentNumber;
        $this->points = $points;
        $this->code = $code;
        $this->givenName = $givenName;
        $this->familyName = $familyName;
        $this->dateOfBirth = $dateOfBirth;
        $this->nationality = $nationality;
        $this->driverId = $driverId;
        $this->constructor = $constructor;
    }

    function setPrice($price)
    {
        $this->price = $price;
    }

    function changeConstructor($newConstructor)
    {
        $this->constructor = $newConstructor;
    }

    function increasePoints($newPoints)
    {
        $this->points .= $newPoints;
    }

    function getDriverId()
    {
        return $this->driverId;
    }

    function getFullName()
    {
        return ($this->givenName . " " . $this->familyName);
    }

    function getConstructor()
    {
        return $this->constructor;
    }

    private $permanentNumber;
    private $points;
    private $code;
    private $price;
    private $givenName;
    private $familyName;
    private $dateOfBirth;
    private $nationality;
    private $driverId;
    private $constructor;
}
?>
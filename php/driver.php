<?php

require_once("constructor.php");

class driver
{
    public function __construct($permanentNumber, $points, $code, $givenName, $familyName,
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

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function changeConstructor($newConstructor)
    {
        $this->constructor = $newConstructor;
    }

    public function increasePoints($newPoints)
    {
        $this->points .= $newPoints;
    }

    public function getDriverId()
    {
        return $this->driverId;
    }

    public function getFullName()
    {
        return ($this->givenName . " " . $this->familyName);
    }

    public function getConstructor()
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
<?php

require_once("constructor.php");

class driver
{
    public function __construct($permanentNumber, $points, $code, $givenName, $familyName,
                         $dateOfBirth, $nationality, $driverId, $constructor, $season)
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
        $this->season = $season;

        //VERY TEMPORARY
        $this->price = 100000;

        $constructor_id = $this->constructor->get_constructor_id();

        require_once("dbh.php");

        $link = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($link->connect_error)
        {
            die("Connection failed " . $link->connect_error);
        }

        $query =
            "
                INSERT INTO drivers (permanent_number, points, code, price, given_name, 
                family_name, date_of_birth, nationality, driver_id, constructor_id, season)
                VALUES ('$this->permanentNumber', '$this->points', '$this->code',
                 '$this->price', '$this->givenName', '$this->familyName',
                '$this->dateOfBirth', '$this->nationality', '$this->driverId', '$constructor_id', '$this->season')
            ";

        mysqli_query($link, $query);

        $link->close();
    }

    public function set_price($price)
    {
        $this->price = $price;
    }

    public function change_constructor($newConstructor)
    {
        $this->constructor = $newConstructor;
    }

    public function increase_points($newPoints)
    {
        $this->points += $newPoints;

        require_once("dbh.php");

        $link = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($link->connect_error)
        {
            die("Connection failed " . $link->connect_error);
        }

        $query =
            "
                UPDATE drivers
                SET 
                    points = '$this->points'
                WHERE
                    driver_id = '$this->driverId';
            ";

        mysqli_query($link, $query);

        $link->close();
    }

    public function get_driver_id()
    {
        return $this->driverId;
    }

    public function get_full_name()
    {
        return ($this->givenName . " " . $this->familyName);
    }

    public function get_constructor()
    {
        return $this->constructor;
    }

    public function get_points()
    {
        return $this->points;
    }

    public function get_price()
    {
        return $this->price;
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
    private $season;
}
?>
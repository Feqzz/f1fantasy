<?php

require_once("constructor.php");

class driver
{
    public function __construct($permanent_number, $points, $code, $given_name, $family_name,
                                $date_of_birth, $nationality, $driver_id, $constructor_id, $season)
    {
        $this->permanent_number = $permanent_number;
        $this->points = $points;
        $this->code = $code;
        $this->given_name = $given_name;
        $this->family_name = $family_name;
        $this->date_of_birth = $date_of_birth;
        $this->nationality = $nationality;
        $this->driver_id = $driver_id;
        $this->season = $season;
        $this->constructor_id = $constructor_id;

        require_once(dirname(__FILE__). "/../dbh.php");

        $link = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($link->connect_error)
        {
            die("Connection failed " . $link->connect_error);
        }

        $query =
            "
                INSERT IGNORE INTO drivers (permanent_number, points, code, price, given_name, 
                family_name, date_of_birth, nationality, driver_id, constructor_id, season)
                VALUES ('$this->permanent_number', '$this->points', '$this->code',
                 '$this->price', '$this->given_name', '$this->family_name',
                '$this->date_of_birth', '$this->nationality', '$this->driver_id', '$this->constructor_id', '$this->season')
            ";

        mysqli_query($link, $query);
        mysqli_close($link);
    }
    private $permanent_number;
    private $points;
    private $code;
    private $price;
    private $given_name;
    private $family_name;
    private $date_of_birth;
    private $nationality;
    private $driver_id;
    private $constructor_id;
    private $season;
}
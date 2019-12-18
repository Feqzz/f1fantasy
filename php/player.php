<?php

require_once("season.php");

class player
{
    public function __construct($id)
    {
        $this->id = $id;
        $this->money = 2000000;
        $this->points = 0;

        require_once("dbh.php");
        $link = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($link->connect_error)
        {
            die("Connection failed " . $link->connect_error);
        }

        $query =
            "
                INSERT IGNORE INTO players (id, money, points)
                VALUES ('$this->id', '$this->money', '$this->points')
            ";

        mysqli_query($link, $query);
        mysqli_close($link);
    }

    public function get_points()
    {
        return $this->points;
    }

    public function add_driver($driver)
    {
        if (!$driver->get_price() > $this->money) return false;
        $this->money -= $driver->get_price();
        array_push($this->drivers, $driver);
        return true;
    }

    public function remove_driver($driver)
    {

    }

    private $id;
    private $drivers = array();
    private $money;
    private $points;

}
<?php

require_once("season.php");

class player
{
    public function __construct($id)
    {
        $this->id = $id;
        $this->money = 2000000;
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
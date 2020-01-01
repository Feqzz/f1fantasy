<?php

require_once("season.php");

class player
{
    public function __construct($id)
    {
        $this->id = $id;
        $this->money = 2000000;
        $this->points = 0;

        require_once(dirname(__FILE__). "/../dbh.php");
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

        $resource = $link->query("SELECT * FROM players WHERE id='$id'");
        while ($row = $resource->fetch_assoc())
        {
            $this->money = "{$row['money']}";
            $this->points = "{$row['points']}";
            $this->driver_one = "{$row['driver_one']}";
            $this->driver_two = "{$row['driver_two']}";
            $this->driver_three = "{$row['driver_three']}";
            $this->driver_four = "{$row['driver_four']}";
            $this->driver_five = "{$row['driver_five']}";
        }
        mysqli_close($link);
    }
    private $id;
    private $money;
    private $points;
    private $driver_one;
    private $driver_two;
    private $driver_three;
    private $driver_four;
    private $driver_five;
}
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

    public function get_points()
    {
        return $this->points;
    }

    public function add_driver($driver)
    {
        if (($driver->get_price() > $this->money))
            return;

        require_once("dbh.php");
        $link = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($link->connect_error)
        {
            die("Connection failed " . $link->connect_error);
        }

        $this->money -= $driver->get_price();

        $query = "";
        $money_query =
            "
                UPDATE players
                SET
                    money = '$this->money'
                WHERE
                    id = '$this->id';
            ";

        if (!$this->driver_one)
        {
            $this->driver_one = $driver->get_driver_id();
            $query =
                "
                    UPDATE players
                    SET
                        driver_one = '$this->driver_one'
                    WHERE
                        id = '$this->id'
                ";
        }
        elseif(!$this->driver_two)
        {
            $this->driver_two = $driver->get_driver_id();
            $query =
                "
                    UPDATE players
                    SET
                        driver_two = '$this->driver_two'
                    WHERE
                        id = '$this->id'
                ";
        }
        elseif(!$this->driver_three)
        {
            $this->driver_three = $driver->get_driver_id();
            $query =
                "
                    UPDATE players
                    SET
                        driver_three = '$this->driver_three'
                    WHERE
                        id = '$this->id'
                ";
        }
        elseif(!$this->driver_four)
        {
            $this->driver_four = $driver->get_driver_id();
            $query =
                "
                    UPDATE players
                    SET
                        driver_four = '$this->driver_four'
                    WHERE
                        id = '$this->id'
                ";
        }
        elseif(!$this->driver_five)
        {
            $this->driver_five = $driver->get_driver_id();
            $query =
                "
                    UPDATE players
                    SET
                        driver_five = '$this->driver_five'
                    WHERE
                        id = '$this->id'
                ";
        }
        else
        {
            echo "You already have five drivers";
        }

        mysqli_query($link, $money_query);
        mysqli_query($link, $query);
        mysqli_close($link);
    }

    public function sell_driver($driver)
    {
        require_once("dbh.php");
        $link = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($link->connect_error)
        {
            die("Connection failed " . $link->connect_error);
        }

        $sell_driver = true;
        $query = "";
        $money_query =
            "
                UPDATE players
                SET
                    money = '$this->money'
                WHERE
                    id = '$this->id';
            ";
        switch ($driver->get_driver_id())
        {
            case $this->driver_one:
                $query =
                    "
                        UPDATE players
                        SET
                            driver_one = NULL
                        WHERE
                            id = '$this->id'
                    ";
                break;
            case $this->driver_two:
                $query =
                    "
                        UPDATE players
                        SET
                            driver_two = NULL
                        WHERE
                            id = '$this->id'
                    ";
                break;
            case $this->driver_three:
                $query =
                    "
                        UPDATE players
                        SET
                            driver_three = NULL
                        WHERE
                            id = '$this->id'
                    ";
                break;
            case $this->driver_four:
                $query =
                    "
                        UPDATE players
                        SET
                            driver_four = NULL
                        WHERE
                            id = '$this->id'
                    ";
                break;
            case $this->driver_five:
                $query =
                    "
                        UPDATE players
                        SET
                            driver_five = NULL
                        WHERE
                            id = '$this->id'
                    ";
                break;
            default:
                $sell_driver = false;
                break;
        }
        if ($sell_driver)
        {
            $this->money += $driver->get_price();
            mysqli_query($link, $query);
            mysqli_query($link, $money_query);
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
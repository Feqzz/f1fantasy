<?php

require_once("constructor.php");

class driver
{
    public function __construct($permanent_number, $points, $code, $given_name, $family_name,
                                $date_of_birth, $nationality, $driver_id, $season)
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
                VALUES ('$this->permanent_number', '$this->points', '$this->code',
                 '$this->price', '$this->given_name', '$this->family_name',
                '$this->date_of_birth', '$this->nationality', '$this->driver_id', '$this->constructor_id', '$this->season')
            ";

        mysqli_query($link, $query);

        $link->close();
    }

    public function change_price()
    {
        require_once("dbh.php");
        $link = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($link->connect_error)
        {
            die("Connection failed " . $link->connect_error);
        }

        $position = $i = 0;
        $resource = $link->query("SELECT * FROM race_results WHERE driver_id='$this->driver_id'");
        while ($row = $resource->fetch_assoc())
        {
            $i += 1;
            $position += "{$row['position']}";

        }

        $float = ($position/$i) * 10;
        $average = round($float);

        $this->price = 1000000 - $average*4500;

        $query =
            "
                UPDATE drivers
                SET 
                    price = '$this->price'
                WHERE
                    driver_id = '$this->driver_id';
            ";

        mysqli_query($link, $query);
        mysqli_close($link);
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
                    driver_id = '$this->driver_id';
            ";

        mysqli_query($link, $query);

        $link->close();
    }

    public function get_driver_id()
    {
        return $this->driver_id;
    }

    public function get_full_name()
    {
        return ($this->given_name . " " . $this->family_name);
    }

    public function get_constructor()
    {
        if (!empty($this->constructor))
        {
            return $this->constructor;
        }
    }

    public function get_points()
    {
        return $this->points;
    }

    public function get_price()
    {
        return $this->price;
    }

    public function set_constructor($constructor)
    {
        $this->constructor = $constructor;
        $this->constructor_id = $constructor->get_constructor_id();
    }

    public function set_constructor_id($constructor_id)
    {
        $this->constructor_id = $constructor_id;
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
    private $constructor;
    private $season;
}
?>
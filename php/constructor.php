<?php

class constructor
{
    public function __construct($constructorId, $name, $nationality, $season)
    {
        $this->constructor_id = $constructorId;
        $this->name = $name;
        $this->nationality = $nationality;
        $this->season = $season;

        require_once("dbh.php");

        $link = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($link->connect_error)
        {
            die("Connection failed " . $link->connect_error);
        }

        $query =
            "
                INSERT IGNORE INTO constructors (constructor_id, name, nationality, season)
                VALUES ('$this->constructor_id', '$this->name', '$this->nationality', '$this->season')
            ";

        mysqli_query($link, $query);


        $link->close();
    }

    public function get_constructor_id()
    {
        return $this->constructor_id;
    }

    public function getName()
    {
        return $this->name;
    }
    private $constructor_id;
    private $name;
    private $nationality;
    private $season;
}
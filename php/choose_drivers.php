<?php

require_once("dbh.php");
$link = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($link->connect_error)
{
    die("Connection failed " . $link->connect_error);
}

//Getting drivers from database
$drivers_array = array();

$resource = $link->query("SELECT * FROM drivers");
while ($row = $resource->fetch_assoc())
{
    $permanent_number = "{$row['permanent_number']}";
    $points = "{$row['points']}";
    $code = "{$row['code']}";
    $price = "{$row['price']}";
    $given_name = "{$row['given_name']}";
    $family_name = "{$row['family_name']}";
    $date_of_birth = "{$row['date_of_birth']}";
    $nationality = "{$row['nationality']}";
    $driver_id = "{$row['driver_id']}";
    $constructor_id = "{$row['constructor_id']}";
    $season = "{$row['season']}";

    $driver = new driver($permanent_number, $points, $code, $given_name, $family_name, $date_of_birth, $nationality, $driver_id, $season);
    $driver->set_constructor_id($constructor_id);
    array_push($drivers_array, $driver);
}


mysqli_close($link);

?>

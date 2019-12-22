<?php

require_once("dbh.php");
$link = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($link->connect_error)
{
    die("Connection failed " . $link->connect_error);
}

$resource = $link->query("SELECT * FROM players");
while ($row = $resource->fetch_assoc())
{
    $id = "{$row['id']}";
    $money = "{$row['money']}";
    $points = "{$row['points']}";
    $driver_one = "{$row['driver_one']}";
    $driver_two = "{$row['driver_two']}";
    $driver_three = "{$row['driver_three']}";
    $driver_four = "{$row['driver_four']}";
    $driver_five = "{$row['driver_five']}";

    if(!empty($driver_five))
    {
        $query =
            "
            INSERT IGNORE INTO player_race_results (id, driver_one, driver_two, driver_three, driver_four, driver_five)
            VALUES ('$id', '$driver_one', '$driver_two', '$driver_three', '$driver_four', '$driver_five')
        ";

        mysqli_query($link, $query);
        echo mysqli_error($link);
    }
}

mysqli_close($link);
?>

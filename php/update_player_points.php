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
    $points = "{$row['points']}";

    $total_points = (int)$points;
    $race_id = "";
    $drivers = array();
    $resource = $link->query("SELECT * FROM  player_race_results WHERE (id='$id') and (redeemed=false)");
    while ($row = $resource->fetch_assoc())
    {
        $race_id = "{$row['race_id']}";
        $driver_one = "{$row['driver_one']}";
        $driver_two = "{$row['driver_two']}";
        $driver_three = "{$row['driver_three']}";
        $driver_four = "{$row['driver_four']}";
        $driver_five = "{$row['driver_five']}";
        array_push($drivers, $driver_one, $driver_two, $driver_three, $driver_four, $driver_five);
    }
    for ($i = 0; $i < count($drivers); $i++)
    {
        $driver_id = $drivers[$i];
        $resource = $link->query("SELECT * FROM  race_results WHERE (race_id='$race_id') and (driver_id='$driver_id')");
        while ($row = $resource->fetch_assoc())
        {
            $position = "{$row['position']}";
            $driver_points = "{$row['points']}";
            $total_points += (20 - $position) + (int)$driver_points;
        }
    }
    $update_player_points_query =
        "
            UPDATE players
            SET 
                points = '$total_points'
            WHERE
                id = '$id'
        ";
    $mark_as_redeemed_query =
        "
            UPDATE player_race_results
            SET
                redeemed = true
            WHERE
                id = '$id'
        ";
    mysqli_query($link, $update_player_points_query);
    mysqli_query($link, $mark_as_redeemed_query);
}
mysqli_close($link);








?>

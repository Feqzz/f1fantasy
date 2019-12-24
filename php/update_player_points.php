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
    $circuit_id = "";
    $drivers = array();
    $resource_0 = $link->query("SELECT * FROM  player_race_results WHERE (id='$id') and (redeemed=0)");
    while ($row_0 = $resource_0->fetch_assoc())
    {
        $circuit_id = "{$row_0['circuit_id']}";
        $driver_one = "{$row_0['driver_one']}";
        $driver_two = "{$row_0['driver_two']}";
        $driver_three = "{$row_0['driver_three']}";
        $driver_four = "{$row_0['driver_four']}";
        $driver_five = "{$row_0['driver_five']}";
        array_push($drivers, $driver_one, $driver_two, $driver_three, $driver_four, $driver_five);
    }
    for ($i = 0; $i < count($drivers); $i++)
    {
        $driver_id = $drivers[$i];
        $resource_1 = $link->query("SELECT * FROM  race_results WHERE (circuit_id='$circuit_id') and (driver_id='$driver_id')");
        while ($row_1 = $resource_1->fetch_assoc())
        {
            $position = "{$row_1['position']}";
            $driver_points = "{$row_1['points']}";
            $total_points = $total_points + (20 - $position) + (int)$driver_points;
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
    echo mysqli_error($link);
    mysqli_query($link, $mark_as_redeemed_query);
}
mysqli_close($link);








?>

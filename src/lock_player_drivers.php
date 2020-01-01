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
            INSERT INTO player_race_results (id, driver_one, driver_two, driver_three, driver_four, driver_five, redeemed)
            VALUES ($id, NULLIF('$driver_one',''), NULLIF('$driver_two',''), NULLIF('$driver_three',''),
                    NULLIF('$driver_four',''), NULLIF('$driver_five','') , false)
            ON DUPLICATE KEY UPDATE
                driver_one = VALUES(driver_one),
                driver_two = VALUES(driver_two),
                driver_three = VALUES(driver_three),
                driver_four = VALUES(driver_four),
                driver_five = VALUES(driver_five),
                redeemed = VALUES(redeemed)
            ";

        $bool = mysqli_query($link, $query);
        echo mysqli_error($link);
    }
}
mysqli_close($link);


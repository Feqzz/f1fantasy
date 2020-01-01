<?php
require_once("../src/dbh.php");
require_once("../src/mysql_tables.php");
require_once("../src/classes/season.php");


require_once("../src/lock_player_drivers.php");

$link = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($link->connect_error)
{
    die("Connection failed " . $link->connect_error);
}

$year = $number_of_races = $round = 1;

$resource = $link->query("SELECT * FROM season ORDER BY id DESC LIMIT 1");
while ($row = $resource->fetch_assoc())
{
    $year = "{$row['year']}";
    $number_of_races = "{$row['number_of_races']}";
}

$season = new season($year, $number_of_races);

$get_last_round = mysqli_query($link,"SELECT * FROM races WHERE season='$year' ORDER BY round DESC LIMIT 0, 1");
$round_array = mysqli_fetch_array($get_last_round);
$round = $round_array['round'] + 1;

if($round > $number_of_races) exit;

$season->get_race_data($round);

mysqli_query($link, "UPDATE race_schedule SET is_done = true WHERE round = '$round'");
mysqli_close($link);
require_once("../src/update_player_points.php");








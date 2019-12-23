<?php
require_once("dbh.php");
require_once("mysql_tables.php");
require_once("season.php");

$round = 1;
$year = 2019;
$number_of_races = 20;

$season = new season($year,$number_of_races);

require_once("lock_player_drivers.php");

$link = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($link->connect_error)
{
    die("Connection failed " . $link->connect_error);
}

$get_last_round = mysqli_query($link,"SELECT * FROM races WHERE season='$year' ORDER BY round DESC LIMIT 0, 1");
$round_array = mysqli_fetch_array($get_last_round);

$round = $round_array['round'] + 1;
mysqli_close($link);

if($round > $number_of_races) exit;

$season->get_race_data($round);

require_once("update_player_points.php");
?>


<?php
require_once("dbh.php");

$link = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($link->connect_error)
{
    die("Connection failed " . $link->connect_error);
}

$website = "https://ergast.com/api/f1/current";
$xml = file_get_contents($website);
file_put_contents("current_schedule.xml", $xml);

$file = file_get_contents("current_schedule.xml");

$MRData = new SimpleXMLElement($file);

$season = (int)$MRData->RaceTable->attributes()->{'season'};

foreach($MRData->RaceTable->Race as $Race)
{
    $round = (int)$Race->attributes()->{'round'};
    $circuit_id = (string)$Race->Circuit->attributes()->{'circuitId'};
    $circuit_name = (string)$Race->Circuit->CircuitName;
    $country = (string)$Race->Circuit->Location->Country;
    $date = (string)$Race->Date;
    $time = (string)$Race->Time;

    $query =
        "
            INSERT INTO race_schedule (season, round, circuit_id, circuit_name, country, date, time, is_done)
            VALUES ('$season', '$round', '$circuit_id', '$circuit_name', '$country', '$date', '$time', false)
        ";
    mysqli_query($link, $query);
}
mysqli_close($link);
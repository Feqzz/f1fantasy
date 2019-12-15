<?php
require_once("dbh.php");

$link = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($link->connect_error)
{
    die("Connection failed " . $link->connect_error);
}

$season_table_query =
    "
        CREATE TABLE IF NOT EXISTS season (
        id INT AUTO_INCREMENT PRIMARY KEY,
        number_of_races INT(5),
        year INT(5),
        UNIQUE KEY (year)
        )
    ";

$drivers_table_query =
    "
        CREATE TABLE IF NOT EXISTS drivers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        permanent_number INT(5),
        points INT(5),
        code VARCHAR(30),
        price INT(5),
        given_name VARCHAR(30),
        family_name VARCHAR(30),
        date_of_birth VARCHAR(30),
        nationality VARCHAR(30),
        driver_id VARCHAR(30),
        constructor_id VARCHAR(30),
        season INT(5),
        UNIQUE KEY (driver_id),
        FOREIGN KEY (constructor_id) REFERENCES constructors(constructor_id),
        FOREIGN KEY (season) REFERENCES season(year)
        )
    ";

$races_table_query =
    "
        CREATE TABLE IF NOT EXISTS races (
        race_id VARCHAR(30) PRIMARY KEY,
        round INT,
        race_name VARCHAR(60),
        circuit_id VARCHAR(40),
        circuit_name VARCHAR(60),
        country VARCHAR(30),
        date VARCHAR(30),
        fastest_lap_time VARCHAR(30),
        fastest_lap_driver_id VARCHAR(30),
        season INT(5),
        UNIQUE KEY (date, race_id),
        FOREIGN KEY (season) REFERENCES season(year)
        )
    ";

$race_results_table_query =
    "
        CREATE TABLE IF NOT EXISTS race_results (
        race_id VARCHAR(30),
        driver_id VARCHAR(30),
        constructor_id VARCHAR(30),
        position INT,
        points INT,
        fastest_lap_rank INT,
        fastest_lap_time VARCHAR(30),
        UNIQUE KEY (race_id, driver_id),
        FOREIGN KEY (race_id) REFERENCES races(race_id),
        FOREIGN KEY (driver_id) REFERENCES drivers(driver_id),
        FOREIGN KEY (constructor_id) REFERENCES constructors(constructor_id)                        
        )
    ";

$constructor_table_query =
    "
        CREATE TABLE IF NOT EXISTS constructors (
        id INT AUTO_INCREMENT PRIMARY KEY,
        constructor_id VARCHAR(30),
        name VARCHAR(30),
        nationality VARCHAR(30),
        season INT(5),
        UNIQUE KEY (constructor_id),
        FOREIGN KEY (season) REFERENCES season(year)
        )
    ";

mysqli_query($link, $season_table_query);
mysqli_query($link, $constructor_table_query);
mysqli_query($link, $drivers_table_query);
mysqli_query($link, $races_table_query);
mysqli_query($link, $race_results_table_query);

$link->close();
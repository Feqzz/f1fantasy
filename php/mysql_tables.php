<?php
require_once("dbh.php");

$link = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($link->connect_error)
{
    die("Connection failed " . $link->connect_error);
}

$user_table_query =
    "
        CREATE TABLE IF NOT EXISTS users (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ";

$season_table_query =
    "
        CREATE TABLE IF NOT EXISTS season (
        id INT AUTO_INCREMENT PRIMARY KEY,
        number_of_races INT(5),
        year INT(5),
        UNIQUE KEY (year)
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
        circuit_id VARCHAR(40) PRIMARY KEY,
        season INT(5),
        round INT,
        race_name VARCHAR(60),
        circuit_name VARCHAR(60),
        country VARCHAR(30),
        date VARCHAR(30),
        fastest_lap_time VARCHAR(30),
        fastest_lap_driver_id VARCHAR(30),
        UNIQUE KEY (season, circuit_id),
        FOREIGN KEY (season) REFERENCES season(year),
        FOREIGN KEY (fastest_lap_driver_id) REFERENCES drivers(driver_id)
        )
    ";

$race_results_table_query =
    "
        CREATE TABLE IF NOT EXISTS race_results (
        id INT AUTO_INCREMENT PRIMARY KEY,
        circuit_id VARCHAR(40),
        season INT(5),
        driver_id VARCHAR(30),
        constructor_id VARCHAR(30),
        position INT,
        points INT,
        fastest_lap_rank INT,
        fastest_lap_time VARCHAR(30),
        UNIQUE KEY (circuit_id, driver_id, season),
        FOREIGN KEY (circuit_id) REFERENCES races(circuit_id),
        FOREIGN KEY (season) REFERENCES season(year)  ,  
        FOREIGN KEY (driver_id) REFERENCES drivers(driver_id),
        FOREIGN KEY (constructor_id) REFERENCES constructors(constructor_id)          
        )
    ";

$player_table_query =
    "
        CREATE TABLE IF NOT EXISTS players (
        id INT PRIMARY KEY,
        money INT,
        points INT,
        driver_one VARCHAR(30),
        driver_two VARCHAR(30),
        driver_three VARCHAR(30),
        driver_four VARCHAR(30),
        driver_five VARCHAR(30),
        UNIQUE KEY (id),
        FOREIGN KEY (driver_one) REFERENCES drivers(driver_id),
        FOREIGN KEY (driver_two) REFERENCES drivers(driver_id),
        FOREIGN KEY (driver_three) REFERENCES drivers(driver_id),
        FOREIGN KEY (driver_four) REFERENCES drivers(driver_id),
        FOREIGN KEY (driver_five) REFERENCES drivers(driver_id),
        FOREIGN KEY (id) REFERENCES users(id)
        )
    ";

$player_race_results_query =
    "
        CREATE TABLE IF NOT EXISTS player_race_results (
        id INT PRIMARY KEY,
        circuit_id VARCHAR(40),
        season INT(5),
        driver_one VARCHAR(30),
        driver_two VARCHAR(30),
        driver_three VARCHAR(30),
        driver_four VARCHAR(30),
        driver_five VARCHAR(30),
        redeemed BOOLEAN,
        UNIQUE KEY (id, circuit_id),
        FOREIGN KEY (id) REFERENCES users(id),
        FOREIGN KEY (circuit_id) REFERENCES races(circuit_id),
        FOREIGN KEY (season) REFERENCES season(year),
        FOREIGN KEY (driver_one) REFERENCES drivers(driver_id),
        FOREIGN KEY (driver_two) REFERENCES drivers(driver_id),
        FOREIGN KEY (driver_three) REFERENCES drivers(driver_id),
        FOREIGN KEY (driver_four) REFERENCES drivers(driver_id),
        FOREIGN KEY (driver_five) REFERENCES drivers(driver_id)
        )
    ";

$race_schedule_query =
    "
        CREATE TABLE IF NOT EXISTS race_schedule (
        season INT(5),
        round INT,
        circuit_id VARCHAR(40),
        circuit_name VARCHAR(60),
        country VARCHAR(30),
        date VARCHAR(30),
        time VARCHAR(30),
        is_done BOOLEAN,
        UNIQUE KEY (season, round),
        FOREIGN KEY (season) REFERENCES season(year)
        )
    ";

mysqli_query($link, $user_table_query);
mysqli_query($link, $season_table_query);
mysqli_query($link, $constructor_table_query);
mysqli_query($link, $drivers_table_query);
mysqli_query($link, $races_table_query);
mysqli_query($link, $race_results_table_query);
mysqli_query($link, $player_table_query);
mysqli_query($link, $player_race_results_query);
mysqli_query($link, $race_schedule_query);

mysqli_close($link);
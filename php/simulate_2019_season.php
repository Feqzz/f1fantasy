<?php

require_once("mysql_tables.php");
require_once("season.php");
require_once("dbh.php");

session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
{
    header("location: login.php");
    exit;
}

$link = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($link->connect_error)
{
    die("Connection failed " . $link->connect_error);
}

$user_id = $_SESSION["id"];
$race_to_load = 1;
$race_id = "";
$circuit_id = "";
$race_name = "";
$drivers = array();
$race_results = array();

$resource = $link->query("SELECT * FROM players WHERE id='$user_id'");
while ($row = $resource->fetch_assoc())
{
    $id = "{$row['id']}";
    $money = "{$row['money']}";
    $points = "{$row['points']}";
}

$resource = $link->query("SELECT * FROM races WHERE round='$race_to_load'");
while ($row = $resource->fetch_assoc())
{
    $race_id = "{$row['race_id']}";
    $race_name = "{$row['race_name']}";
    $circuit_id = "{$row['circuit_id']}";
}

$resource = $link->query("SELECT * FROM  player_race_results WHERE (race_id='$race_id') and (id='$id')");
while ($row = $resource->fetch_assoc())
{
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
        $points = "{$row['points']}";
        array_push($race_results, array($driver_id, $position, $points));
    }
}
mysqli_close($link);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Race results</title>
    <link rel="stylesheet" href="../bootstrap/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../bootstrap/assets/css/Navigation-Clean.css">
    <link rel="stylesheet" href="../bootstrap/assets/css/styles.css">
</head>

<body>
<div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <nav class="navbar navbar-light navbar-expand-md navigation-clean">
                    <div class="container"><a class="navbar-brand" href="welcome.php">F1 Fantasy</a><button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
                        <div
                            class="collapse navbar-collapse" id="navcol-1">
                            <ul class="nav navbar-nav ml-auto">
                                <li class="nav-item" role="presentation"><a class="nav-link active" href="#">Money: $<?php echo $money ?></a></li>
                                <li class="nav-item" role="presentation"><a class="nav-link active" href="#">Points: <?php echo $points ?></a></li>
                                <li class="nav-item" role="presentation"><a class="nav-link" href="logout.php">Log out</a></li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>
<div>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <aside>
                    <a href="choose_drivers.php">Choose drivers</a>
                </aside>
            </div>
            <div class="col-md-9">
                <h3 style="text-align:center; font-weight: bold;"><?php echo $race_name ?></h3>
                <div style="text-align:center; vertical-align:middle;">
                <img src="../bootstrap/assets/img/races/<?php echo $circuit_id?>.png" style="width:512px;height:288px; margin:auto;">
            </div>
                <p><br></p>
                <?php for ($i = 0; $i < count($drivers); $i++) { ?>
                    <div class="col-md-4">
                        <ul class="list-group">
                            <li class="list-group-item border-0">
                                <span>
                                    <img src="../bootstrap/assets/img/drivers/<?php echo $race_results[$i][0]  ?>.png" style="height:40px;width:40px;">
                                    Position: <?php echo $race_results[$i][1] ?> Points: <?php echo $race_results[$i][2] ?>
                                </span>
                            </li>
                        </ul>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>

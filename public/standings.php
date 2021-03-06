<?php
require_once("../src/dbh.php");

session_start();

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

$resource = $link->query("SELECT * FROM players WHERE id='$user_id'");
while ($row = $resource->fetch_assoc())
{
    $id = "{$row['id']}";
    $player_money = "{$row['money']}";
    $player_points = "{$row['points']}";
}

$season = 2019;
$drivers = array();

$resource = $link->query("SELECT * FROM drivers WHERE season='$season'");
while ($row = $resource->fetch_assoc())
{
    $points = 0;
    $podiums = 0;
    $wins = 0;
    $fastest_laps = 0;
    $constructor_name = "";

    $driver_id = "{$row['driver_id']}";
    $given_name = "{$row['given_name']}";
    $family_name = "{$row['family_name']}";
    $nationality = "{$row['nationality']}";
    $constructor_id = "{$row['constructor_id']}";

    $resource_0 = $link->query("SELECT * FROM  constructors WHERE (constructor_id='$constructor_id') and (season='$season')");
    while ($row_0 = $resource_0->fetch_assoc())
    {
        $constructor_name = "{$row_0['name']}";
    }

    $resource_1 = $link->query("SELECT * FROM  race_results WHERE (driver_id='$driver_id') and (season='$season')");
    while ($row_1 = $resource_1->fetch_assoc())
    {
        $position = "{$row_1['position']}";
        $points_db = "{$row_1['points']}";
        $fastest_lap_rank = "{$row_1['fastest_lap_rank']}";
        if ($position < 4)
        {
            $podiums++;
            if ($position == 1)
            {
                $wins++;
            }
        }
        if ($fastest_lap_rank == 1)
        {
            $fastest_laps++;
        }
        $points += (int)$points_db;
    }
    $full_name = $given_name . " " . $family_name;
    array_push($drivers, array($driver_id, $full_name, $nationality, $constructor_name, $fastest_laps, $podiums, $wins, $points));
}

array_multisort(array_map(function($element) {
    return $element[7];
}, $drivers), SORT_DESC, $drivers);

mysqli_close($link);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Race results</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/Navigation-Clean.css">
    <link rel="stylesheet" href="css/styles.css">
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
                                <li class="nav-item" role="presentation"><a class="nav-link active" href="#">Money: $<?php echo $player_money ?></a></li>
                                <li class="nav-item" role="presentation"><a class="nav-link active" href="#">Points: <?php echo $player_points ?></a></li>
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
                <div class="bg-light border-0" id="sidebar-wrapper">
                    <div class="list-group list-group-flush">
                        <a href="driver_display.php" class="list-group-item list-group-item-action ">Driver display</a>
                        <a href="last_race_result.php" class="list-group-item list-group-item-action ">Last race result</a>
                        <a href="standings.php" class="list-group-item list-group-item-action " style="font-weight: bold">Standings</a>
                        <a href="leaderboard.php" class="list-group-item list-group-item-action ">Leaderboard</a>
                        <a href="upcoming_races.php" class="list-group-item list-group-item-action ">Upcoming races</a>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <h3 style="text-align:center; font-weight: bold;">Driver standings <?php echo $season ?></h3>
                <p><br></p>
                <table class="table">
                    <tr>
                        <th scope="col">Position</th>
                        <th scope="col"></th>
                        <th scope="col">Full name</th>
                        <th scope="col">Nationality</th>
                        <th scope="col">Constructor</th>
                        <th scope="col">Fastest laps</th>
                        <th scope="col">Podiums</th>
                        <th scope="col">Wins</th>
                        <th scope="col">Points</th>
                    </tr>
                    <tbody>
                        <?php for ($i = 0; $i < count($drivers); $i++) { ?>
                            <tr>
                                <th scope="row"><?php echo $i + 1; ?> </th>
                                <td><img src="images/drivers/<?php echo $drivers[$i][0]  ?>.png" style="height:40px;width:40px;"></td>
                                <?php for ($j = 1; $j < 8; $j++ ) { ?>
                                <td><?php echo $drivers[$i][$j] ?></td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
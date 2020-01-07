<?php
require_once("../src/dbh.php");

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

$user_id = $_SESSION['id'];
$user_money = $user_points = 0;

$resource = $link->query("SELECT * FROM players where id='$user_id'");
while ($row = $resource->fetch_assoc())
{
    $user_money = "{$row['money']}";
    $user_points = "{$row['points']}";
}

$last_race_round = 0;

$get_last_round = mysqli_query($link,"SELECT * FROM races WHERE season='2019' ORDER BY round DESC LIMIT 0, 1");
$round_array = mysqli_fetch_array($get_last_round);

$last_race_round = $round_array['round'];

function fix_date_and_time(&$date, &$time)
{
    $new_date = $new_time = "";
    $months = array("January","February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    if($date[8] != 0) $new_date .= $date[8];
    $new_date .= $date[9] . ". ";
    if($date[5] == 0)
    {
        $new_date .= $months[(int)$date[6] - 1];
    }
    else
    {
        $new_date .= $months[9 + (int)$date[6]];
    }

    if($time[1] == 9)
    {
        $new_time .= ((int)$time[0] + 1) . "0";

    }
    else
    {
        $new_time .= $time[0] . ((int)$time[1] + 1);
    }
    for ($i = 2; $i < 5; $i++)
    {
        $new_time .= $time[$i];
    }
    $date = $new_date;
    $time = $new_time;
}

$races = array();

$resource_0 = $link->query("SELECT * FROM race_schedule WHERE (round > '$last_race_round') and (is_done = false)");
while($row_0 = $resource_0->fetch_assoc())
{
    $season = "{$row_0['season']}";
    $round = "{$row_0['round']}";
    $circuit_id = "{$row_0['circuit_id']}";
    $circuit_name = "{$row_0['circuit_name']}";
    $country = "{$row_0['country']}";
    $date = "{$row_0['date']}";
    $time = "{$row_0['time']}";

    fix_date_and_time($date, $time);
    array_push($races, array($round, $circuit_id, $circuit_name, $country, $date, $time, $season));
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Race results</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/Navigation-Clean.css">
    <link rel="stylesheet" href="../css/styles.css">
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
                                <li class="nav-item" role="presentation"><a class="nav-link active" href="#">Money: $<?php echo $user_money ?></a></li>
                                <li class="nav-item" role="presentation"><a class="nav-link active" href="#">Points: <?php echo $user_points ?></a></li>
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
                        <a href="standings.php" class="list-group-item list-group-item-action ">Standings</a>
                        <a href="leaderboard.php" class="list-group-item list-group-item-action ">Leaderboard</a>
                        <a href="upcoming_races.php" class="list-group-item list-group-item-action " style="font-weight: bold">Upcoming races</a>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <h3 style="text-align:center; font-weight: bold;">Upcoming races</h3>
                <div style="text-align:center; vertical-align:middle;">
                    <img src="../images/races/<?php echo $races[0][1]?>.png" style="width:512px;height:288px; margin:auto;">
                    <h3><?php echo $races[0][2]?></h3>
                </div>
                <p><br></p>
                <table class="table">
                    <tr>
                        <th scope="col">Round</th>
                        <th scope="col">Circuit Name</th>
                        <th scope="col">Country</th>
                        <th scope="col">Date</th>
                        <th scope="col">Time</th>
                        <th scope="col">Season</th>
                    </tr>
                    <tbody>
                    <?php for ($i = 0; $i < count($races); $i++) { ?>
                        <tr>
                            <th scope="row"><?php echo $races[$i][0] ?> </th>
                            <?php for ($j = 2; $j < 7; $j++ ) { ?>
                                <td><?php echo $races[$i][$j] ?></td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
</body>
</html>
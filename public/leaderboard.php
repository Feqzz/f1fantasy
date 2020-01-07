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
$player_points = $player_money = 0;
$players = array();

$resource = $link->query("SELECT * FROM players WHERE id='$user_id'");
while ($row = $resource->fetch_assoc())
{
    $player_money = "{$row['money']}";
    $player_points = "{$row['points']}";
}

$resource = $link->query("SELECT * FROM players");
while ($row = $resource->fetch_assoc())
{
    $id = "{$row['id']}";
    $money = "{$row['money']}";
    $points = "{$row['points']}";

    $resource_0 = $link->query("SELECT * FROM users WHERE id='$id'");
    while ($row_0 = $resource_0->fetch_assoc())
    {
        $player_username = "{$row_0['username']}";
    }
    if($points != 0)
        array_push($players, array($id, $player_username, $points));
}

array_multisort(array_map(function($element) {
    return $element[2];
}, $players), SORT_DESC, $players);

$times = 10;
if (count($players) < 10) $times = count($players);
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
                        <a href="driver_display.php" class="list-group-item list-group-item-action " >Driver display</a>
                        <a href="last_race_result.php" class="list-group-item list-group-item-action ">Last race result</a>
                        <a href="standings.php" class="list-group-item list-group-item-action ">Standings</a>
                        <a href="leaderboard.php" class="list-group-item list-group-item-action " style="font-weight: bold">Leaderboard</a>
                        <a href="upcoming_races.php" class="list-group-item list-group-item-action ">Upcoming races</a>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <h3 style="text-align:center; font-weight: bold;">The top 10 players</h3>
                <p><br></p>
                <table class="table">
                    <tr>
                        <th scope="col">Position</th>
                        <th scope="col">Username</th>
                        <th scope="col">Points</th>
                    </tr>
                    <tbody>
                    <?php for ($i = 0; $i < $times; $i++) { if($user_id == $players[$i][0]) { ?>
                        <tr class="table-info">
                        <?php } else { ?>
                        <tr>
                        <?php } ?>
                            <th scope="row"><?php echo $i + 1; ?> </th>
                            <?php for ($j = 1; $j < 3; $j++ ) { ?>
                                <td><?php echo $players[$i][$j] ?></td>
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
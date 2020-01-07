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
$money = 0;
$points = 0;
$player_drivers = array();

$resource = $link->query("SELECT * FROM players WHERE id='$user_id'");
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
    array_push($player_drivers, array($driver_one), array($driver_two),
        array($driver_three), array($driver_four), array($driver_five));
}

for ($i = 0; $i < count($player_drivers); $i++)
{
    $driver_id = $player_drivers[$i][0];
    $resource = $link->query("SELECT * FROM drivers WHERE driver_id='$driver_id'");
    while ($row = $resource->fetch_assoc())
    {
        $driver_id_db = "{$row['driver_id']}";
        $price = "{$row['price']}";
        $given_name = "{$row['given_name']}";
        $family_name = "{$row['family_name']}";
        $full_name = $given_name . " " . $family_name;
        $player_drivers[$i][] = $price;
        $player_drivers[$i][] = $full_name;
    }
}
$driver_to_sell = array();

for ($i = 0; $i < 5; $i++)
{
    $string = "active_slot_" . $i;
    if(isset($_GET[$string]))
    {
        if (!empty($player_drivers[$i][0]))
        {
            $driver_to_sell = $player_drivers[$i];
            $_SESSION['driver_to_sell'] = $driver_to_sell;
        }
        else
        {
            header("location: buy_menu.php");
            $_SESSION['buy_driver_slot'] = $i;
        }
    }
}

if (isset($_POST['sell']))
{
    $driver_to_sell = $_SESSION['driver_to_sell'];
    if(($driver_to_sell[0]))
    {
        $sell_driver = true;
        $query = "";
        switch ($driver_to_sell[0])
        {
            case $player_drivers[0][0]:
                $query =
                    "
                        UPDATE players
                        SET
                            driver_one = NULL
                        WHERE
                            id = '$user_id'
                    ";
                break;
            case $player_drivers[1][0]:
                $query =
                    "
                        UPDATE players
                        SET
                            driver_two = NULL
                        WHERE
                            id = '$user_id'
                    ";
                break;
            case $player_drivers[2][0]:
                $query =
                    "
                        UPDATE players
                        SET
                            driver_three = NULL
                        WHERE
                            id = '$user_id'
                    ";
                break;
            case $player_drivers[3][0]:
                $query =
                    "
                        UPDATE players
                        SET
                            driver_four = NULL
                        WHERE
                            id = '$user_id'
                    ";
                break;
            case $player_drivers[4][0]:
                $query =
                    "
                        UPDATE players
                        SET
                            driver_five = NULL
                        WHERE
                            id = '$user_id'
                    ";
                break;
            default:
                $sell_driver = false;
                break;
        }
        if ($sell_driver)
        {
            $money += (int)$driver_to_sell[1];
            $money_query =
                "
                UPDATE players
                SET
                    money = '$money'
                WHERE
                    id = '$user_id'
            ";
            mysqli_query($link, $query);
            mysqli_query($link, $money_query);
        }
        mysqli_close($link);

        $driver_to_sell = array();
        header("location: driver_display.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>F1 Fantasy</title>
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
                <div class="bg-light border-0" id="sidebar-wrapper">
                    <div class="list-group list-group-flush">
                        <a href="driver_display.php" class="list-group-item list-group-item-action " style="font-weight: bold">Driver display</a>
                        <a href="last_race_result.php" class="list-group-item list-group-item-action ">Last race result</a>
                        <a href="standings.php" class="list-group-item list-group-item-action ">Standings</a>
                        <a href="leaderboard.php" class="list-group-item list-group-item-action ">Leaderboard</a>
                        <a href="upcoming_races.php" class="list-group-item list-group-item-action ">Upcoming races</a>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="container">
                    <div class="row">
                        <?php for ($i = 0; $i < 5; $i++) { ?>
                            <div class="col-md-4">
                                <a href="?active_slot_<?php echo $i; ?>" style="text-decoration: none">
                                    <div class="card border-0">
                                        <div class="card-body">
                                            <img src="images/drivers/<?php if(!empty($player_drivers[$i][0])) {echo $player_drivers[$i][0];} else {echo "empty";}?>.png" style="height:200px;width:200px;">
                                            <h6 class="text-muted card-subtitle mb-2"><br> <?php if(!empty($player_drivers[$i][0])) {echo $player_drivers[$i][2];} else {echo "Available";} ?> <br> <?php if (!empty($player_drivers[$i][0])) {echo  "$" . $player_drivers[$i][1];} ?></h6>
                                            <h6 class="text-muted card-subtitle mb-2"> <?php if ((!empty($player_drivers[$i][0])) && $_SESSION['driver_to_sell'][0] == $player_drivers[$i][0]) { ?>
                                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                                        <button class="btn btn-danger" type="submit" style="width: 200px; background-color: rgb(255,57,57);height: 38px;" value="Submit" name="sell">Sell</button>
                                                    </form>
                                                    <?php
                                                } else { echo "<br>" . "<br>"; }  ?></h6>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>


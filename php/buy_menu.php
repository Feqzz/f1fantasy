<?php
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

$user_id = $_SESSION['id'];
$money = 0;
$points = 0;
$buy_slot = $_SESSION['buy_driver_slot'];
$drivers = array();
$player_drivers = array();

$resource = $link->query("SELECT * FROM players WHERE id='$user_id'");
while ($row = $resource->fetch_assoc())
{
    $money = "{$row['money']}";
    $points = "{$row['points']}";
    $driver_one = "{$row['driver_one']}";
    $driver_two = "{$row['driver_two']}";
    $driver_three = "{$row['driver_three']}";
    $driver_four = "{$row['driver_four']}";
    $driver_five = "{$row['driver_five']}";
    array_push($player_drivers, $driver_one, $driver_two, $driver_three, $driver_four, $driver_five);
}

$resource = $link->query("SELECT * FROM drivers");
while ($row = $resource->fetch_assoc())
{
    $driver_id_db = "{$row['driver_id']}";
    $price = "{$row['price']}";
    $given_name = "{$row['given_name']}";
    $family_name = "{$row['family_name']}";
    $full_name = $given_name . " " . $family_name;
    array_push($drivers, array($driver_id_db, $price, $full_name));
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['buy']))
{

    $driver_id = trim($_POST['buy']);
    for ($i = 0; $i < count($drivers); $i++)
    {
        if ($drivers[$i][0] == $driver_id)
        {
            $chosen_driver = $drivers[$i][0];
            $chosen_driver_price = $drivers[$i][1];
            break;
        }
    }
    if(!empty($chosen_driver))
    {
        $buy_driver = true;
        $query = "";
        if(empty($player_drivers[$buy_slot]))
        {
            switch ($buy_slot)
            {
                case 0:
                    $query =
                        "
                            UPDATE players
                            SET
                                driver_one = '$chosen_driver'
                            WHERE
                                id = '$user_id'
                        ";
                    break;
                case 1:
                    $query =
                        "
                            UPDATE players
                            SET
                                driver_two = '$chosen_driver'
                            WHERE
                                id = '$user_id'
                        ";
                    break;
                case 2:
                    $query =
                        "
                            UPDATE players
                            SET
                                driver_three = '$chosen_driver'
                            WHERE
                                id = '$user_id'
                        ";
                    break;
                case 3:
                    $query =
                        "
                            UPDATE players
                            SET
                                driver_four = '$chosen_driver'
                            WHERE
                                id = '$user_id'
                        ";
                    break;
                case 4:
                    $query =
                        "
                            UPDATE players
                            SET
                                driver_five = '$chosen_driver'
                            WHERE
                                id = '$user_id'
                        ";
                    break;
            }
        }
        else
        {
            //The buy slot was not empty.
            $buy_driver = false;
        }
        if ($buy_driver)
        {
            $money -= (int)$chosen_driver_price;
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

    }
    header("location: welcome.php");
    exit;
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>F1 Fantasy</title>
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
            <div class="bg-light border-0" id="sidebar-wrapper">
                <div class="list-group list-group-flush">
                    <a href="driver_display.php" class="list-group-item list-group-item-action ">Driver display</a>
                    <a href="show_race_result.php" class="list-group-item list-group-item-action ">Last race result</a>
                    <a href="standings.php" class="list-group-item list-group-item-action ">Standings</a>
                </div>
            </div>
        </div>
            <div class="col-md-9">
                <div class="container">
                    <div class="row">
                        <?php for ($i = 0; $i < count($drivers); $i++) { if(!(in_array($drivers[$i][0],$player_drivers))) { ?>
                        <div class="col-md-4">
                            <div class="card border-0">
                                <div class="card-body">
                                    <img src="../bootstrap/assets/img/drivers/<?php echo $drivers[$i][0]; ?>.png" style="height:200px;width:200px;">
                                    <h6 class="text-muted card-subtitle mb-2"><br> <?php echo $drivers[$i][2]; ?> <br> $<?php echo $drivers[$i][1]; ?></h6>
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                        <button class="btn btn-danger <?php if ($drivers[$i][1] > $money) echo "disabled"; ?>" <?php if ($drivers[$i][1] > $money) echo "disabled"; ?>  type="submit" style="width: 200px; background-color: rgb(255,57,57);height: 38px;" value="<?php echo $drivers[$i][0];?>" name="buy" >Buy</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php } } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

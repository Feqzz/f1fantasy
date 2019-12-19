<?php
require_once("driver.php");
require_once("player.php");
require_once("dbh.php");

session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["has_player"]) || $_SESSION["has_player"] !== true){
    header("location: welcome.php");
    exit;
}

$link = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($link->connect_error)
{
    die("Connection failed " . $link->connect_error);
}

//Getting drivers from database
$sell_driver = false;
$driver_to_sell = false;
$drivers_array = array();
$chosen_driver = null;
$user_id = $_SESSION["id"];
$test = "";
$money = null;
$points = null;
$driver_one = $driver_two = $driver_three = $driver_four = $driver_five = "";

$resource = $link->query("SELECT * FROM drivers");
while ($row = $resource->fetch_assoc())
{
    $permanent_number = "{$row['permanent_number']}";
    $points = "{$row['points']}";
    $code = "{$row['code']}";
    $price = "{$row['price']}";
    $given_name = "{$row['given_name']}";
    $family_name = "{$row['family_name']}";
    $date_of_birth = "{$row['date_of_birth']}";
    $nationality = "{$row['nationality']}";
    $driver_id = "{$row['driver_id']}";
    $constructor_id = "{$row['constructor_id']}";
    $season = "{$row['season']}";

    $driver = new driver($permanent_number, $points, $code, $given_name, $family_name,
        $date_of_birth, $nationality, $driver_id, $season);
    $driver->set_constructor_id($constructor_id);
    $driver->set_price($price);
    array_push($drivers_array, $driver);
}

$resource = $link->query("SELECT * FROM players WHERE id='$user_id'");
while ($row = $resource->fetch_assoc()) {
    $id = "{$row['id']}";
    $money = "{$row['money']}";
    $points = "{$row['points']}";
    $driver_one = "{$row['driver_one']}";
    $driver_two = "{$row['driver_two']}";
    $driver_three = "{$row['driver_three']}";
    $driver_four = "{$row['driver_four']}";
    $driver_five = "{$row['driver_five']}";
}

for ($i = 0; $i < count($drivers_array); $i++)
{
    switch ($drivers_array[$i]->get_driver_id())
    {
        case $driver_one:
            $driver_one = $drivers_array[$i];
            break;
        case $driver_two:
            $driver_two = $drivers_array[$i];
            break;
        case $driver_three:
            $driver_three = $drivers_array[$i];
            break;
        case $driver_four:
            $driver_four = $drivers_array[$i];
            break;
        case $driver_five:
            $driver_five = $drivers_array[$i];
            break;
    }
}

$player = new player($id);

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['products']))
{
    $driver_full_name = trim($_POST['products']);
    for ($i = 0; $i < count($drivers_array); $i++)
    {
        if ($drivers_array[$i]->get_full_name() == $driver_full_name)
        {
            $chosen_driver = $drivers_array[$i];
            break;
        }
    }
    if(!empty($chosen_driver))
    {
        $player->add_driver($chosen_driver);
    }
    header("location: choose_drivers.php");
    exit;
}

//Yes I know this looks very dumb lmao.
$driver_to_sell = $_SESSION['driver_to_sell'];

if(isset($_GET['sell_driver_one']))
{
    $_SESSION['driver_to_sell'] = $driver_one;
}
if(isset($_GET['sell_driver_two']))
{
    $_SESSION['driver_to_sell'] = $driver_two;
}
if(isset($_GET['sell_driver_three']))
{
    $_SESSION['driver_to_sell'] = $driver_three;
}
if(isset($_GET['sell_driver_four']))
{
    $_SESSION['driver_to_sell'] = $driver_four;
}
if(isset($_GET['sell_driver_five']))
{
    $_SESSION['driver_to_sell'] = $driver_five;
}

if (isset($_POST['sell']))
{
    if($driver_to_sell)
    {
        $player->sell_driver($_SESSION['driver_to_sell']);
        $driver_to_sell = null;
        $_SESSION['driver_to_sell'] = $driver_to_sell;
        header("location: choose_drivers.php");
        exit;
    }
}

$driver_to_sell = $_SESSION['driver_to_sell'];






mysqli_close($link);

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
                    <div class="container"><a class="navbar-brand" href="#">F1 Fantasy</a><button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
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
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <select name="products">
                            <option select="selected">Choose one</option>
                            <?php
                            for ($i = 0; $i < count($drivers_array); $i++)
                            {
                                $item = $drivers_array[$i]->get_full_name();
                                ?>
                                <option value="<?php echo $item; ?>"><?php echo $item; ?>
                                </option>
                                <?php
                            }
                            ?>
                        </select>
                        <input type="submit" value="Submit">
                    </form>
                    <a href="simulate_2019_season.php">Simulate 2019 Season</a>
                </aside>
            </div>
            <div class="col-md-9">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4">
                            <a href="?sell_driver_one" style="text-decoration: none">
                            <div class="card border-0">
                                <div class="card-body">
                                    <img src="../bootstrap/assets/img/drivers/<?php if($driver_one) {echo $driver_one->get_driver_id();} else {echo "empty";}?>.png" style="height:200px;width:200px;">
                                    <h6 class="text-muted card-subtitle mb-2"><br> <?php if($driver_one) {echo $driver_one->get_full_name();} else {echo "Available";} ?> <br> $<?php if ($driver_one) {echo $driver_one->get_price();} ?></h6>
                                </div>
                            </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="?sell_driver_two" onclick="" style="text-decoration: none">
                                <div class="card border-0">
                                    <div class="card-body">
                                        <img src="../bootstrap/assets/img/drivers/<?php if($driver_two) {echo $driver_two->get_driver_id();} else {echo "empty";}?>.png" style="height:200px;width:200px;">
                                        <h6 class="text-muted card-subtitle mb-2"><br> <?php if($driver_two) {echo $driver_two->get_full_name();} else {echo "Available";} ?> <br> $<?php if ($driver_two) {echo $driver_two->get_price();} ?></h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="?sell_driver_three" style="text-decoration: none">
                                <div class="card border-0">
                                    <div class="card-body">
                                        <img src="../bootstrap/assets/img/drivers/<?php if($driver_three) {echo $driver_three->get_driver_id();} else {echo "empty";}?>.png" style="height:200px;width:200px;">
                                        <h6 class="text-muted card-subtitle mb-2"><br> <?php if($driver_three) {echo $driver_three->get_full_name();} else {echo "Available";} ?> <br> $<?php if ($driver_three) {echo $driver_three->get_price();} ?></h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="?sell_driver_four" style="text-decoration: none">
                                <div class="card border-0">
                                    <div class="card-body border-0">
                                        <img src="../bootstrap/assets/img/drivers/<?php if($driver_four) {echo $driver_four->get_driver_id();} else {echo "empty";}?>.png" style="height:200px;width:200px;">
                                        <h6 class="text-muted card-subtitle mb-2"><br> <?php if($driver_four) {echo $driver_four->get_full_name();} else {echo "Available";} ?> <br> <?php if ($driver_four) {echo "$" . $driver_four->get_price();} else {echo "-";} ?></h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="?sell_driver_five" style="text-decoration: none">
                                <div class="card border-0">
                                    <div class="card-body">
                                        <img src="../bootstrap/assets/img/drivers/<?php if($driver_five) {echo $driver_five->get_driver_id();} else {echo "empty";}?>.png" style="height:200px;width:200px;">
                                        <h6 class="text-muted card-subtitle mb-2"><br> <?php if($driver_five) {echo $driver_five->get_full_name();} else {echo "Available";} ?> <br> $<?php if ($driver_five) {echo $driver_five->get_price();} ?></h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0">
                                <div class="card-body">
                                    <h6 class="text-muted card-subtitle mb-2">Toggle a driver that you want to sell. <br> <?php if($driver_to_sell) echo $_SESSION['driver_to_sell']->get_full_name() . " is toggled and ready to be sold." ?></h6>
                                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                        <button class="btn btn-primary" type="submit" style="width: 200px; background-color: rgb(255,57,57);height: 48px;" value="Submit" name="sell">Sell</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../bootstrap/assets/js/jquery.min.js"></script>
<script src="../bootstrap/assets/js/bootstrap.min.js"></script>
</body>
</html>
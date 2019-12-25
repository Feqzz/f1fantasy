<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
{
    header("location: login.php");
    exit;
}
require_once("player.php");
require_once("dbh.php");
$link = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($link->connect_error)
{
    die("Connection failed " . $link->connect_error);
}

$_SESSION['driver_to_sell'] = false;
$_SESSION['buy_driver_slot'] = 0;

if($_SESSION["loggedin"])
{
    $user_id = $_SESSION["id"];
    $found = null;
    $sql = "SELECT count(1) FROM players WHERE id = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "s", $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $found);
    mysqli_stmt_fetch($stmt);
    if ($found)
    {
        header("location: driver_display.php");
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['play_button'])) {
        //Make a new player object. The constructor will send it to the database. :)
        $players = new player($user_id);
        header("location: driver_display.php");
    }
}
mysqli_close($link);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Welcome</title>
    <link rel="stylesheet" href="../bootstrap/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../bootstrap/assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="../bootstrap/assets/css/Features-Clean.css">
    <link rel="stylesheet" href="../bootstrap/assets/css/styles.css">
</head>

<body>
<div class="features-clean">
    <div class="container">
        <div class="intro">
            <h2 class="text-center">F1 Fantasy</h2>
            <p class="text-center">Spill laget Stian Onarheim https://github.com/Feqzz <br/> Grafisk design av Kornelius Hauge </p>
        </div>
        <div class="row features">
            <div class="col-sm-6 col-lg-4 item"><i class=""></i>
                <h3 class="name">How to play</h3>
                <p class="description">Buy five drivers with the money you have. As the races goes by, you score points.</p>
            </div>
            <div class="col-sm-6 col-lg-4 item"><i class="fa fa-clock-o icon"></i>
                <h3 class="name">Replay 2019 Season</h3>
                <p class="description">While we wait for the 2020 season to start. Try replaying the 2019 season.</p>
            </div>
            <div class="col-sm-6 col-lg-4 item"><i class="fa fa-list-alt icon"></i>
                <h3 class="name">2020 Season</h3>
                <p class="description">The drivers will be ready 15. March in Melbourne, Australia. </p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col text-center">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <button class="btn btn-danger" type="submit" style="width: 256px; background-color: rgb(255,57,57);height: 64px;" value="Submit" name="play_button">Start Playing!
            </button>
            </form>
        </div>
    </div>
</div>
<script src="../bootstrap/assets/js/jquery.min.js"></script>
<script src="../bootstrap/assets/js/bootstrap.min.js"></script>
</body>
</html>

<?php
require_once("driver.php");
require_once("dbh.php");

session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}



$link = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($link->connect_error)
{
    die("Connection failed " . $link->connect_error);
}

//Getting drivers from database
$drivers_array = array();
$chosen_driver = null;
$user_id = $_SESSION["id"];

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

    $driver = new driver($permanent_number, $points, $code, $given_name, $family_name, $date_of_birth, $nationality, $driver_id, $season);
    $driver->set_constructor_id($constructor_id);
    array_push($drivers_array, $driver);
}

if ($_SERVER['REQUEST_METHOD'] == "POST")
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
    echo $chosen_driver->get_driver_id();
}


mysqli_close($link);

?>

<html>
<head>
    <meta charset="utf-8">
    <title>Choose Drivers</title>
</head>
<body>
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
</body>
</html>



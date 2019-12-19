<?php
    session_start();
    $_SESSION = array();
    session_destroy();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="refresh" content="3; login.php" />
    <title>Log out</title>
    <link rel="stylesheet" href="../bootstrap/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../bootstrap/assets/css/styles.css">
</head>

<body>
<h1 align="center">You have successfully logged out!</h1>
<script src="../bootstrap/assets/js/jquery.min.js"></script>
<script src="../bootstrap/assets/js/bootstrap.min.js"></script>
</body>

</html>

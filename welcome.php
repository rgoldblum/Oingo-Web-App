<?php

//check if user is logged in
require_once "check_login.php";

$uid = $_SESSION["uid"];
$username = $_SESSION["username"];

echo "<h1> Welcome to Oingo ".$username."!";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
          <a href="mainpage.php" class="btn btn-primary">Map Page</a>
          <a href="logout.php" class="btn btn-primary">Log Out</a>
    </div>
</body>
</html>

<?php

//Get session variables
session_start();
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
    </div>
</body>
</html>

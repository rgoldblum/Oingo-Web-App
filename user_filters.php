<?php
//check if user is logged in
require_once "check_login.php";

// Include config file
require_once "config.php";
?>

<!DOCTYPE html>
<html>

  <head>
    <title>My Filters</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">

    </style>
  </head>

  <body>
    <nav class="navbar navbar-inverse">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="mainpage.php">Oingo</a>
        </div>
        <ul class="nav navbar-nav">
          <li><a href="mainpage.php">Home</a></li>
          <li><a href="user_notes.php">My Notes</a></li>
          <li class="active"><a href="user_filters.php">My Filters</a></li>
          <li><a href="user_states.php">My States</a></li>
          <li><a href="user_friends.php">My Friends</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
        </ul>
      </div>
    </nav>

    <?php
      echo "<h1> Filters made by ".$username."</h1>";

      //fetch filters written by user

      // Prepare a select statement
      $sql = "SELECT * FROM filters NATURAL JOIN tag WHERE uid = ?";

      if($stmt = $conn->prepare($sql)){

        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("i", $param_uid);

        // Set parameters
        $param_uid = $uid;

        // Attempt to execute the prepared statement
        if($stmt->execute()){
          //store results
          $result = $stmt->get_result();

          if($result->num_rows > 0) {
            //output first note
            $row = $result->fetch_assoc();

            //start table
            echo "<table class='table table-hover'>
                    <thead class='thead-dark'>
                      <tr>
                        <th>FID</th>
                        <th>Name</th>
                        <th>SID</th>
                        <th>Tag</th>
                        <th>Privacy</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                      </tr>
                    </thead>";

            //add first row
            echo "<tbody>
                    <tr>
                      <td>".$row["fid"]."</td>
                      <td>".$row["fname"]."</td>
                      <td>".$row["sid"]."</td>
                      <td>".$row["ttext"]."</td>
                      <td>".$row["filter_privacy"]."</td>
                      <td>".$row["latitude"]."</td>
                      <td>".$row["longitude"]."</td>
                    </tr>";



            //iterate through rows
            while ($row = $result->fetch_assoc()) {
              //add rest of rows
              echo "<tr>
                      <td>".$row["fid"]."</td>
                      <td>".$row["fname"]."</td>
                      <td>".$row["sid"]."</td>
                      <td>".$row["ttext"]."</td>
                      <td>".$row["filter_privacy"]."</td>
                      <td>".$row["latitude"]."</td>
                      <td>".$row["longitude"]."</td>
                    </tr>";
            }

            echo "</tbody>";

          } else {
              echo "You have not made any filters";
          }

          echo "</br></br><a href='new_filter.php' class='btn btn-primary'>Create a New Filter</a>";



        } else{
            echo "Error: Statement did not execute".mysqli_error($conn);
        }
      }
    ?>
    </body>
</html>

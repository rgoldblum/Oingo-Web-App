<?php
//check if user is logged in
require_once "check_login.php";

// Include config file
require_once "config.php";

$sname = "";
$sname_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
  if(isset($_POST["new_active_sid"])) {
    $new_active_sid = $_POST["new_active_sid"];
    //echo $new_active_sid;

    //prepare sql statements
    $sql_old_active = "UPDATE state SET isActive = ? WHERE uid = ? AND isActive = ? ";
    $sql_new_active = "UPDATE state SET isActive = ? WHERE uid = ? AND sid = ? ";

    if($stmt = $conn->prepare($sql_old_active)) {
      //bind params
      $stmt->bind_param("sis", $param_new_isActive, $param_uid, $param_old_isActive);

      //set params
      $param_new_isActive = "False";
      $param_uid = $uid;
      $param_old_isActive = "True";

      //execute statement
      if($stmt->execute()) {
        //prepare next statement
        if($stmt = $conn->prepare($sql_new_active)) {
          //bind params
          $stmt->bind_param("sii", $param_new_isActive, $param_uid, $param_sid);

          //set $params
          $param_new_isActive = "True";
          $param_uid = $uid;
          $param_sid = $new_active_sid;

          if($stmt->execute()) {
            // Redirect back to page to clear post request
            header("location: user_states.php");


          } else {
              echo "Error: Statemet was not executed: ".mysqli_error($conn);
          }
        } else {
            echo "Error: Statemet was not prepared: ".mysqli_error($conn);
        }


      } else {
          echo "Error: Statemet was not executed: ".mysqli_error($conn);
      }

    } else {
        echo "Error: Statemet was not prepared: ".mysqli_error($conn);
    }

  } else {

      //validate $sname
      if(empty(trim($_POST["sname"]))){
          $sname_err = "Please enter a name for your new state.";
      } else {
          $sname = trim($_POST["sname"]);
      }

      if(empty($sname_err)) {
        //prepare insert statement

        $sql = "INSERT INTO state (uid, sname, isActive) VALUES (?, ?, ?)";

        if($stmt = $conn->prepare($sql)) {
            //bind parameters
            $stmt->bind_param("iss", $param_uid, $param_sname, $param_isActive);

            //set parameters
            $param_uid = $uid;
            $param_sname = $sname;
            $param_isActive = "False";

            //exectute statment
            if($stmt->execute()) {
              // Redirect back to page to clear post request
              header("location: user_states.php");

            } else {
                echo "Error: Statement not executed: ".mysqli_error($conn);
            }
        } else {
          echo "Error: Statement not prepared: ".mysqli_error($conn);
        }
      }

  }

}

?>

<!DOCTYPE html>
<html>

  <head>
    <title>My States</title>
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
          <li><a href="user_filters.php">My Filters</a></li>
          <li class="active"><a href="user_states.php">My States</a></li>
          <li><a href="user_friends.php">My Friends</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
        </ul>
      </div>
    </nav>

    <?php
      echo "<h1> States made by ".$username."</h1>";

      //fetch states created by user

      // Prepare a select statement
      $sql = "SELECT * FROM state WHERE uid = ? ORDER BY sid";

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
                        <th>SID</th>
                        <th>State Name</th>
                        <th>isActive</th>
                      </tr>
                    </thead>";

            //add first row
            echo "<tbody>
                    <tr>
                      <td>".$row["sid"]."</td>
                      <td>".$row["sname"]."</td>
                      <td>".$row["isActive"];

            if($row["isActive"] == "False") {
              echo "<form action='".htmlspecialchars($_SERVER["PHP_SELF"])."' method='post'>
                      <input type = 'hidden'  name = 'new_active_sid' value = '".$row["sid"]."'>
                      <div class='form-group'>
                          <input type='submit' class='btn btn-primary' value='Make Active'>
                      </div>
                    </form>";
            }

            echo "</td>
                    </tr>";



            //iterate through rows
            while ($row = $result->fetch_assoc()) {
              //add rest of rows
              echo "<tr>
                      <td>".$row["sid"]."</td>
                      <td>".$row["sname"]."</td>
                      <td>".$row["isActive"];

              if($row["isActive"] == "False") {
                echo "<form action='".htmlspecialchars($_SERVER["PHP_SELF"])."' method='post'>
                        <input type = 'hidden'  name = 'new_active_sid' value = '".$row["sid"]."'>
                        <div class='form-group'>
                            <input type='submit' class='btn btn-primary' value='Make Active'>
                        </div>
                      </form>";
              }

              echo "</td>
                    </tr>";
            }

            echo "</tbody>";

          } else {
              echo "You have not made any stats";
          }

          //echo "</br></br><a href='new_filter.php' class='btn btn-primary'>Create a New State</a>";



        } else{
            echo "Error: Statement did not execute".mysqli_error($conn);
        }
      }
    ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group <?php echo (!empty($sname_err)) ? 'has-error' : ''; ?>">
            <label>New State</label>
            <input type="text" name="sname" value="">
            <span class="help-block"><?php echo $sname_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Add State">
        </div>


    </form>

    </body>
</html>

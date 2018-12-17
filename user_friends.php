<?php
//check if user is logged in
require_once "check_login.php";

// Include config file
require_once "config.php";

$reciever_uid = $sender_uid = "";
$reciever_uid_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST["sender_uid"])) {

  //validate receiver_uid
  if(!isset($_POST["reciever_uid"])) {
    $reciever_uid_err = "No user selected";
  } else {
    $reciever_uid = $_POST["reciever_uid"];
  }


  //check input error before inserting into database
  if(empty($reciever_uid_err)) {

    //prepare insert statement
    $sql = "INSERT INTO friend_request (sender_uid, reciever_uid) VALUES (?, ?)";

    if($stmt = $conn->prepare($sql)){

      // Bind variables to the prepared statement as parameters
      $stmt->bind_param("ii", $param_sender_uid, $param_reciever_uid);

      // Set parameters
      $param_sender_uid = $uid;
      $param_reciever_uid = $reciever_uid;

      if($stmt->execute()) {
        // Redirect back to user friends page
        header("location: user_friends.php");
      } else {
          echo "Error: Statment did not exectute".mysqli_error($conn);
      }


    } else {
        echo "Error: Statement could not be prepared".mysqli_error($conn);
    }
  }

} else if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["sender_uid"])) {

    $sender_uid = $_POST["sender_uid"];

    //prepare friendship insert statements
    $sql_user_friendship = "INSERT INTO friendship (uid, friends_uid) VALUES (?, ?)";
    $sql_sender_friendship = "INSERT INTO friendship (uid, friends_uid) VALUES (?, ?)";
    $sql_remove_request = "DELETE FROM friend_request WHERE sender_uid = ? AND reciever_uid = ?";

    if($stmt = $conn->prepare($sql_user_friendship)) {
      // Bind variables to the prepared statement as parameters
      $stmt->bind_param("ii", $param_uid, $param_friend_uid);

      // Set parameters
      $param_uid = $uid;
      $param_friend_uid = $sender_uid;

      if($stmt->execute()) {

        if($stmt = $conn->prepare($sql_sender_friendship)) {
          // Bind variables to the prepared statement as parameters
          $stmt->bind_param("ii", $param_uid, $param_friend_uid);

          // Set parameters
          $param_uid = $sender_uid;
          $param_friend_uid = $uid;

          if($stmt->execute()) {
            if($stmt = $conn->prepare($sql_remove_request)) {
              // Bind variables to the prepared statement as parameters
              $stmt->bind_param("ii", $param_sender_uid, $param_reciever_uid);

              // Set parameters
              $param_sender_uid = $sender_uid;
              $param_reciever_uid = $uid;

              if($stmt->execute()) {
                // Redirect back to user friends page
                header("location: user_friends.php");
              } else {
                  echo "Error: Statment did not exectute".mysqli_error($conn);
              }

            } else {
                echo "Error: statment could not be prepared: ".mysqli_error($conn);
            }
          } else {
              echo "Error: Statment did not exectute".mysqli_error($conn);
          }

        } else {
            echo "Error: statment could not be prepared: ".mysqli_error($conn);
        }
      } else {
          echo "Error: Statment did not exectute".mysqli_error($conn);
      }

    } else {
        echo "Error: statment could not be prepared: ".mysqli_error($conn);
    }





}

?>

<!DOCTYPE html>
<html>

  <head>
    <title>My Friends</title>
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
          <li><a href="user_states.php">My States</a></li>
          <li class="active"><a href="user_friends.php">My Friends</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li><a href="all_notes.php">All Notes</a></li>
          <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
        </ul>
      </div>
    </nav>


    <?php
      //echo "<h1> Friends of ".$username."</h1>";

      //fetch friends of user

      // Prepare a select statement
      $sql = "SELECT friends_uid, fname, lname FROM friendship JOIN users ON (friends_uid = users.uid) WHERE friendship.uid = ?";

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
            echo "<div><h1> Friends List</h1>
                  <table class='table table-hover'>
                    <thead class='thead-dark'>
                      <tr>
                        <th>Friend UID &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</th>
                        <th>Name</th>
                      </tr>
                    </thead>";

            //add first row
            echo "<tbody>
                    <tr>
                      <td>".$row["friends_uid"]."</td>
                      <td>".$row["fname"]." ".$row["lname"]."</td>
                    </tr>";



            //iterate through rows
            while ($row = $result->fetch_assoc()) {
              //add rest of rows
              echo "<tr>
                      <td>".$row["friends_uid"]."</td>
                      <td>".$row["fname"]." ".$row["lname"]."</td>
                    </tr>";
            }

            echo "</tbody></div>";

          } else {
              echo "You have no friends lol";
          }


          //echo "</br></br><a href='new_friend.php' class='btn btn-primary'>Send Friend Request</a>";


        } else{
            echo "Error: Statement did not execute".mysqli_error($conn);
        }
      } else {
          echo "Error: Could not prepare SQL Statement".mysqli_error($conn);
      }

      //fetch friend requests SENT BY user
      // "<h1> Friend Requests Sent</h1>";

      // Prepare a select statement
      $sql = "SELECT reciever_uid, fname, lname FROM friend_request JOIN users ON (reciever_uid = uid) WHERE sender_uid = ?";

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

            //<h1> Friend Requests Sent</h1>
            //start table
            echo "<div>
                  <table class='table table-hover'>
                    <thead class='thead-dark'>
                      <tr>
                        <th>Receiver UID</th>
                        <th>Name</th>
                      </tr>
                    </thead>";

            //add first row
            echo "<tbody>
                    <tr>
                      <td>".$row["reciever_uid"]."</td>
                      <td>".$row["fname"]." ".$row["lname"]."</td>
                    </tr>";



            //iterate through rows
            while ($row = $result->fetch_assoc()) {
              //add rest of rows
              echo "<tr>
                      <td>".$row["reciever_uid"]."</td>
                      <td>".$row["fname"]." ".$row["lname"]."</td>
                    </tr>";
            }

            echo "</tbody></div>";

          } else {
              echo "You have no friend requests lol";
          }


          //echo "</br></br><a href='new_friend.php' class='btn btn-primary'>Add New Friend</a>";



        } else{
            echo "Error: Statement did not execute".mysqli_error($conn);
        }
      } else {
          echo "Error: Could not prepare SQL Statement".mysqli_error($conn);
      }

      //fetch friend requests SENT TO user

      // Prepare a select statement
      $sql = "SELECT sender_uid, fname, lname FROM friend_request JOIN users ON (sender_uid = uid) WHERE reciever_uid = ?";

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


            //<h1> Friend Requests Recieved</h1>
            //start table
            echo "<div>
                  <table class='table table-hover'>
                    <thead class='thead-dark'>
                      <tr>
                        <th>Sender UID</th>
                        <th>Name</th>
                      </tr>
                    </thead>";

            //add first row
            echo "<tbody>
                    <tr>
                      <td>".$row["sender_uid"]."</td>
                      <td>".$row["fname"]." ".$row["lname"]."
                      <form action='".htmlspecialchars($_SERVER["PHP_SELF"])."' method='post'>
                          <input type = 'hidden'  name = 'sender_uid' value = '".$row["sender_uid"]."'>
                          <div class='form-group'>
                              <input type='submit' class='btn btn-primary' value='Accept'>
                          </div>
                      </form>
                      </td>
                    </tr>";



            //iterate through rows
            while ($row = $result->fetch_assoc()) {
              //add rest of rows
              echo "<tr>
                      <td>".$row["sender_uid"]."</td>
                      <td>".$row["fname"]." ".$row["lname"]."
                      <form action='".htmlspecialchars($_SERVER["PHP_SELF"])."' method='post'>
                          <input type = 'hidden'  name = 'sender_uid' value = '".$row["sender_uid"]."'>
                          <div class='form-group'>
                              <input type='submit' class='btn btn-primary' value='Accept'>
                          </div>
                      </form>
                      </td>
                    </tr>";
            }

            echo "</tbody></div>";

          } else {
              echo "You have no friend requests lol";
          }

        } else{
            echo "Error: Statement did not execute".mysqli_error($conn);
        }
      } else {
          echo "Error: Could not prepare SQL Statement".mysqli_error($conn);
      }



    ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group <?php echo (!empty($reciever_uid_err)) ? 'has-error' : ''; ?>">
            <label>Select User to Send a Friend Request</label>
            <select name = "reciever_uid">
              <?php
                // Include config file
                require_once "config.php";

                //prepare select available users to send request to
                $sql_states = "SELECT uid, fname, lname
                                FROM users
                                WHERE uid NOT IN
                                (SELECT reciever_uid FROM friend_request WHERE sender_uid = ?)
                                AND uid NOT IN
                                (SELECT sender_uid FROM friend_request WHERE reciever_uid = ?)
                                AND uid NOT IN
                                (SELECT friends_uid FROM friendship WHERE uid = ?)
                                AND NOT(uid = ?)";

                if($stmt = $conn->prepare($sql_states)) {
                  // Bind variables to the prepared statement as parameters
                  $stmt->bind_param("iiii", $param_uid1, $param_uid2, $param_uid3, $param_uid4);

                  // Set parameters
                  $param_uid1 = $param_uid2 = $param_uid3 = $param_uid4 = $uid;

                  //execute statment
                  if($stmt->execute()) {

                    $result = $stmt->get_result();

                    if($result->num_rows > 0) {

                      while ($row = $result->fetch_assoc()) {
                        echo '<option value="'.$row["uid"].'">'.$row["fname"].' '.$row["lname"].'</option>';
                      }

                    } else {
                      echo "No users available";
                    }

                  } else {
                      echo "Error: Statement could not be executed".mysqli_error($conn);
                  }

                } else {
                    echo "Error: Statement could not be prepared".mysqli_error($conn);
                }

               ?>
            </select>
            <span class="help-block"><?php echo $reciever_uid_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Send Friend Request">
        </div>


    </form>

    </body>
</html>

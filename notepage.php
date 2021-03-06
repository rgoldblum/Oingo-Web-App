<?php
//check if user is logged in
require_once "check_login.php";

// Include config file
require_once "config.php";

$ctext = "";
$ctext_err = "";
$nid;

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
  $nid = $_POST["nid"];

  //validate comment
  if(empty(trim($_POST["ctext"]))) {
    $ctext_err = "No comment entered";
  } else {
      $ctext = trim($_POST["ctext"]);
  }

  if(empty($ctext_err)) {

    $sql = "INSERT INTO comments (nid, uid, ctext) VALUES (?, ?, ?)";

    if($stmt = $conn->prepare($sql)) {
      $stmt->bind_param("iis", $param_nid, $param_uid, $param_ctext);

      $param_nid = $nid;
      $param_uid = $uid;
      $param_ctext = $ctext;

      if($stmt->execute()) {
        header("location: notepage?nid=".$nid.".php");
      }

    } else {
        echo "Error: Statement was not prepared: ".mysqli_error($conn);
    }

  }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Note Page</title>
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
          <li><a href="user_friends.php">My Friends</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
        </ul>
      </div>
    </nav>

    <!-- <h1>Oingo Note Page</h1> -->
    <?php

      if(!isset($_GET["nid"]) && !isset($_POST["nid"])) {
        header("location: mainpage.php");
      }

      if(isset($_GET["nid"])) {
        $nid = $_GET["nid"];
      }

      $sql = "SELECT * FROM note JOIN users ON note.uid  = users.uid WHERE nid = ?";

      //prepate statement
      if($stmt = $conn->prepare($sql)) {
        //bind $params
        $stmt->bind_param("i", $param_nid);

        //set params
        $param_nid = $nid;

        //execute
        if($stmt->execute()) {
          //display note info
          $result = $stmt->get_result();

          if($result->num_rows > 0) {

            $note = $result->fetch_assoc();

            echo "<div>
                  <h2> ".$note["ntext"]."</h2>

                  </div>";

            //fetch tags
            $sql_tags = "SELECT * FROM tag_in_note NATURAL JOIN tag WHERE nid = ?";

            //fetch comments
            $sql_comments = "SELECT * FROM users NATURAL JOIN comments WHERE nid = ? ORDER BY ctimestamp";


            //prepare statement
            if($stmt = $conn->prepare($sql_tags)) {

              //bind params
              $stmt->bind_param("i", $param_nid);

              //set params
              $param_nid = $nid;

              //execute statement
              if($stmt->execute()) {
                $tags = $stmt->get_result();

                if($tags->num_rows > 0) {

                  while($row = $tags->fetch_assoc()) {
                    //output comment info
                    echo "<p><strong>".$row["ttext"]."</strong></p>";

                  }

                }
              } else {
                  echo "Error: Statement not executed: ".mysqli_error($conn);
              }
            } else {
                echo "Error: Statment not prepared: ".mysqli_error($conn);
            }

            echo "<p><b>Posted By: </b>".$note["username"]." (".$note["ntimestamp"].")</p>";

            echo "<h2>Comments:</h2>";

            //prepare statement
            if($stmt = $conn->prepare($sql_comments)) {

              //bind params
              $stmt->bind_param("i", $param_nid);

              //set params
              $param_nid = $nid;

              //execute statement
              if($stmt->execute()) {
                $comments = $stmt->get_result();

                if($comments->num_rows > 0) {



                  while($row = $comments->fetch_assoc()) {
                    //output comment info
                    echo "<div><p><b>".$row["username"]." (".$row["ctimestamp"]."): </b>".$row["ctext"]."</p></div>";

                  }

                }
              } else {
                  echo "Error: Statement not executed: ".mysqli_error($conn);
              }
            } else {
                echo "Error: Statment not prepared: ".mysqli_error($conn);
            }


          } else {
              echo "Error: Note does not exist";
          }

        } else {
            echo "Error: Statment not execute: ".mysqli_error($conn);
        }
      } else {
          echo "Error: Statement not prepared: ".mysqli_error($conn);
      }

    ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group <?php echo (!empty($ctext_err)) ? 'has-error' : ''; ?>">
            <input type="hidden" name = "nid" value = "<?php echo $nid ?>">
            <input type="text" name="ctext" class="form-control" placeholder="Write a Comment">
            <span class="help-block"><?php echo $ctext_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Post Comment">
        </div>
    </form>

  </body>
</html>

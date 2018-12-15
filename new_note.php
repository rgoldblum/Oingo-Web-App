<?php
//check if user is logged in
require_once "check_login.php";

// Include config file
require_once "config.php";

//Inlude fetch user data
require_once "fetch_user_data.php";

  // echo $userlat;
  // echo $userlng;


//Set to eastern time zone
date_default_timezone_set("America/New_York");

//get current time
$currTime = date("Y-m-d G:i:s", time());
//echo $currTime;






// Define variables and initialize with empty values
$ntext = $notePrivacy = $activeDays =  $startDate = $endDate = $startTime = $endTime = $radius = $sched_id = "";
$tag = array();
$ntext_err = $notePrivacy_err = $activeDays_err =  $startDate_err = $endDate_err = $startTime_err = $endTime_err = $radius_err = $tag_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

  // echo $_POST["startTime"];
  // echo $_POST["endTime"];

    // Validate ntext
    if(empty(trim($_POST["ntext"]))){
        $ntext_err = "Please enter body text.";
    } else{
        $ntext = trim($_POST["ntext"]);
    }

    // Validate notePrivacy
    if(empty(trim($_POST["notePrivacy"]))){
        $notePrivacy_err = "Please pick a privacy setting for your note.";
    } else{
        $notePrivacy = trim($_POST["notePrivacy"]);
    }

    //concatenate active days
    if(isset($_POST["Mon"])) {
      $activeDays .= $_POST["Mon"].", ";
    }
    if(isset($_POST["Tue"])) {
      $activeDays .= $_POST["Tue"].", ";
    }
    if(isset($_POST["Wed"])) {
      $activeDays .= $_POST["Wed"].", ";
    }
    if(isset($_POST["Thur"])) {
      $activeDays .= $_POST["Thu"].", ";
    }
    if(isset($_POST["Fri"])) {
      $activeDays .= $_POST["Fri"].", ";
    }
    if(isset($_POST["Sat"])) {
      $activeDays .= $_POST["Sat"].", ";
    }
    if(isset($_POST["Sun"])) {
      $activeDays .= $_POST["Sun"];
    }

    // Validate activeDays
    if(empty(trim($activeDays))){
        $activeDays_err = "Please select day(s) you want this note to be active.";
    }

    // Validate startDate
    if(empty(trim($_POST["startDate"]))){
        $startDate_err = "Please enter a start date.";
    } else{
        $startDate = trim($_POST["startDate"]);
    }

    // Validate endDate
    if(empty(trim($_POST["endDate"]))){
        $endDate_err = "Please enter a end date.";
    } else{
        $endDate = trim($_POST["endDate"]);
    }

    // Validate startTime
    if(empty(trim($_POST["startTime"]))){
        $startTime_err = "Please enter a time of day to start showing the note.";
    } else{
        $startTime = trim($_POST["startTime"]);
    }

    // Validate endTime
    if(empty(trim($_POST["endTime"]))){
        $endTime_err = "Please enter a time of day to stop showing the note.";
    } else{
        $endTime = trim($_POST["endTime"]);
    }

    // Validate radius
    if(empty(trim($_POST["radius"]))){
        $radius_err = "Please enter a radius of how far from the notes location you want it to be displayed.";
    } else{
        $radius = trim($_POST["radius"]);
    }

    //Validate tags
    if(empty(trim($_POST["tags"]))){
        $tag_err = "Please enter at least 1 tag";
    } else{
        $tags = explode(", ", $_POST["tags"]);
    }



    // Check input errors before inserting in database
    if(empty($ntext_err) && empty($notePrivacy_err) && empty($activeDays_err) && empty($startDate_err) && empty($endDate_err) && empty($startTime_err) && empty($endTime_err) && empty($radius_err) && empty($tag_err)){

        // Prepare an schedule insert statement
        $sql_sched = "INSERT INTO schedules (activeDays, startDate, endDate, startTime, endTime) VALUES (?, ?, ?, ?, ?)";

        //statement to get last inserted sched_id
        $sql_get_sched = "SELECT sched_id FROM schedules ORDER BY sched_id DESC LIMIT 1";

        // Prepare an note insert statement
        $sql_note = "INSERT INTO note (uid, ntext, notePrivacy, ntimestamp, sched_id, radius, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        //prepare check tag statement
        $sql_check_tag = "SELECT * FROM tag WHERE ttext = ?";

        //Prepare tag insert statement
        $sql_insert_tag = "INSERT INTO tag (ttext) VALUES (?)";

        //Prepare tag_in_note insert statement
        $sql_tag_in_note = "INSERT INTO tag_in_note (nid, tid) VALUES (?, ?)";


        //Prepare select last auto_inc value for sched_id statement
        // $sql_get_sched_auto = "SELECT ";

        if($stmt = $conn->prepare($sql_sched)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sssss", $param_activeDays, $param_startDate, $param_endDate, $param_startTime, $param_endTime);

            // Set parameters
            $param_activeDays = $activeDays;
            $param_startDate = $startDate;
            $param_endDate = $endDate;
            $param_startTime = $startTime;
            $param_endTime = $endTime;

            // Attempt to execute the schedule insert statement
            if($stmt->execute()){

              $sched_id = $conn->insert_id;

              if($stmt = $conn->prepare($sql_note)){

                // Bind variables to the prepared statement as parameters
                $stmt->bind_param("isssiddd", $param_uid, $param_ntext, $param_notePrivacy, $param_ntimestamp, $param_sched_id, $param_radius, $param_latitude, $param_longitude);

                // Set parameters
                $param_uid = $uid;
                $param_ntext = $ntext;
                $param_notePrivacy = $notePrivacy;
                $param_ntimestamp = $currTime;
                $param_sched_id = $sched_id;
                $param_radius = $radius;
                $param_latitude = $userlat;
                $param_longitude = $userlng;

                // Attempt to execute the note insert statement
                if($stmt->execute()){

                  $nid = $conn->insert_id;

                  //iterate through tags array
                  foreach ($tags as $ttext) {

                    //check if tag already exists
                    if($stmt = $conn->prepare($sql_check_tag)) {
                      $stmt->bind_param("s", $param_ttext);

                      $param_ttext = '#'.$ttext;

                      if($stmt->execute()) {
                        $result = $stmt->get_result();

                        //if tag exists, only insert into tag_in_note, not tag
                        if($result->num_rows > 0) {
                          $tag = $result->fetch_assoc();

                          if($stmt = $conn->prepare($sql_tag_in_note)) {
                            $stmt->bind_param("ii", $param_nid, $param_tid);

                            $param_nid = $nid;
                            $param_tid = $tag["tid"];

                            if($stmt->execute()) {

                            } else {
                                echo "Error: Statement not executed: ".mysqli_error($conn);
                            }

                          } else {
                              echo "Error: Statement not prepared: ".mysqli_error($conn);
                          }

                        } else {
                          //insert into tag then into tag_in_note
                          if($stmt = $conn->prepare($sql_insert_tag)) {
                            $stmt->bind_param("s", $param_ttext);
                            $param_ttext = '#'.$ttext;

                            if($stmt->execute()) {
                              //insert tag_in_note
                              $tid = $conn->insert_id;

                              if($stmt = $conn->prepare($sql_tag_in_note)) {
                                $stmt->bind_param("ii", $param_nid, $param_tid);

                                $param_nid = $nid;
                                $param_tid = $tid;

                                if($stmt->execute()) {

                                } else {
                                    echo "Error: Statement not executed: ".mysqli_error($conn);
                                }

                              } else {
                                  echo "Error: Statement not prepared: ".mysqli_error($conn);
                              }

                            } else {
                                echo "Error: Statement not executed: ".mysqli_error($conn);
                            }
                          }
                        }

                      } else {
                          echo "Error: Statement not executed: ".mysqli_error($conn);
                      }
                    } else {
                      echo "Error: Statement not prepared: ".mysqli_error($conn);
                    }

                  }

                  // Redirect to user notes page
                  header("location: user_notes.php");
                } else {
                  echo "Error: Note could not be posted".mysqli_error($conn);
                }

              }

              // if($stmt = $conn->prepare($sql_get_sched)){
              //
              //   // Attempt to execute the get last schedule statement
              //   if($stmt->execute()){
              //
              //     $result = $stmt->get_result();
              //
              //     //iterate through rows
              //     while ($row = $result->fetch_assoc()) {
              //       // echo '<p>Row:'.$row.'</p>';
              //       //store row in notes array
              //       $sched_id = $row["sched_id"];
              //       echo $sched_id;
              //     }
              //
              //     if($stmt = $conn->prepare($sql_note)){
              //
              //       // Bind variables to the prepared statement as parameters
              //       $stmt->bind_param("isssiddd", $param_uid, $param_ntext, $param_notePrivacy, $param_ntimestamp, $param_sched_id, $param_radius, $param_latitude, $param_longitude);
              //
              //       // Set parameters
              //       $param_uid = $uid;
              //       $param_ntext = $ntext;
              //       $param_notePrivacy = $notePrivacy;
              //       $param_ntimestamp = $currTime;
              //       $param_sched_id = $sched_id;
              //       $param_radius = $radius;
              //       $param_latitude = $userlat;
              //       $param_longitude = $userlng;
              //
              //       // Attempt to execute the note insert statement
              //       if($stmt->execute()){
              //
              //         //iterate through tags array
              //         foreach ($tags as $ttext) {
              //
              //           //check if tag already exists
              //           if($stmt = $conn->prepare($sql_check_tag)) {
              //             $stmt->bind_param("s", $param_ttext)
              //
              //             $param_ttext = '#'.$ttext;
              //
              //             if($stmt->execute()) {
              //               $result = $stmt->get_result();
              //
              //               //if tag exists, only insert into tag_in_note, not tag
              //               if($result->num_rows > 0) {
              //                 $tag = $result->fetch_assoc();
              //
              //
              //
              //                 if($stmt = $conn->prepare($sql_tag_in_note)) {
              //                   $stmt->bind_param("ii", $param_nid, $param_tid);
              //
              //
              //                 } else {
              //                     echo "Error: Statement not prepared: ".mysqli_error($conn);
              //                 }
              //
              //               } else {
              //
              //               }
              //
              //
              //             } else {
              //                 echo "Error: Statement not executed: ".mysqli_error($conn);
              //             }
              //           } else {
              //             echo "Error: Statement not prepared: ".mysqli_error($conn);
              //           }
              //
              //         }
              //
              //         // Redirect to user notes page
              //         header("location: user_notes.php");
              //       } else {
              //         echo "Error: Note could not be post".mysqli_error($conn);
              //       }
              //
              //     }
              //
              //   }
              // }

            } else{
                echo "Something went wrong. Please try again later.";
                echo mysqli_error($conn);

            }
        }

        // Close statement
        $stmt->close();
    }

    // Close connection
    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Note</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>New Note Page</h2>
        <p>Please fill this form to create an new note</p>
        <form id="note-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($ntext_err)) ? 'has-error' : ''; ?>">
                <label>Note Text</label>
                <input type="text" name="ntext" class="form-control" value="<?php echo $ntext; ?>">
                <span class="help-block"><?php echo $ntext_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($notePrivacy_err)) ? 'has-error' : ''; ?>">
                <label>Privacy</label><br>
                <input type="radio" name="notePrivacy" class="form-control" value="self"> Visible to Me Only
                <input type="radio" name="notePrivacy" class="form-control" value="friends"> Visible to my friends and me only
                <input type="radio" name="notePrivacy" class="form-control" value="public">Visible to anyone (Public)
                <span class="help-block"><?php echo $notePrivacy_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($activeDays_err)) ? 'has-error' : ''; ?>">
                <label>Active Days</label><br>
                <input type="checkbox" name="Mon" value="Mon">Monday<br>
                <input type="checkbox" name="Tue" value="Tue">Tuesday<br>
                <input type="checkbox" name="Wed" value="Wed">Wednesday<br>
                <input type="checkbox" name="Thu" value="Thu">Thursday<br>
                <input type="checkbox" name="Fri" value="Fri">Friday<br>
                <input type="checkbox" name="Sat" value="Sat">Saturday<br>
                <input type="checkbox" name="Sun" value="Sun">Sunday<br>
                <span class="help-block"><?php echo $activeDays_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($startDate_err)) ? 'has-error' : ''; ?>">
                <label>Start Date</label>
                <input type="date" name="startDate" class="form-control" >
                <span class="help-block"><?php echo $startDate_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($endDate_err)) ? 'has-error' : ''; ?>">
                <label>End Date</label>
                <input type="date" name="endDate" class="form-control" >
                <span class="help-block"><?php echo $endDate_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($startDate_err)) ? 'has-error' : ''; ?>">
                <label>Start Time</label>
                <input type="time" name="startTime" class="form-control" >
                <span class="help-block"><?php echo $startTime_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($endDate_err)) ? 'has-error' : ''; ?>">
                <label>End Time</label>
                <input type="time" name="endTime" class="form-control" >
                <span class="help-block"><?php echo $endTime_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($radius_err)) ? 'has-error' : ''; ?>">
                <label>Radius of Interest (Kilometers)</label>
                <input type="number" min="1" name="radius" class="form-control" value="<?php echo $radius; ?>">
                <span class="help-block"><?php echo $radius_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($tag_err)) ? 'has-error' : ''; ?>">
                <label>Tags (Seperate Multiple Tags by Commas, i.e, "Korean_Food, Shopping, Downtown")</label>
                <input type="text" name="tags" class="form-control" value="">
                <span class="help-block"><?php echo $tag_err; ?></span>
                <!-- <button class="btn btn-primary" onclick="addTagBox()"> Add Another Tag </button> -->
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Post Note">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
        </form>
    </div>
</body>

<script>

//function to add new tag boxes to form
function addTagBox() {
  var form = document.getElementById("note-form");
  var formLength = form.childNodes.length;

  var newTag = document.createElement(div);

  form.insertBefore(newTag, form.childNodes[formLength-1]);

}

</script>
</html>

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
$fname = $sid = $filter_privacy = $activeDays =  $startDate = $endDate = $startTime = $endTime = $radius = $sched_id = $tid = "";
$fname_err = $sid_err = $filter_privacy_err = $activeDays_err =  $startDate_err = $endDate_err = $startTime_err = $endTime_err = $radius_err = $tid_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

  // echo $_POST["startTime"];
  // echo $_POST["endTime"];

    // Validate fname
    if(empty(trim($_POST["fname"]))){
        $fname_err = "Please enter a filter name.";
    } else{
        $fname = trim($_POST["fname"]);
    }

    //vaildate sid
    if(empty(trim($_POST["sid"]))){
        $sid_err = "Please pick a state.";
    } else{
        $sid = trim($_POST["sid"]);
    }

    // Validate filter_privacy
    if(empty(trim($_POST["filter_privacy"]))){
        $filter_privacy_err = "Please pick a privacy setting for your filter.";
    } else{
        $filter_privacy = trim($_POST["filter_privacy"]);
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
      $activeDays .= $_POST["Thur"].", ";
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
    } else{

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

    //validate tid
    if(empty(trim($_POST["tid"]))){
        $tid_err = "Please pick a tag.";
    } else{
        $tid = trim($_POST["tid"]);
    }

    // Check input errors before inserting in database
    if(empty($fname_err) && empty($sid_err) && empty($filter_privacy_err) && empty($activeDays_err) && empty($startDate_err) && empty($endDate_err) && empty($startTime_err) && empty($endTime_err) && empty($radius_err) && empty($tid_err)){

        // Prepare an schedule insert statement
        $sql_sched = "INSERT INTO schedules (activeDays, startDate, endDate, startTime, endTime) VALUES (?, ?, ?, ?, ?)";

        //statement to get last inserted sched_id
        $sql_get_sched = "SELECT sched_id FROM schedules ORDER BY sched_id DESC LIMIT 1";

        // Prepare an note insert statement
        $sql_filter = "INSERT INTO filters (uid, tid, sid, sched_id, fname, filter_privacy, radius, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

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

              if($stmt = $conn->prepare($sql_get_sched)){

                // Attempt to execute the get last schedule statement
                if($stmt->execute()){

                  $result = $stmt->get_result();

                  //iterate through rows
                  while ($row = $result->fetch_assoc()) {
                    // echo '<p>Row:'.$row.'</p>';
                    //store row in notes array
                    $sched_id = $row["sched_id"];
                    echo $sched_id;
                  }

                  if($stmt = $conn->prepare($sql_filter)){

                    // Bind variables to the prepared statement as parameters
                    $stmt->bind_param("iiiissddd", $param_uid, $param_tid, $param_sid, $param_sched_id, $param_fname, $param_filter_privacy, $param_radius, $param_latitude, $param_longitude);

                    // Set parameters
                    $param_uid = $uid;
                    $param_tid = $tid;
                    $param_sid = $sid;
                    $param_sched_id = $sched_id;
                    $param_fname = $fname;
                    $param_filter_privacy = $filter_privacy;
                    $param_radius = $radius;
                    $param_latitude = $userlat;
                    $param_longitude = $userlng;

                    // Attempt to execute the note insert statement
                    if($stmt->execute()){
                      // Redirect to user notes page
                      header("location: user_filters.php");
                    } else {
                      echo "Error: Filter could not be created".mysqli_error($conn);
                    }

                  }

                }
              }

            } else{
                echo "Something went wrong. Please try again later.";
                echo mysqli_error($conn);

            }
        }

        // Close statement
        $stmt->close();
    }

    // Close connection
    // $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Filter</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>New Filter Page</h2>
        <p>Please fill this form to create an new filter</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($fname_err)) ? 'has-error' : ''; ?>">
                <label>Filter Name</label>
                <input type="text" name="fname" class="form-control" value="<?php echo $fname; ?>">
                <span class="help-block"><?php echo $fname_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($sid_err)) ? 'has-error' : ''; ?>">
                <label>State</label>
                <select name = "sid">
                  <?php
                    // Include config file
                    require_once "config.php";

                    //prepare select user states
                    $sql_states = "SELECT sid, sname FROM state WHERE uid = ?";

                    if($stmt = $conn->prepare($sql_states)) {
                      // Bind variables to the prepared statement as parameters
                      $stmt->bind_param("i", $param_uid);

                      // Set parameters
                      $param_uid = $uid;

                      //execute statment
                      if($stmt->execute()) {

                        $result = $stmt->get_result();

                        if($result->num_rows > 0) {

                          while ($row = $result->fetch_assoc()) {
                            echo '<option value="'.$row["sid"].'">'.$row["sname"].'</option>';
                          }

                        } else {
                          echo "No states available";
                        }

                      } else {
                          echo "Error: Statement could not be executed".mysqli_error($conn);
                      }

                    } else {
                        echo "Error: Statement could not be prepared".mysqli_error($conn);
                    }

                    // $conn->close();
                   ?>
                </select>
                <span class="help-block"><?php echo $sid_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($tid_err)) ? 'has-error' : ''; ?>">
                <label>Tag</label>
                <select name = "tid">
                  <?php
                    // Include config file
                    require_once "config.php";

                    //prepare select all tags
                    $sql_tags = "SELECT * FROM tag";

                    if($stmt = $conn->prepare($sql_tags)) {

                      //execute statment
                      if($stmt->execute()) {

                        $result = $stmt->get_result();

                        if($result->num_rows > 0) {

                          while ($row = $result->fetch_assoc()) {
                            echo '<option value="'.$row["tid"].'">'.$row["ttext"].'</option>';
                          }

                        } else {
                          echo "No tags available";
                        }

                      } else {
                          echo "Error: Statement could not be executed".mysqli_error($conn);
                      }

                    } else {
                        echo "Error: Statement could not be prepared".mysqli_error($conn);
                    }

                    // $conn->close();
                   ?>
                </select>
                <span class="help-block"><?php echo $tid_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($filter_privacy_err)) ? 'has-error' : ''; ?>">
                <label>Privacy</label><br>
                <input type="radio" name="filter_privacy" class="form-control" value="self"> Visible to Me Only
                <input type="radio" name="filter_privacy" class="form-control" value="friends"> Visible to my friends and me only
                <input type="radio" name="filter_privacy" class="form-control" value="public">Visible to anyone (Public)
                <span class="help-block"><?php echo $filter_privacy_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($activeDays_err)) ? 'has-error' : ''; ?>">
                <label>Active Days</label><br>
                <input type="checkbox" name="Mon" value="Mon">Monday<br>
                <input type="checkbox" name="Tue" value="Tue">Tuesday<br>
                <input type="checkbox" name="Wed" value="Wed">Wednesday<br>
                <input type="checkbox" name="Thur" value="Thur">Thursday<br>
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
                <label>Radius of Interest</label>
                <input type="number" min="1" name="radius" class="form-control" value="<?php echo $radius; ?>">
                <span class="help-block"><?php echo $radius_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Post Note">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
        </form>
    </div>
</body>
</html>

<?php $conn->close(); ?>

<?php
//check if user is logged in
require_once "check_login.php";

// Include config file
require_once "config.php";

//Inlude fetch user data
require_once "fetch_user_data.php";

$currTime = "";

$userlat_err = $userlng_err = $currTime_err = "";

//array to store notes
$notes = array();

//Set to eastern time zone
date_default_timezone_set("America/New_York");

//get current time
$currDate = date("Y-m-d", time());

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
  // Check if latitude is empty
  if(empty(trim($_POST["userlat"]))){
      $userlat_err = "Please enter latitude.";
  } else{
      $userlat = trim($_POST["userlat"]);
  }

  // Check if longitude is empty
  if(empty(trim($_POST["userlng"]))){
      $userlng_err = "Please enter longitude.";
  } else{
      $userlng = trim($_POST["userlng"]);
  }

  // Validate credentials
  if(empty($userlat_err) && empty($userlng_err)){
      // Prepare a select statement
      $sql = "UPDATE users SET latitude = ?, longitude = ? WHERE uid = ?";

      if($stmt = $conn->prepare($sql)){
          // Bind variables to the prepared statement as parameters
          $stmt->bind_param("ddi", $param_userlat, $param_userlng, $param_uid);

          // Set parameters
          $param_userlat = $userlat;
          $param_userlng = $userlng;
          $param_uid = $uid;

          // Attempt to execute the prepared statement
          if($stmt->execute()){

            // Redirect to user notes page
            header("location: all_notes.php");

          } else {
              // Display an error message if bad coordinates input
              $userlng_err = "Incorrect coordinates input.";
          }


          } else{
              echo "Oops! Something went wrong. Please try again later.";
          }
      } else{
          echo "statement wasnt prepared";
          echo mysqli_error($conn);
      }

}

$sql = "SELECT uid, nid, ntext, notePrivacy, latitude, longitude FROM note";

if($stmt = $conn->prepare($sql)) {

  if($stmt->execute()) {
    $result = $stmt->get_result();

    //if there are any active filters
    if($result->num_rows > 0) {

      //iterate through rows
      while ($row = $result->fetch_assoc()) {
        //store row in notes array
        $notes[] = $row;
      }

    }

  } else {
      echo "Error: Statement not executed: ".mysqli_error($conn);
  }

} else {
    echo "Error: Statement not prepared: ".mysqli_error($conn);
}

?>


<!DOCTYPE html>
<html>

<head>
    <title>Oingo Map</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">

      .map-container {
        width: 600px;
        height: 450px;
      }

      #map {
        width: 100%;
        height: 100%;
        border: 1px solid blue;
        /* margin-left: 25%;
        margin-top: 25%; */
      }

      #legend {
        font-family: Arial, sans-serif;
        background: #fff;
        padding: 10px;
        margin: 10px;
        border: 3px solid #000;
      }
      #legend h3 {
        margin-top: 0;
      }
      #legend img {
        vertical-align: middle;
      }


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
          <li class="active"><a href="all_notes.php">All Notes</a></li>
          <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
        </ul>
      </div>
    </nav>

    <div class="row">
      <div class="col-md-6">
        <div class="location-container">
          <center><h1> Current Location</h1></center>
          <?php //echo 'Latitude: '.$userlat.', Longitude: '.$userlng;?>
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
              <div class="form-group <?php echo (!empty($userlat_err)) ? 'has-error' : ''; ?>">
                  <label>Current Latitude</label>
                  <input type="number" step = "any" name="userlat" class="form-control" value="<?php echo $userlat; ?>">
                  <span class="help-block"><?php echo $userlat_err; ?></span>
              </div>
              <div class="form-group <?php echo (!empty($userlng_err)) ? 'has-error' : ''; ?>">
                  <label>Current Longitude</label>
                  <input type="number" step = "any" name="userlng" class="form-control" value="<?php echo $userlng; ?>">
                  <span class="help-block"><?php echo $userlng_err; ?></span>
              </div>
              <div class="form-group">
                  <input type="submit" class="btn btn-primary" value="Update Current Location and Time">
              </div>
          </form>
        </div>
      </div>
      <div class="col-md-6">
         <div class = "map-container">
           <center><h1>MAP</h1></center>
           <div id="map"></div>
           <div id="legend"><h3>Legend</h3></div>
         </div>
       </div>
    </div>

    <script>
      var map;
      var userLat;
      var userLng;
      var uid;

      //get user lat and long
      <?php echo 'var userLat = '.$userlat.';';?>

      <?php echo 'var userLng = '.$userlng.';';?>

      <?php echo 'var uid = '.$uid.';';?>

      //get notes array from php
      <?php echo 'var notes = '.json_encode($notes).';';?>

      console.log(notes);

      //function to add note markers to map
      function addNoteMarkers(notes) {
        // infowindow
        var infowindow = new google.maps.InfoWindow;
        var noteIcon = '';

        Array.prototype.forEach.call(notes, function(note){
          //create text element for info window
          var content = document.createElement('a');
          content.setAttribute('href', 'notepage.php?nid='+note.nid);
          var strong = document.createElement('strong');
          strong.textContent = note.ntext;
          content.appendChild(strong);

          //set icon based on privacy
          if (note.uid == uid) {
              noteIcon = 'images/sticky-note-yellow-small.png';
          } else if(note.notePrivacy == 'public') {
            noteIcon = 'images/sticky-note-red-small.png';
          } else if (note.notePrivacy == 'friends') {
              noteIcon = 'images/sticky-note-blue-small.png';
          }

          //maker for note
          var marker = new google.maps.Marker({
            position: new google.maps.LatLng(note.latitude, note.longitude),
            title: '' + note.nid,
            map: map,
            icon: noteIcon
          });

          marker.addListener('click', function() {
            infowindow.setContent(content);
            infowindow.open(map, marker);
          });

        });
      }


      function initMap() {

        map = new google.maps.Map(document.getElementById('map'), {
          zoom: 11,
          disableDefaultUI: true
        });

        //add marker for users location
        var userLocation = new google.maps.LatLng(userLat, userLng);
        var userMarker = new google.maps.Marker({
          position: userLocation,
          map: map,
          icon: 'images/blue-dot.png'
        });

        map.setCenter(userLocation);

        //call add notes function
        addNoteMarkers(notes);

        //add legend
        var legend = document.getElementById('legend');

        privateDiv = document.createElement('div');
        privateDiv.innerHTML = '<img src="images/sticky-note-yellow-small.png">Private Note';
        legend.appendChild(privateDiv);

        friendDiv = document.createElement('div');
        friendDiv.innerHTML = '<img src="images/sticky-note-blue-small.png">Friend Note';
        legend.appendChild(friendDiv);

        publicDiv = document.createElement('div');
        publicDiv.innerHTML = '<img src="images/sticky-note-red-small.png">Public Note';
        legend.appendChild(publicDiv);

        map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(legend);

      }
    </script>
    <script async defer
      src="https://maps.googleapis.com/maps/api/js?key=<?php echo $map_key; ?>&callback=initMap" type="text/javascript">
    </script>
  </body>

</html>

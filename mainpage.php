<?php
//check if user is logged in
require_once "check_login.php";

// Include config file
require_once "config.php";

//Inlude fetch user data
require_once "fetch_user_data.php";

$userlat_err = $userlng_err = "";

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

          echo $uid;

          // Set parameters
          $param_userlat = $userlat;
          $param_userlng = $userlng;
          $param_uid = $uid;

          // Attempt to execute the prepared statement
          if($stmt->execute()){
            echo "We did it!";
              // Store result
              //$stmt->store_result();

              // Check if username exists, if yes then verify password
              // if($stmt->num_rows == 1){
              //     // Bind result variables
              //     $stmt->bind_result($uid, $username, $hashed_password);
              //     if($stmt->fetch()){
              //         if(password_verify($password, $hashed_password)){
              //             // Password is correct, so start a new session
              //             session_start();
              //
              //             // Store data in session variables
              //             $_SESSION["loggedin"] = true;
              //             $_SESSION["uid"] = $uid;
              //             $_SESSION["username"] = $username;
              //
              //             // Redirect user to welcome page
              //             header("location: mainpage.php");
              //         } else{
              //             // Display an error message if password is not valid
              //             $password_err = "The password you entered was not valid.";
              //         }
              //     }
              } else{
                  // Display an error message if username doesn't exist
                  $userlng_err = "Incorrect coordinates input.";
              }

              // Close statement
              $stmt->close();

          } else{
              echo "Oops! Something went wrong. Please try again later.";
          }
      } else{
          echo "statement wasnt prepared";
          echo mysqli_error($conn);
      }


  }

  // Close connection
  $conn->close();

?>

<!DOCTYPE html>
<html>

<head>
    <title>Oingo Map</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
       /* .location-container{
         align: left;
       } */

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

      /* Optional: Makes the sample page fill the window. */
      /* html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      } */

    </style>
  </head>
  <body>
    <nav class="navbar navbar-inverse">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="mainpage.php">Oingo</a>
        </div>
        <ul class="nav navbar-nav">
          <li class="active"><a href="mainpage.php">Home</a></li>
          <li><a href="user_notes.php">My Notes</a></li>
          <li><a href="user_filters.php">My Filters</a></li>
          <li><a href="user_states.php">My States</a></li>
          <li><a href="user_friends.php">My Friends</a></li>
          <!-- <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Page 1 <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="#">Page 1-1</a></li>
              <li><a href="#">Page 1-2</a></li>
              <li><a href="#">Page 1-3</a></li>
            </ul>
          </li> -->
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
        </ul>
      </div>
    </nav>

    <div class="row">
      <div class="col-md-6">
        <div class="location-container">
          <center><h1> Current Location</h1></center>
          <?php echo 'Latitude: '.$userlat.', Longitude: '.$userlng;?>
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
              <div class="form-group <?php echo (!empty($userlat_err)) ? 'has-error' : ''; ?>">
                  <label>Latitude</label>
                  <input type="number" step = "any" name="userlat" class="form-control" value="<?php echo $userlat; ?>">
                  <span class="help-block"><?php echo $userlat_err; ?></span>
              </div>
              <div class="form-group <?php echo (!empty($userlng_err)) ? 'has-error' : ''; ?>">
                  <label>Longitude</label>
                  <input type="number" step = "any" name="userlng" class="form-control" value="<?php echo $userlng; ?>">
                  <span class="help-block"><?php echo $userlng_err; ?></span>
              </div>
              <div class="form-group">
                  <input type="submit" class="btn btn-primary" value="Update Current Location">
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

    <!-- <center>

    </center> -->

  <!-- <div class="location-container">
    <h3> Current Location</h3>
  </div> -->
    <script>
      var map;
      var userLat;
      var userLng;

      //get user lat and long
      <?php echo 'var userLat = '.$userlat.';';?>

      <?php echo 'var userLng = '.$userlng.';';?>

      //get notes array from php
      <?php echo 'var notes = '.json_encode($notes).';';?>

      console.log(notes);

      // for(i = 0; i<notes.length; i++) {
      //   console.log(notes[i]);
      // }
      // var  = document.getElementById("demo");

      // function getLocation() {
      //   if (navigator.geolocation) {
      //       navigator.geolocation.getCurrentPosition();
      //       lat = position.coords.latitude;
      //       long = position.coords.longitude;
      //   } else {
      //         console.log("Geolocation is not supported by this browser.");
      //   }
      // }

      // function showPosition(position) {
      //   x.innerHTML="Latitude: " + position.coords.latitude +
      //   "<br>Longitude: " + position.coords.longitude;
      // }

      // function getPosition() {
      //   if (navigator.geolocation) {
      //     navigator.geolocation.watchPosition(showPosition);
      //   } else {
      //     console.log("Geolocation is not supported by this browser.");
      //   }
      // }

      // function setUserLocation(position) {
      //   userLat = position.coords.latitude;
      //   userLong = position.coords.longitude;
      // }

      //function to download xml file with markers
      function downloadUrl(url,callback) {
        var request = window.ActiveXObject ?
          new ActiveXObject('Microsoft.XMLHTTP') :
          new XMLHttpRequest;

        request.onreadystatechange = function() {
          if (request.readyState == 4) {
            request.onreadystatechange = function(){};
            callback(request, request.status);
          }
        };

        request.open('GET', url, true);
        request.send(null);
      }

      //function to add note markers to map
      function addNoteMarkers(notes) {
        // infowindow
        var infowindow = new google.maps.InfoWindow;

        Array.prototype.forEach.call(notes, function(note){
          //create text element for info window
          var content = document.createElement('a');
          content.setAttribute('href', 'notepage.php?nid="'+note.nid+'"');
          var strong = document.createElement('strong');
          strong.textContent = note.ntext;
          content.appendChild(strong);

          //maker for note
          var marker = new google.maps.Marker({
            position: new google.maps.LatLng(note.latitude, note.longitude),
            title: '' + note.nid,
            map: map
          });

          marker.addListener('click', function() {
            infowindow.setContent(content);
            infowindow.open(map, marker);
          });

        });
      }


      function initMap() {
      //  position = navigator.geolocation.getCurrentPosition(setUserLocation);

        map = new google.maps.Map(document.getElementById('map'), {
          //make center user's current location
          //center: {lat: -34.397, lng: 150.644},
          // center: {lat: , lng: +userLong},
          zoom: 8,
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



        // if (navigator.geolocation) {
        //   navigator.geolocation.getCurrentPosition(function (position) {
        //     userLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
        //     map.setCenter(userLocation);
        //
        //     //add marker for users location
        //     var userMarker = new google.maps.Marker({
        //       position: userLocation,
        //       map: map,
        //       icon: 'images/blue-dot.png'
        //     });
        //   });
        // }

      //  map.setCenter({lat: userLat, lng: userLong});


        // var iconBase = 'https://maps.google.com/mapfiles/kml/shapes/';
        // var image = {
        //   url: 'images/sticky-note-blue.png',
        //   size: new google.maps.Size(20, 32),
        //   origin: new google.maps.Point(0, 0),
        //   anchor: new google.maps.Point(0, 32)
        //
        // };


        //add markers for viewable notes
        var notePosition = {lat: -34.401, lng: 150.652};
        var noteMarker = new google.maps.Marker({
          position: notePosition,
          map: map,
          icon: 'images/sticky-note-blue-small.png'
        });
        var noteText = 'This is the note text!';
        var noteInfo = new google.maps.InfoWindow({
          //content: noteText
        });

        noteMarker.addListener('click', function() {
          noteInfo.setContent('Note 1 Text!');
          noteInfo.open(map, noteMarker);
        });

        //add note 2 for testing
        var note2Position = {lat: -34.501, lng: 150.752};
        var note2Marker = new google.maps.Marker({
          position: note2Position,
          map: map,
          icon: 'images/sticky-note-red-small.png'
        });
        //var note2Text = 'This is the note 2 text!';
        // var note2Info = new google.maps.InfoWindow({
        //   content: note2Text
        // });

        note2Marker.addListener('click', function() {
          noteInfo.setContent('<a href="notepage.php">Note 2 Text!</a>');
          noteInfo.open(map, note2Marker);
        });

        //call add notes function
        addNoteMarkers(notes);




        //
        // //add notes from json Array
        // for(var i=0; i<notes.length; i++) {
        //   // marker
        //   var position = {lat: Number(notes[i].latitude), lng: Number(notes[i].longitude)};
        //   var marker = new google.maps.Marker({
        //     position: position,
        //     title: ''+notes[i].nid,
        //     map: map
        //   });
        //
        //   var content = document.createElement('div');
        //   var strong = document.createElement('strong');
        //   strong.textContent = notes[i].ntext;
        //   content.appendChild(strong);
        //
        //
        //
        //   // // infowindow
        //   // var infowindow = new google.maps.InfoWindow({
        //   //   content: '<a href="notepage.php">'+notes[i].ntext+'</a>',
        //   //   map: map,
        //   //   position: position
        //   // });
        //
        //   marker.addListener('click', function() {
        //     infowindow.setContent(content);
        //     infowindow.open(map, marker);
        //   });


          //add event listener to open note info on click
          // marker.addListener('click', function() {
          //   noteInfo.open(map, marker);
          // });


      //  }

        //add note markers
      //   downloadUrl('fetch_nodes.php', function(data) {
      //     var xml = data.responseXML;
      //     var markers = xml.documentElement.getElementsByTagName('marker');
      //     Array.prototype.forEach.call(markers, function(markerElem) {
      //       var id = markerElem.getAttribute('nid');
      //       //var name = markerElem.getAttribute('name');
      //       //var address = markerElem.getAttribute('addresss');
      //       //var type = markerElem.getAttribute('type');
      //       var point =  google.maps.LatLng(
      //         parseFloat(markerElem.getAttribute('lat')),
      //         parseFloat(markerElem.getAttribute('lng')));
      //
      //       // var infowincontent = document.createElement('div');
      //       // var strong = document.createElement('strong');
      //       // strong.textContent = name
      //       // infowincontent.appendChild(strong);
      //       // infowincontent.appendChild(document.createElement('br'));
      //
      //       // var text = document.createElement('text');
      //       // text.textContent = address
      //       // infowincontent.appendChild(text);
      //       // var icon = customLabel[type] || {};
      //       var marker = new google.maps.Marker({
      //         map: map,
      //         position: point,
      //         //label: icon.label
      //     });
      //   });
      // });

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

        // for (var key in icons) {
        //   var type = icons[key];
        //   var name = type.name;
        //   var icon = type.icon;
        //   var div = document.createElement('div');
        //   div.innerHTML = '<img src="' + icon + '"> ' + name;
        //   legend.appendChild(div);
        // }

        map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(legend);

      }
    </script>
    <script async defer
      src="https://maps.googleapis.com/maps/api/js?key=<?php echo $map_key; ?>&callback=initMap" type="text/javascript">
    </script>
  </body>

</html>

<?php
// Include config file
require_once "config.php";

//Get session variables
session_start();
$uid = $_SESSION["uid"];
$username = $_SESSION["username"];

//array to store notes
$notes = array();

//get viewable notes from database
// Prepare a select statement
$sql = "SELECT * FROM note ";

if($stmt = $conn->prepare($sql)){

    // Attempt to execute the prepared statement
    if($stmt->execute()){
      //store results
      $result = $stmt->get_result();

      //iterate through rows
      while ($row = $result->fetch_assoc()) {
        // echo '<p>Row:'.$row.'</p>';
        //store row in notes array
        $notes[] = $row;
      }

      // $notes = json_encode($notes);
      //
      // echo $notes;

    } else{
        echo "Oops! Something went wrong. Please try again later.";
    }
}

// // Start XML file, create parent node
// $doc = domxml_new_doc("1.0");
// $node = $doc->create_element("markers");
// $parnode = $doc->append_child($node);


// //get viewable notes from database
// // Prepare a select statement
// $sql = "SELECT nid, latitude, longitude FROM notes ";
//
// if($stmt = $conn->prepare($sql)){
//     // Bind variables to the prepared statement as parameters
//   //  $stmt->bind_param("s", $param_username);
//
//     // Set parameters
//     //$param_username = trim($_POST["username"]);
//
//     // Attempt to execute the prepared statement
//     if($stmt->execute()){
//       //store results
//       $result = $stmt->get_result();
//
//       //iterate through rows
//       while ($row = $result->fetch_assoc()) {
//         // Add to XML document node
//         $node = $doc->create_element("marker");
//         $newnode = $parnode->append_child($node);
//         $newnode->set_attribute("id", $row['nid']);
//         $newnode->set_attribute("lat", $row['latitude']);
//         $newnode->set_attribute("long", $row['longitude']);
//         // $newnode->set_attribute("type", $row['type']);
//       }
//
//       $xmlfile = $doc->dump_mem();
//
//     } else{
//         echo "Oops! Something went wrong. Please try again later.";
//     }
// }

?>

<!DOCTYPE html>
<html>

<head>
    <title>Oingo Map</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
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
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }

    </style>
  </head>
  <body>
    <div id="map"></div>
    <div id="legend"><h3>Legend</h3></div>
    <script>
      var map;
      var userLat;
      var userLong;

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

        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function (position) {
            userLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
            map.setCenter(userLocation);

            //add marker for users location
            var userMarker = new google.maps.Marker({
              position: userLocation,
              map: map,
              icon: 'images/blue-dot.png'
            });
          });
        }

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
      //       var point = new google.maps.LatLng(
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

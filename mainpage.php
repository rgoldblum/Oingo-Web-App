<?php
// Include config file
require_once "config.php";

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
    <script type='text/javascript' src='config.js'></script>
    <script>
      var map;
      function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
          //make center user's current location
          center: {lat: -34.397, lng: 150.644},
          zoom: 8,
          disableDefaultUI: true
        });

        //add markers for viewable notes
        var iconBase = 'https://maps.google.com/mapfiles/kml/shapes/';
        var image = {
          url: 'images/sticky-note-blue.png',
          size: new google.maps.Size(20, 32),
          origin: new google.maps.Point(0, 0),
          anchor: new google.maps.Point(0, 32)

        };

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

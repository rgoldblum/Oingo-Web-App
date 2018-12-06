var map;
var userLat;
var userLong;

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
// function downloadUrl(url,callback) {
//   var request = window.ActiveXObject ?
//     new ActiveXObject('Microsoft.XMLHTTP') :
//     new XMLHttpRequest;
//
//   request.onreadystatechange = function() {
//     if (request.readyState == 4) {
//       request.onreadystatechange = function(){};
//       callback(request, request.status);
//     }
//   };
//
//   request.open('GET', url, true);
//   request.send(null);
// }


function showNotes(notes) {
  Array.prototype.forEach.call(notes, function(note) {
    var marker = new google.maps.Marker({
      position: new google.maps.LatLng(note.lat, note.lng),
      map: map
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

  //add note markers
  downloadUrl('fetch_nodes.php', function(data) {
    var xml = data.responseXML;
    var markers = xml.documentElement.getElementsByTagName('marker');
    Array.prototype.forEach.call(markers, function(markerElem) {
      var id = markerElem.getAttribute('nid');
      //var name = markerElem.getAttribute('name');
      //var address = markerElem.getAttribute('addresss');
      //var type = markerElem.getAttribute('type');
      var point = new google.maps.LatLng(
        parseFloat(markerElem.getAttribute('lat')),
        parseFloat(markerElem.getAttribute('lng')));

      // var infowincontent = document.createElement('div');
      // var strong = document.createElement('strong');
      // strong.textContent = name
      // infowincontent.appendChild(strong);
      // infowincontent.appendChild(document.createElement('br'));

      // var text = document.createElement('text');
      // text.textContent = address
      // infowincontent.appendChild(text);
      // var icon = customLabel[type] || {};
      var marker = new google.maps.Marker({
        map: map,
        position: point,
        //label: icon.label
    });
  });
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

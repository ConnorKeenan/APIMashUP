<!DOCTYPE html>
<html>
  <head>
    <style>

      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
         float:left; width:85%; height:100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
	  height: 100%;
		padding:0;
		margin: 0;         
      }
	  
	  #sideContent{
	  float:left; width:15%; height:100%;
	  }
	
	
    </style>
  </head>
  <body>
<div id="map"></div>
<div id="sideContent">
<h1>GitHubJobs</h1>

<?php include 'RetrieveJobs.php';?>
</div>

    <script>
      var map;
      function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
          zoom: 0,
          center: new google.maps.LatLng(2.8,-187.3),
          mapTypeId: 'terrain'
        });

		// Create a <script> tag and set the USGS URL as the source.
        var script = document.createElement('script');
        // This example uses a local copy of the GeoJSON stored at
        // http://earthquake.usgs.gov/earthquakes/feed/v1.0/summary/2.5_week.geojsonp
		script.src = 'testALLJSON.json';
        document.getElementsByTagName('head')[0].appendChild(script);  
	
      }
      // Loop through the results array and place a marker for each
      // set of coordinates.	  
      window.eqfeed_callback = function(results) {
	  var markerBounds = new google.maps.LatLngBounds(); 					//Keeps track of map bounds
	  
        for (var i = 0; i < results.features.length; i++) {
          var coords = results.features[i].geometry.coordinates;
          var latLng = new google.maps.LatLng(coords[0],coords[1]);
		  var title = results.features[i].properties.place;
		  var image = results.features[i].markerType;
          var marker = new google.maps.Marker({
            position: latLng,
            map: map,
			title: title,
			icon: image
          });
		  markerBounds.extend(latLng);  									//Extends bounds by marker
        }
		map.fitBounds(markerBounds);										//Resizes  map to fit all markers  
		}
		
	  
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=   [key here]    k&callback=initMap">
    </script>
  </body>
</html>



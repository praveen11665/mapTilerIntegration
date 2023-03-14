<?php
  // connect to the database
  $pdo = require 'connect.php';

  // Find device date list based on get method of device key.
  $locationDetails = fetch_location_list($pdo, isset($_GET['device']) ? $_GET['device'] : 'k', isset($_GET['date']) ? $_GET['date'] : '2023-03-08');
  $pdo = null; /* CLose connection*/
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>MapBox</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Maptiler Javascript JS module -->
    <script src="./node_modules/mapbox-gl/dist/mapbox-gl.js"></script>
    <link href="./node_modules/mapbox-gl/dist/mapbox-gl.css" rel="stylesheet" />
    
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Jquery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>

    <style type="text/css">
      .map-layout {
        margin-top: 5rem;
        width: calc(100vw - 10px);
        height: calc(90vh - 10px);
      }
    </style>
  </head>
  <body>
    <div class="container mt-4">
      <h3>MapBox View</h3>
      <div class="card mt-4">
        <div class="card-head">
          <h5><em>Map Style</em></h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-2">
              <div class="form-check">
                <input type="radio" class="form-check-input" name="map_type" value="street" checked>
                <label class="form-check-label" for="radio1">Street</label>
              </div>
            </div>
            <div class="col-2">
              <div class="form-check">
                <input type="radio" class="form-check-input" name="map_type" value="outdoor" >
                <label class="form-check-label" for="radio1">Outdoor</label>
              </div>
            </div>
            <div class="col-2">
              <div class="form-check">
                <input type="radio" class="form-check-input" name="map_type" value="light" >
                <label class="form-check-label" for="radio1">Light</label>
              </div>
            </div>
            <div class="col-2">
              <div class="form-check">
                <input type="radio" class="form-check-input" name="map_type" value="dark" >
                <label class="form-check-label" for="radio1">Dark</label>
              </div>
            </div>
            <div class="col-2">
              <div class="form-check">
                <input type="radio" class="form-check-input" name="map_type" value="satellite_street" >
                <label class="form-check-label" for="radio1">Satellite</label>
              </div>
            </div>
            <div class="col-2">
              <div class="form-check">
                <input type="radio" class="form-check-input" name="map_type" value="satellite_street" >
                <label class="form-check-label" for="radio1">Satellite Street</label>
              </div>
            </div>
            <div class="col-2">
              <div class="form-check">
                <input type="radio" class="form-check-input" name="map_type" value="navigation_day" checked>
                <label class="form-check-label" for="radio1">Navigation Day</label>
              </div>
            </div>
            <div class="col-2">
              <div class="form-check">
                <input type="radio" class="form-check-input" name="map_type" value="navigation_night" >
                <label class="form-check-label" for="radio1">Navigation Night</label>
              </div>
            </div>
          </div> 
        </div>
      </div>

      <div class="card mt-4">
        <div class="card-body">
          <div class="row">
            <div class="col-6">
              <label>Geo Line Color</label>
              <select class="form-select" name="geo_line_color" id="geo_line_color" aria-label="Default select example">
                <option selected value="#3e3b3e">Black</option>
                <option value="#eb4c2a">Red</option>
                <option value="#6cf01c">Green</option>
                <option value="#1cf0dd">Cyan</option>
                <option value="#1ca6f0">Blue</option>
                <option value="#791cf0">Violet</option>
                <option value="#ea1cf0">Orange</option>
              </select>
            </div>
            <div class="col-2"></div>
            <div class="col-4">
              <div class="mt-4">
                <input type="checkbox" class="form-check-input" checked name="map_with_marker" value="true" >
                <label class="ms-3 form-check-label" for="radio1">Geo line with marker</label>
              </div>
            </div>
          </div>
        </div>
      </div>      
    </div>
    <div id="map" class="map-layout">
        
     </div>
    <script>
      const mapData = <?php echo json_encode($locationDetails); ?>; /*setting php array to javascrit array*/

      const geoLineData = mapData.map(function(item){ /*changing map cooridates to geo line coordinates.*/
        return [item.longitude,item.latitude];
      });

      var baseUrl = 'mapbox://styles/mapbox/streets-v12'  /* mapbox street style url*/

      $('.form-check-input').on('click',function(){
        changeMapType(this.value)
      })

      $('#geo_line_color').on('change',function(){
        changeMapType($("input[name='username']").val())
      })

      function changeMapType(type) { /*This function is used change map type for fetched locations*/

        switch (type) { /*Based on type base url of server api will change*/
          case 'street':
            baseUrl = `mapbox://styles/mapbox/streets-v12` ;
            break;
          case 'outdoor':
            baseUrl = `mapbox://styles/mapbox/outdoors-v12` ;
            break;
          case 'light':
            baseUrl = `mapbox://styles/mapbox/light-v11` ;
            break;
          case 'dark':
            baseUrl = `mapbox://styles/mapbox/dark-v11` ;
            break;
          case 'satellite':
            baseUrl = `mapbox://styles/mapbox/satellite-v9` ;
            break;
          case 'satellite_street':
            baseUrl = `mapbox://styles/mapbox/satellite-streets-v12` ;
            break;
          case 'navigation_day':
            baseUrl = `mapbox://styles/mapbox/navigation-day-v1` ;
            break;
          case 'navigation_night':
            baseUrl = `mapbox://styles/mapbox/navigation-night-v1` ;
            break;
        }

        // TO MAKE THE MAP APPEAR YOU MUST ADD YOUR ACCESS TOKEN FROM https://account.mapbox.com
        mapboxgl.accessToken = 'pk.eyJ1IjoiMjAyM2ltcHJlcy1wc2EiLCJhIjoiY2xlaXFrNG9rMDQycTNycjA1cWdxNTNlMCJ9.cX-R5wmsl9YLWS7SrY2xAA';
        const map = new mapboxgl.Map({
            container: 'map',
            // Choose from Mapbox's core styles, or make your own style with Mapbox Studio
            style: baseUrl,
            center: [mapData[0].longitude, mapData[0].latitude],
            zoom: 14
        });

        /*This functions load map Data with GEO Lines*/

        map.on('load', function () {
          map.addSource('route', {
            'type': 'geojson',
            'data': {
              'type': 'Feature',
              'properties': {},
              'geometry': {
                'type': 'LineString',
                'coordinates': geoLineData
              }
            }
          });

          map.addLayer({
            'id': 'route',
            'type': 'line',
            'source': 'route',
            'layout': {
              'line-join': 'round',
              'line-cap': 'round'
            },
            'paint': {
              'line-color': $('#geo_line_color').val(),
              'line-width': 8
            }
          });
        });

        map.addControl(new mapboxgl.NavigationControl(), 'top-right');

        /*Geo line checkbox based marker in map*/
        if ($('input[name=map_with_marker]:checked').val() != undefined) {
          mapData.map(function(item){  /* This map function fetched db long/latt data and marked it in map*/

            /*This will create marker in map*/
            new mapboxgl.Marker({
              color: "#eb4c2a", /*Marker color*/
              draggable: true  /*Marked marker can able to drag*/
            }).setLngLat([item.longitude, item.latitude])
            .addTo(map);

            return item
          }) 
        }

      }

      changeMapType('basic');

    </script>
  </body>
</html>
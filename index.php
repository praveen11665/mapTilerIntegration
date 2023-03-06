<?php
phpinfo();
  $servername = "localhost";  /* enter server name*/
  $username = "root";  /* enter server user name*/
  $password = "";   /* enter server user password*/
  $dbname = "map";   /* enter database name to access table*/

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  $sql = "SELECT longitude, latitude FROM map_details"; /* Select query from DB*/
  $result = $conn->query($sql);

  $mapRows = [];
  if ($result->num_rows > 0) {  /*Checking the rows in db*/
      $mapRows = $result->fetch_all(MYSQLI_ASSOC);  /*Fetch all rows as associate array*/
  }

  $conn->close(); /* CLose connection*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>MapTiler</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Maptiler Javascript JS module -->
  <script src="./node_modules/maplibre-gl/dist/maplibre-gl.js"></script>
  <link href="./node_modules/maplibre-gl/dist/maplibre-gl.css" rel="stylesheet" />
  
  <!-- Bootstrap CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

  <style type="text/css">
    .map-layout {
      margin-top: 5rem;
      width: 1350px;
      height: 350px;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="row mt-3">
        <div class="col-4">
          <label for="longitude" class="form-label">Longitude:</label>
          <input class="form-control" value="80.237617" id="longitude" placeholder="Enter longitude" name="longitude">
        </div>
        <div class="col-4">
          <label for="latitude" class="form-label">Latitude:</label>
          <input class="form-control" value="13.067439" id="latitude" placeholder="Enter latitude" name="latitude">
        </div>
        <div class="col-4 mt-4">
          <button onclick="searchInMap()" class="btn btn-outline-success">Search</button>
        </div>
    </div>
    
    <div id="map" class="map-layout">
      
    </div>
      
  </div>
  <script>
    const mapData = <?php echo json_encode($mapRows); ?>;
    const baseUrl = `http://localhost:3650/api/maps/basic/style.json`  /* Local maptiler server's vector style url*/
    /*default view point center*/
    const centerLogLat = [80.237617, 13.067439]  // [Longitude, Latitude]

    /*This will create map based on json data from local server*/
    const map = new maplibregl.Map({
      container: 'map', // container's id or the HTML element in which MapLibre GL JS will render the map
      style: baseUrl, // style URL
      center: centerLogLat, // starting position [lng, lat]
      zoom: 5, // starting zoom
    });
    map.addControl(new maplibregl.NavigationControl(), 'top-right');

    mapData.map(function(item){  /* This map function fetched db long/latt data and marked it in map*/

      /*This will create marker in map*/
      new maplibregl.Marker({
        color: "#eb4c2a", /*Marker color*/
        draggable: true  /*Marked marker can able to drag*/
      }).setLngLat([item.longitude, item.latitude])
      .addTo(map);

      return item
    })

    function searchInMap(event) { /*This function is used for active search in map of given long and lat*/
      /*Below code read the input value from html*/
      var latitude = document.getElementById("latitude").value
      var longitude = document.getElementById("longitude").value

      /*This will create marker dynamically on map*/
      const map = new maplibregl.Map({
        container: 'map', // container's id or the HTML element in which MapLibre GL JS will render the map
        style: baseUrl, // style URL
        center: [longitude, latitude], // starting position [lng, lat]
        zoom: 5, // starting zoom
      });
      map.addControl(new maplibregl.NavigationControl(), 'top-right');

      new maplibregl.Marker({
        color: "#eb4c2a",
        draggable: true
      }).setLngLat([longitude, latitude])
      .addTo(map);
      
    }

  </script>
</body>
</html>

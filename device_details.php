<?php

  // connect to the database
  $pdo = require 'connect.php';

  // fetch device date list based on get method of device key.
  $deviceDetails = fetch_device_by_data($pdo, isset($_GET['device']) ? $_GET['device'] : 'k');

  $pdo = null; /* CLose connection*/

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
    .table-viewport {
      height: calc(90vh - 100px);
      width: calc(35vw - 10px);
    }
  </style>
  
</head>
<body>
  <div class="container mt-4">
    <h3> <?php echo isset($_GET['device']) ? $_GET['device'] : 'k'; ?>'s Device Details</h3>
    <div class="card table-viewport">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-sm table-hover">
            <thead>
              <tr>
                <th>Device Name</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              <?php 
                if(!empty($deviceDetails)){
                  foreach ($deviceDetails as $value) {
                    echo "<tr>
                      <td>".$value['device_name']."</td>
                      <td> 
                        <div class='d-flex'>
                          <p>".$value['signal_time']."</p>
                          <a class='btn btn-outline-success ms-4' href='./map_details.php?device=".$value['device_name']."&date=".$value['signal_time']."' data-bs-toggle='tooltip'  style='text-decoration: none;' title='View details in map tiler'>MapTiler </a>
                          <a class='btn btn-outline-success ms-4' href='./map_box_details.php?device=".$value['device_name']."&date=".$value['signal_time']."' data-bs-toggle='tooltip'  style='text-decoration: none;' title='View details in map box'>MapBox </a>
                        </div>
                      </td>
                      </tr>";
                  }
                } else {
                  echo "<tr>
                    <td class='text-muted text-center' colspan='2'>
                      No records found
                    </td>
                  </tr>";
                }
              ?>
              
            </tbody>
          </table>
        </div> 
      </div>
    </div>
         
  </div>
  <script>

  </script>
</body>
</html>
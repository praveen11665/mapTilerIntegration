<?php
  $conn      = require 'connect.php'; /**/

  // fetch device data with group of device name from db.
  $devices = fetch_device_data($conn);
  
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>MapTiler</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>  

    <style type="text/css">
      .table-scroll {
        max-height: calc(100vh - 150px);
        overflow: auto;
      }

      :root {
        --scroll-bar-color: #c5c5c5;
        --scroll-bar-bg-color: #f6f6f6;
      }

      ::-webkit-scrollbar-corner { background: rgba(0,0,0,0.5); }      

      *::-webkit-scrollbar {
        width: 12px;
        height: 12px;
      }

      *::-webkit-scrollbar-track {
        background: var(--scroll-bar-bg-color);
      }

      *::-webkit-scrollbar-thumb {
        background-color: var(--scroll-bar-color);
        border-radius: 20px;
        border: 3px solid var(--scroll-bar-bg-color);
      }
      .table-viewport {
        height: calc(90vh - 100px);
        width: calc(35vw - 10px);
      }
    </style>
  </head>

  <body>
    <div class="container mt-4">

      <h3>Device Details</h3>
      <div class="card table-viewport">
        <div class="card-body">
          <div class="row mt-3">
            <div class="table-scroll">          
              <table class="table table-sm table-bordered">
                <thead>
                  <tr>
                    <th>S.No</th>
                    <th>Device Name</th>                
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $count = 1;
                    if(empty($devices)) {
                        ?>
                          <tr>
                            <td class="text-muted text-center" colspan="2">
                              No records found
                            </td>
                          </tr>
                        <?php
                    } else {
                      foreach ($devices as $device) {
                        ?>
                          <tr>
                            <td><?php echo $count++ ?></td>
                            <td>
                              <a style="text-decoration: none;" data-bs-toggle="tooltip" title='View device details' href="device_details.php?device=<?php echo $device['device_name'] ?>" class="btn btn-sm btn-link">
                                <?php echo $device['device_name'] ?>
                              </a>
                            </td>
                          </tr>
                        <?php
                      }
                    }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
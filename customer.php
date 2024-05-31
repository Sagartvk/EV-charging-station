<?php
require 'includes/connection.php';
session_start();

function haversineDistance($lat1, $lon1, $lat2, $lon2)
{
  $radius = 6371;
  $lat1 = deg2rad($lat1);
  $lon1 = deg2rad($lon1);
  $lat2 = deg2rad($lat2);
  $lon2 = deg2rad($lon2);
  $latDiff = $lat2 - $lat1;
  $lonDiff = $lon2 - $lon1;
  $a = sin($latDiff / 2) * sin($latDiff / 2) + cos($lat1) * cos($lat2) * sin($lonDiff / 2) * sin($lonDiff / 2);
  $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
  $distance = $radius * $c;

  return $distance;
}


if (!isset($_SESSION['id'])) {
  header("Location: index.php");
}

$id = $_SESSION['id'];

$name = $_SESSION['name'];

$email = mysqli_execute_query($conn, "SELECT uname, phone FROM users WHERE id=$id");
$email = mysqli_fetch_row($email)[0];

$res = mysqli_execute_query($conn, "SELECT a.*, count(b.bid), c.location, c.latitude, c.longitude FROM bunk_admin AS a LEFT JOIN bunk_slots AS b ON a.id=b.bid LEFT JOIN geolocation as c ON a.id=c.id WHERE a.status=1 group by a.id;");
$bunks = [[]];
if (mysqli_num_rows($res) >= 1) {
  $bunks = mysqli_fetch_all($res);
}

$bookings = [];
$res = mysqli_execute_query($conn, "SELECT b.name, b.phone, d.stype, a.date, a.time_slot, c.location, a.id FROM booking AS a LEFT JOIN bunk_admin AS b ON a.bid = b.id LEFT JOIN geolocation AS c ON c.id = b.id LEFT JOIN bunk_slots AS d ON d.sid = a.sid WHERE a.status = 0 AND a.uid = $id");
if (mysqli_num_rows($res) >= 1) {
  $bookings = mysqli_fetch_all($res);
}

$book_previous = [];
$res = mysqli_execute_query($conn, "SELECT b.name, b.phone, d.stype, a.date, a.time_slot, a.unit FROM booking AS a LEFT JOIN bunk_admin AS b ON a.bid = b.id LEFT JOIN bunk_slots AS d ON d.sid = a.sid WHERE a.status = 1 AND a.uid = $id");
if (mysqli_num_rows($res) >= 1) {
  $book_previous = mysqli_fetch_all($res);
}

if (isset($_GET['lat'])) {
  $lat = $_GET['lat'];
  $lon = $_GET['lon'];
  $cnt = 0;
  foreach ($bunks as $bunk) {
    $lat2 = $bunk[12];
    $lon2 = $bunk[13];

    if (haversineDistance($lat, $lon, $lat2, $lon2) > 8) {
      $bunks[$cnt] = [];
    }

    $cnt++;
  }
}



if (isset($_POST['pswd'])) {
  $pswd = $_POST['pswd'];
  $pswd2 = $_POST['pswd2'];
  if ($pswd == $pswd2) {
    $pswd = md5($pswd);
    mysqli_execute_query($conn, "UPDATE users SET pswd='$pswd' WHERE id=$id");
    echo "<script>alert('Password changed successfull!')</script>";
  } else {
    echo "<script>alert('Mismatching passwords')</script>";
  }
}



if (isset($_GET['bid'])) {
  $bid = $_GET['bid'];
  $slots = mysqli_execute_query($conn, "SELECT * FROM bunk_slots WHERE bid=$bid AND status='Operational' AND curstat='AVAILABLE'");
  $slots = mysqli_fetch_all($slots);
  $location = mysqli_execute_query($conn, "SELECT location FROM geolocation WHERE id=$bid");
  $location = mysqli_fetch_row($location)[0];

  $tbooks = mysqli_execute_query($conn, "SELECT sid, count(*), date, time_slot FROM booking WHERE bid=$bid GROUP BY date, time_slot, sid;");
  $tbooks = mysqli_fetch_all($tbooks);
}




if (isset($_POST['pdate'])) {
  $bid = $_POST['bid'];
  $sid = $_POST['sid'];
  $pdate = $_POST['pdate'];
  $ptime = $_POST['ptime'];
  $res = mysqli_execute_query($conn, "INSERT INTO booking (uid, sid, bid, date, time_slot) VALUES ($id, $sid, $bid, '$pdate', '$ptime')");
  if ($res) {
    echo "<script>alert('Successfully booked slot');</script>";
  } else {
    echo "<script>alert('Failed to book')</script>";
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>EcoCharge Navigator - Customer</title>
  <!-- base:css -->
  <link rel="stylesheet" href="vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/custom.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
</head>

<body>
  <div class="container-scroller d-flex">

    <!-- partial:./partials/_sidebar.html -->
    <nav class="sidebar sidebar-offcanvas bg-dark bg-gradient" id="sidebar">
      <ul class="nav">
        <li class="nav-item sidebar-category">
          <p>Navigation</p>
          <span></span>
        </li>
        <li class="nav-item">
          <a role="button" class="nav-link" href="customer.php">
            <i class="mdi mdi-view-quilt menu-icon"></i>
            <span class="menu-title">Dashboard</span>
            <div class="badge badge-info badge-pill">2</div>
          </a>
        </li>
        <li class="nav-item sidebar-category">
          <p></p>
          <span></span>
        </li>
        <li class="nav-item">
          <a role="button" class="nav-link" id='btn1'>
            <i class="mdi mdi-gas-station  menu-icon"></i>
            <span class="menu-title">Search Stations</span>
            <i class="menu-arrow"></i>
          </a>
        </li>
        <li class="nav-item">
          <a role="button" class="nav-link" id="btn2">
            <i class="mdi mdi-chart-pie menu-icon"></i>
            <span class="menu-title">View Bookings</span>
          </a>
        </li>
        <li class="nav-item">
          <a role="button" class="nav-link" id="btn3">
            <i class="mdi mdi-grid-large menu-icon"></i>
            <span class="menu-title">Charging History</span>
          </a>
        </li>
        <br />
        <li class="nav-item sidebar-category">
          <p>Settings</p>
          <span></span>
        </li>
        <li class="nav-item">
          <a role="button" class="nav-link" id="btn4">
            <i class="mdi mdi-account menu-icon"></i>
            <span class="menu-title">Profile Settings</span>
          </a>
        </li>
        <li class="nav-item">
          <a role="button" class="nav-link" href="index.php">
            <i class="mdi mdi-logout menu-icon"></i>
            <span class="menu-title">Logout</span>
          </a>

        </li>
      </ul>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:./partials/_navbar.html -->
      <nav class="navbar col-lg-12 col-12 px-0 py-0 py-lg-4 d-flex flex-row" style=" background: url(images/other/navbar-cover2.jpg) center center no-repeat; background-size: cover;">
        <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
          </button>
          <div class="navbar-brand-wrapper">
          <a role="button" class="navbar-brand brand-logo" href="index.html"><img src="images/logo.png" width="80" alt="logo" /></a>
          </div>
          <h4 class="font-weight-bold mb-0 d-none d-md-block mt-1">Welcome back, <?php echo $name; ?></h4>
          <ul class="navbar-nav navbar-nav-right">
          </ul>

        </div>

      </nav>

      <div id="div1">
        <div class="container mt-4">
          <div class="col-lg-8 mx-auto">
            <div class="form-group">
              <label for="exampleInputPassword">Search EV Charging Stations</label>
              <div class="input-group">

                <input type="text" id='search' class="form-control form-control-lg border-left-0" name="search" placeholder="For better results Search with place, district, state, pincode" required>
                <div class="input-group-postpend bg-transparent">
                  <button class='btn btn-block btn-lg btn-dark' id='sbtn'>SEARCH</button>
                </div>
              </div>
            </div>
            <div class='mt-4'>
              <table border="0">
                <tbody>
                  <?php
                  foreach ($bunks as $bk) {
                    if (isset($bk[1])) {
                      $s1 = $bk[9] == '0' ? 'selected' : '';
                      $s2 = $bk[9] == '1' ? 'selected' : '';
                      echo "<tr>
                    <td>
                    
                    <div class='ls-slot mt-4'> <div class='row'> <div class='col-lg-4'><h4 align='center'>$bk[1]</h4><img src='images/bunks/b1.png' class='img-fluid'></div>

                    <div class='col-lg-8'>
                    <table border='0' cellpadding='5'> <tr> <td>Email</td> <td>:</td> <td>$bk[2]</td> </tr> <tr> <td>Phone</td> <td>:</td> <td>$bk[4]</td> </tr> <tr> <td>Place</td> <td>:</td> <td>$bk[5]</td> </tr> <tr> <td>District</td> <td>:</td> <td>$bk[6]</td> </tr> <tr> <td>State</td> <td>:</td> <td>$bk[7]</td> </tr> <tr> <td>Pin Code</td> <td>:</td> <td>$bk[8]</td> </tr> <tr> <td>Total Slots</td> <td>:</td> <td>$bk[10] Units</td> </tr><tr><td colspan='3'><button class='btn btn-dark btn-block btn-sm w-100' onclick='gdiv($bk[0])'>SELECT</button></td></tr></table></div><div class='container'> '<a href='$bk[11]' target='_blank' class='btn btn-primary' style='width: 100%' role='button'>VIEW GEO LOCATION</a> </div></div></div>
                    
                    </td>
                    </tr>";
                    }
                  }

                  ?>
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </div>
      <div id="div2" style="display: none">
        <div class="col-lg-10 mt-4 mx-auto">
          <h3>Booking List</h3>
          <div class="">
            <table class="table">
              <thead>
                <th>Station Name</th>
                <th>Location</th>
                <th>Phone Number</th>
                <th>Slot Type</th>
                <th>Date</th>
                <th>Time Slot</th>
                <th>Close Booking</th>
              </thead>
              <tbody>
                <?php
                foreach ($bookings as $bk) {
                  echo "<tr><td>$bk[0]</td><td><a href='$bk[5]' target='_blank' class='btn btn-dark btn-sm'>VIEW MAP</a></td><td>$bk[1]</td><td>$bk[2]</td><td>$bk[3]</td><td>$bk[4]</td><td><a role='btn' onclick='closebook($bk[6])' target='_blank' class='btn btn-dark btn-sm'>CANCEL</a></td></tr>";
                }

                ?>
              </tbody>
            </table>

          </div>
        </div>
      </div>
      <div id="div3" style="display: none">

        <div class="col-lg-10 mt-4 mx-auto">
          <h3>Charging History</h3>
          <div class="">
            <table class="table">
              <thead>
                <th>Station Name</th>
                <th>Phone Number</th>
                <th>Slot Type</th>
                <th>Date</th>
                <th>Time Slot</th>
                <th>Used Units</th>
              </thead>
              <tbody>
                <?php
                foreach ($book_previous as $bk) {
                  echo "<tr><td>$bk[0]</td><td>$bk[1]</td><td>$bk[2]</td><td>$bk[3]</td><td>$bk[4]</td><td>$bk[5]</td></tr>";
                }

                ?>
              </tbody>
            </table>

          </div>
        </div>

      </div>


      <div id="div4" style="display: none">
        <div class="">
          <div class="container">

            <div class="col-lg-10 mt-4 mx-auto">
              <h3>Profile Settings</h3>
              <form method="POST" action="customer.php">

                <div class="col-lg-6">
                  <div class="form-group">
                    <label>Username</label>
                    <div class="input-group">
                      <input type="text" value="<?php echo $email; ?>" name="email" class="form-control" readonly>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>Type your new password</label>
                      <div class="input-group">
                        <input type="password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" title="At least 8 characters with 1 uppercase, 1 lowercase, 1 symbol" required class="form-control form-control border-left-0" name="pswd" placeholder="Password">
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>Confirm Password</label>
                      <div class="input-group">
                        <input type="password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" title="At least 8 characters with 1 uppercase, 1 lowercase, 1 symbol" required class="form-control form-control border-left-0" name="pswd2" placeholder="Password">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="mt-6" align="center">
                  <input class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" type="submit" value="CHANGE PASSWORD">
                </div>

              </form>

            </div>
          </div>
        </div>




      </div>

      <div id="div5" style='display: none'>
        <div id="slots" class="mt-4 container">
          <h4>Choose a charging slot</h4>
          <br />
          <div class='row mx-auto'>
            <?php
            if (isset($slots)) {
              foreach ($slots as $slot) {
                $img = ['IEC 62196-2' => 'b1.png', 'Scame Type 3' => 'b2.png', 'CHAdeMO' => 'b2.png', 'CCS' => 'b2.png', 'Tesla Supercharger' => 'b3.png'][$slot[2]];
                echo "
    <div class='col-lg-1' role='button' onclick=\"listdata('$slot[2]', '$slot[3]', '$slot[4]', '$slot[5]', '$slot[7]', '$slot[0]')\" title='$slot[2]'>
        <img src='images/bunks/$img' class='img w-100'>
        <br/>
        <span style='text-align: center'>$slot[1]</span> 
    </div>
";
              }
            }

            ?>
          </div>
          <div class="mt-4">
            <form method='POST' action='customer.php'>
              <div class="row">
                <div class='col-lg-3'>
                  <div class="form-group">
                    <label>Slot Type</label>
                    <div class="input-group">
                      <input type="text" id='stypex' class="form-control" readonly>
                    </div>
                  </div>

                </div>
                <div class='col-lg-3'>
                  <div class="form-group">
                    <label>Maximum Load</label>
                    <div class="input-group">
                      <input type="text" id='sloadx' class="form-control" readonly>
                    </div>
                  </div>

                </div>
                <div class='col-lg-3'>
                  <div class="form-group">
                    <label>Maximum Voltage</label>
                    <div class="input-group">
                      <input type="text" id='svolx' class="form-control" readonly>
                    </div>
                  </div>

                </div>
                <div class='col-lg-3'>
                  <div class="form-group">
                    <label>Connector Type</label>
                    <div class="input-group">
                      <input type="text" id='ctype' class="form-control" readonly>
                    </div>
                  </div>

                </div>
              </div>
              <div class='row'>
                <div class='col-lg-4'>
                  <div class="form-group">
                    <label>Status</label>
                    <div class="input-group">
                      <input type="text" id='sstat' class="form-control" readonly>
                    </div>
                  </div>
                </div>
                <div class='col-lg-4'>
                  <div class="form-group">
                    <label>Select Date</label>
                    <div class="input-group">
                      <input type="date" id='pdate' name='pdate' class="form-control" onchange='checkavailability()' required>
                    </div>
                  </div>
                </div>
                <div class='col-lg-4'>
                  <div class="form-group">
                    <label>Select Time Slot</label>
                    <div class="input-group">
                      <select id='ptime' name='ptime' class="form-control" onchange='checkavailability()' required>
                        <option value='12.00 AM - 07.59 AM'>12.00 AM - 07.59 AM</option>
                        <option value='08.00 AM - 03.59 PM'>08.00 AM - 03.59 PM</option>
                        <option value='04.00 PM - 11.59 PM'>04.00 PM - 11.59 PM</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <input type='hidden' name='bid' value='<?php if (isset($bid)) {
                                                        echo $bid;
                                                      } ?>'>
              <input type='hidden' name='sid' value='' id='sltid'>
              <br /><br />
              <div align='center'>
                <a class='btn btn-dark btn-block' href='<?php echo $location;?>' id='map' target="_blank">VIEW MAP</a>
                <input type='submit' class='btn btn-block btn-dark' value='BOOK NOW' id='bookbtn'>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->

    <!-- base:js -->
    <script src="vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page-->
    <script src="vendors/chart.js/Chart.min.js"></script>
    <script src="js/jquery.cookie.js" type="text/javascript"></script>
    <!-- End plugin js for this page-->
    <!-- inject:js -->
    <script src="js/off-canvas.js"></script>
    <script src="js/hoverable-collapse.js"></script>
    <script src="js/template.js"></script>
    <!-- endinject -->
    <!-- plugin js for this page -->
    <script src="js/jquery.cookie.js" type="text/javascript"></script>
    <!-- End plugin js for this page -->
    <!-- Custom js for this page-->
    <script src="js/dashboard.js"></script>
    <!-- End custom js for this page-->
    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3O6O8pW7hUEi1f4h2pAFqI1/o6h8f1bBt6Uq8A=" crossorigin="anonymous"></script>
    <script>
      $('#btn1, #btn2, #btn3, #btn4, #btn5, #btn6').click(function() {
        id = this.id
        let dct = {
          btn1: '#div1',
          btn2: '#div2',
          btn3: '#div3',
          btn4: '#div4'
        };
        for (let tg of Object.values(dct)) {
          // console.log(tg)
          $(tg).slideUp(1000);
        }
        $(dct[id]).slideDown(1000);
      })



      $('#sbtn').click(function() {

        locationInput = $('#search').val()
        if (locationInput.length <= 2) {
          location.href = location.href.split('?')[0]
        }
        $.ajax({
          url: `https://nominatim.openstreetmap.org/search?q=${locationInput}&format=json`,
          method: "GET",
          dataType: "json",
          success: function(response) {
            location.href = location.href.split('?')[0]+ '?lat=' + response[0].lat + '&lon=' + response[0].lon
          }
        });


      })











      const queryString = window.location.search;
      const urlParams = new URLSearchParams(queryString);
      const bid = urlParams.get('bid');
      if (bid) {
        $('#div1').slideUp(1000)
        $('#div5').slideDown(1000);
      }


      function listdata(d1, d2, d3, d4, d5, d6) {
        // data = JSON.parse(data)
        $('#stypex').val(d1)
        $('#sloadx').val(d2 + ' kW')
        $('#svolx').val(d3 + ' V')
        $('#ctype').val(d4)
        $('#sstat').val(d5)
        $('#sltid').val(d6)
      }


      function checkavailability() {
        $.post('checkavailability.php', {sid: $('#sltid').val(), tslot: $('#ptime').val(), date: $('#pdate').val()}, (response)=>{
          response = JSON.parse(response)
          if (response.success){
            $('#bookbtn').css("display", "inline-block")
          } else {
            alert("Please choose another date or time slot!")
            $('#bookbtn').css("display", "none")
          }
        })
      }


      function gdiv(data) {
        url = location.href.split('?')
        url = url[0];
        location.href = url + '?bid=' + data
      }


      function closebook(id) {
        ls = window.confirm("Are you sure to cancel booking?")
        if (ls) {
          $.ajax({
            url: 'closebooking2.php',
            method: "POST",
            data: {
              id: id
            },
            success: function(response) {
              alert(response)
              location.reload()
            }
          })
        }
      }
    </script>
</body>

</html>
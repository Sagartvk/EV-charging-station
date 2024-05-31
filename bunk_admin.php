<?php
require 'includes/connection.php';
session_start();
if (!isset($_SESSION['id'])) {
  header("Location: index.php");
}

$sname = "SLT0001";
$id = $_SESSION['id'];
$res = mysqli_execute_query($conn, "SELECT sname FROM bunk_slots WHERE bid=$id ORDER BY sname DESC LIMIT 1");
if (mysqli_num_rows($res) >= 1) {
  $sname = mysqli_fetch_row($res)[0];
  $numericPart = substr($sname, 3);
  $numericPart = intval($numericPart) + 1;
  $sname = 'SLT' . str_pad($numericPart, 4, '0', STR_PAD_LEFT);
}

$name = $_SESSION['name'];

$res = mysqli_execute_query($conn, "SELECT a.*, b.location FROM bunk_Admin AS a LEFT JOIN geolocation as b ON a.id=b.id WHERE a.id=$id");
if (mysqli_num_rows($res) == 1) {
  $rows = mysqli_fetch_assoc($res);
  $bname = $rows['name'];
  $email = $rows['uname'];
  $address = $rows['address'];
  $state = $rows['state'];
  $district = $rows['district'];
  $phone = $rows['phone'];
  $pin = $rows['pin'];
  $url = $rows['location'];
}

if (isset($_POST['pswd'])) {
  $pswd = $_POST['pswd'];
  $pswd2 = $_POST['pswd2'];
  if ($pswd == $pswd2) {
    $pswd = md5($pswd);
    mysqli_execute_query($conn, "UPDATE bunk_admin SET pswd='$pswd' WHERE id=$id");
    echo "<script>alert('Password changed successfull!')</script>";
  } else {
    echo "<script>alert('Mismatching passwords')</script>";
  }
}

if (isset($_POST['phone'])) {
  $phone = $_POST['phone'];
  mysqli_execute_query($conn, "UPDATE bunk_admin SET phone=$phone WHERE id=$id");
  echo "<script>alert('Phone number changed!')</script>";
}

if (isset($_POST['state'])) {
  $address = $_POST['address'];
  $state = $_POST['state'];
  $district = $_POST['district'];
  $pin = $_POST['pin'];
  $url = $_POST['url'];

  $pattern = '/@(-?\d+\.\d+),(-?\d+\.\d+)/';
  preg_match($pattern, $url, $matches);
  if (isset($matches[1]) && isset($matches[2])) {
    $lt = $matches[1];
    $ln = $matches[2];
    mysqli_execute_query($conn, "UPDATE bunk_admin SET address='$address', state='$state', district='$district', pin='$pin' WHERE id=$id");
    mysqli_execute_query($conn, "UPDATE geolocation SET location='$url', latitude=$lt, longitude=$ln WHERE id=$id");
    echo "<script>alert('Location updated!')</script>";
  } else {
    echo "<script>alert('Invalid location URL!')</script>";
  }
}

$res = mysqli_execute_query($conn, "SELECT a.*, b.name, b.phone, c.stype FROM booking AS a LEFT JOIN users AS b ON a.uid=b.id LEFT JOIN bunk_slots AS c ON a.sid=c.sid WHERE a.bid=$id AND a.status=0");
$bookings = mysqli_fetch_all($res);


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>EcoCharge Navigator - Bunk Admin</title>
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
    <nav class="sidebar sidebar-offcanvas" id="sidebar">
      <ul class="nav">
        <li class="nav-item sidebar-category">
          <p>Navigation</p>
          <span></span>
        </li>
        <li class="nav-item">
          <a role="button" class="nav-link" href="bunk_admin.php">
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
          <a role="button" class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
            <i class="mdi mdi-gas-station  menu-icon"></i>
            <span class="menu-title">Charging Slots</span>
            <i class="menu-arrow"></i>
          </a>
          <div class="collapse" id="ui-basic">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a role="button" class="nav-link" id="btn1">Add</a></li>
              <li class="nav-item"> <a role="button" class="nav-link" id="btn2">Remove</a></li>
            </ul>
          </div>
        </li>
        <li class="nav-item">
          <a role="button" class="nav-link" id="btn3">
            <i class="mdi mdi-view-headline menu-icon"></i>
            <span class="menu-title">Manage Slots</span>
          </a>
        </li>
        <li class="nav-item">
          <a role="button" class="nav-link" id="btn4">
            <i class="mdi mdi-chart-pie menu-icon"></i>
            <span class="menu-title">View Bookings</span>
          </a>
        </li>
        <li class="nav-item">
          <a role="button" class="nav-link" id="btn5">
            <i class="mdi mdi-grid-large menu-icon"></i>
            <span class="menu-title">Slot Status</span>
          </a>
        </li>
        <br />
        <li class="nav-item sidebar-category">
          <p>Settings</p>
          <span></span>
        </li>
        <li class="nav-item">
          <a role="button" class="nav-link" id="btn6">
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
      <nav class="navbar col-lg-12 col-12 px-0 py-0 py-lg-4 d-flex flex-row">
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

      <div id="">

      </div>

      <!-- Add Slots -->
      <div class="" id="div1" style="display: none">
        <div class="container">
          <br />
          <form id="slots">
            <div class="row">
              <div class="col-lg-6 col-xl-6">
                <div class="form-group">
                  <label>Slot Name</label>
                  <div class="input-group">
                    <input type="text" class="form-control" name="sname" value="<?php echo $sname; ?>" readonly>
                  </div>
                </div>
              </div>

              <div class="col-lg-6 col-xl-6">
                <div class="form-group">
                  <label>Slot Type</label>
                  <div class="input-group">
                    <select class="form-control" name="stype">
                      <option value="IEC 62196-2">IEC 62196-2</option>
                      <option value="Scame Type 3">Scame Type 3</option>
                      <option value="CHAdeMO">CHAdeMO</option>
                      <option value="CCS">CCS</option>
                      <option value="Tesla Supercharger">Tesla Supercharger</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-6 col-xl-6">
                <div class="form-group">
                  <label>Maximum Load</label>
                  <div class="input-group">
                    <input type="num" class="form-control" placeholder="22kW 50kW 100kW 250kW" name="sload">
                  </div>
                </div>
              </div>
              <div class="col-lg-6 col-xl-6">
                <div class="form-group">
                  <label>Maximum Voltage</label>
                  <div class="input-group">
                    <input type="num" class="form-control" placeholder="230V 400V 500V 800V" name="svolt">
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-6 col-xl-6">
                <div class="form-group">
                  <label>Supported Connector</label>
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Type 2 & 3; CHAdeMO; CCS; Tesla" name="sconn">
                  </div>
                </div>
              </div>
              <div class="col-lg-6 col-xl-6">
                <div class="form-group">
                  <label>Status</label>
                  <div class="input-group">
                    <select class="form-control" name="sstat">
                      <option value="Operational">Operational</option>
                      <option value="Out of Service">Out of Service</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
        </div>

        <div align="center">
          <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" id="addslot">Add Slot</button>
        </div>
        </form>
      </div>

      <!-- Remove Slots -->
      <div id="div2" style="display: none">

        <div class="container mt-3">
          <div class="col-lg-8 mx-auto">
            <div class="form-group">
              <label>Slot Name</label>
              <div class="input-group">
                <input type="text" id="sname2" class="form-control form-control-lg border-left-0" placeholder="SLTXXXX">
                <div class="input-group-prepend bg-transparent">
                  <button class="btn btn-primary btn-block btn-lg" id="remslot">Remove</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div id="div3">
        <div class="container">

          <div class="col-lg-10 mt-4 mx-auto">
            <h3>List of slots</h3>
            <div id="lslots">
              <?php

              $res = mysqli_execute_query($conn, "SELECT * FROM bunk_slots WHERE bid=$id");
              if ($res) {
                $data = mysqli_fetch_all($res);
                foreach ($data as $slot) {
                  $sl1 = $slot[6] == 'Operational' ? 'selected' : '';
                  $sl2 = $slot[6] == 'Out of Service' ? 'selected' : '';
                  $sl3 = $slot[7] == 'AVAILABLE' ? 'selected' : '';
                  $sl4 = $slot[7] == 'UNAVAILABLE' ? 'selected' : '';
                  $img = ['IEC 62196-2' => 'b1.png', 'Scame Type 3' => 'b2.png', 'CHAdeMO' => 'b2.png', 'CCS' => 'b2.png', 'Tesla Supercharger' => 'b3.png'][$slot[2]];
                  echo "<div class='ls-slot mt-4'> <div class='row'> <div class='col-lg-3'> <img src='images/bunks/" . $img . "' class='img-fluid'> <br/> <h5 style='text-align: center;'>Slot: $slot[1]</h5> </div> <div class='col-lg-9'> <table border='0' cellspacing='10' cellpadding='10'> <tr> <td>Type</td> <td>:</td> <td>$slot[2]</td> </tr> <tr> <td>Supported Connectors</td> <td>:</td> <td>$slot[5]</td> </tr> <tr> <td>Max Load</td> <td>:</td> <td>$slot[3] kW</td> </tr> <tr> <td>Max Voltage</td> <td>:</td> <td>$slot[4] V</td> </tr> <tr> <td>Status</td> <td>:</td> <td><select onchange='changestat($slot[0], this.value)'> <option value='Operational' $sl1>Operational</option> <option value='Out of Service' $sl2>Out of Service</option> </select></td> </tr> <tr> <td>Service</td> <td>:</td> <td><select onchange='changecur($slot[0], this.value)'> <option value='AVAILABLE' $sl3>Available</option> <option value='UNAVAILABLE' $sl4>Unavailable</option> </select></td> </tr> <tr> <td>Remaining Time</td> <td>:</td> <td>$slot[8]</td> </tr> </table> </div> </div> </div>";
                }
              }

              ?>
            </div>

          </div>
        </div>
      </div>
      <div id="div4" style="display: none">
        <div class="container">

          <div class="col-lg-10 mt-4 mx-auto">
            <h3>Booking List</h3>
            <div class="">
              <table class="table">
                <thead>
                  <th>Customer Name</th>
                  <th>Phone Number</th>
                  <th>Charging Slot</th>
                  <th>Date</th>
                  <th>Time Slot</th>
                </thead>
                <tbody>
                  <?php
                  foreach ($bookings as $bk) {
                    echo "<tr><td>$bk[8]</td><td>$bk[9]</td><td>$bk[10]</td><td>$bk[4]</td><td>$bk[5]</td></tr>";
                    echo "<tr><td colspan='2'>Total Units: <input id='t$bk[0]' type='text'></td><td colspan='2'>Status: <select id='s$bk[0]'><option value='0'>Pending</option><option value='1'>Completed</option></select></td><td><button onclick='closebook($bk[0])' class='btn btn-block btn-primary btn-sm'>Submit</button></td></tr>";
                  }

                  ?>
                </tbody>
              </table>

            </div>
          </div>
        </div>
      </div>
      <div id="div5"></div>
      <div id="div6" style="display: none">
        <div class="">
          <div class="container">

            <div class="col-lg-10 mt-4 mx-auto">
              <h3>Profile Settings</h3>
              <form method="POST" action="bunk_admin.php">

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
              <form class="mt-4" action="bunk_admin.php" method="POST">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label>Phone</label>
                    <div class="input-group">
                      <input type="tel" value="<?php echo $phone; ?>" pattern="[0-9]{10}" title="10 digit numeric numbers only" required class="form-control form-control border-left-0" name="phone" placeholder="10 digit number">
                    </div>
                  </div>
                </div>
                <div class="mt-6" align="center">
                  <input class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" type="submit" value="UPDATE PHONE">
                </div>
              </form>
              <form class="mt-4" method="POST" action="bunk_admin.php">
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>State</label>
                      <div class="input-group">
                        <select required class="form-control form-control border-left-0" name="state">
                          <option value="Andhra Pradesh">Andhra Pradesh</option>
                          <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                          <option value="Assam">Assam</option>
                          <option value="Bihar">Bihar</option>
                          <option value="Chhattisgarh">Chhattisgarh</option>
                          <option value="Goa">Goa</option>
                          <option value="Gujarat">Gujarat</option>
                          <option value="Haryana">Haryana</option>
                          <option value="Himachal Pradesh">Himachal Pradesh</option>
                          <option value="Jharkhand">Jharkhand</option>
                          <option value="Karnataka">Karnataka</option>
                          <option value="Kerala" selected>Kerala</option>
                          <option value="Madhya Pradesh">Madhya Pradesh</option>
                          <option value="Maharashtra">Maharashtra</option>
                          <option value="Manipur">Manipur</option>
                          <option value="Meghalaya">Meghalaya</option>
                          <option value="Mizoram">Mizoram</option>
                          <option value="Nagaland">Nagaland</option>
                          <option value="Odisha">Odisha</option>
                          <option value="Punjab">Punjab</option>
                          <option value="Rajasthan">Rajasthan</option>
                          <option value="Sikkim">Sikkim</option>
                          <option value="Tamil Nadu">Tamil Nadu</option>
                          <option value="Telangana">Telangana</option>
                          <option value="Tripura">Tripura</option>
                          <option value="Uttar Pradesh">Uttar Pradesh</option>
                          <option value="Uttarakhand">Uttarakhand</option>
                          <option value="West Bengal">West Bengal</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>District</label>
                      <div class="input-group">
                        <input value="<?php echo $district; ?>" type="text" pattern="[A-Za-z]{3,}" title="At least 3 characters with alphabetic characters only" required class="form-control form-control border-left-0" name="district" placeholder="District">
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>Place</label>
                      <div class="input-group">
                        <input type="text" value="<?php echo $address; ?>" required class="form-control form-control border-left-0" name="address" placeholder="Place">
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>Zip Code</label>
                      <div class="input-group">
                        <input type="tel" value="<?php echo $pin; ?>" pattern="[0-9]{6}" title="6 digit numeric numbers only" required class="form-control form-control border-left-0" name="pin" placeholder="XXXXXX">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label>Geo Location</label>
                  <div class="input-group">
                    <input type="url" value="<?php echo $url; ?>" title="Enter the geo location location from google map" required class="form-control border-left-0" name="url" placeholder="https://www.google.co.in/maps/@9.1493265,76.6066105,15z?entry=ttu">
                  </div>
                </div>
                <div class="mt-6" align="center">
                  <input class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" type="submit" value="UPDATE LOCATION">
                </div>
              </form>
            </div>
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
      $('#addslot').click(function() {
        let fd = new FormData($('#slots')[0])
        $.ajax({
          url: 'addslots.php',
          method: "POST",
          data: fd,
          contentType: false,
          processData: false,
          success: function(response) {
            alert(response)
          }
        })
      })


      $('#btn1, #btn2, #btn3, #btn4, #btn5, #btn6').click(function() {
        id = this.id
        let dct = {
          btn1: '#div1',
          btn2: '#div2',
          btn3: '#div3',
          btn4: '#div4',
          btn5: '#div5',
          btn6: '#div6'
        };
        for (let tg of Object.values(dct)) {
          // console.log(tg)
          $(tg).slideUp(1000);
        }
        $(dct[id]).slideDown(1000);
      })


      $('#remslot').click(() => {
        sname = $('#sname2').val()
        if (sname.length == 7) {
          $.post('removeslot.php', {
            sname: sname
          }, (data) => {
            alert(data);
            location.reload()
          })
        } else {
          alert("Please enter a valid slot")
        }
      })


      function changecur(d1, d2) {
        $.post('changestatus.php', {
          id: d1,
          data: d2,
          t: 'curstat'
        }, (data) => {
          alert(data);
        })

      }

      function changestat(d1, d2) {
        $.post('changestatus.php', {
          id: d1,
          data: d2,
          t: 'status'
        }, (data) => {
          alert(data);
        })

      }

      function closebook(cnt){
        units = $('#t'+cnt).val()
        status = $('#s'+cnt).val()
        $.post('closebooking.php', {
          status: status,
          units: units,
          id: cnt
        }, (data) => {
          alert(data);
          location.reload()
        })
      }
    </script>
</body>

</html>
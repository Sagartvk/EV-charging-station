<?php
require 'includes/connection.php';
session_start();
if (!isset($_SESSION['id'])) {
  header("Location: index.php");
}

$id = $_SESSION['id'];

$name = $_SESSION['name'];

$res = mysqli_execute_query($conn, "SELECT a.*, count(b.bid), c.location FROM bunk_admin AS a LEFT JOIN bunk_slots AS b ON a.id=b.bid LEFT JOIN geolocation as c ON a.id=c.id WHERE a.status=1 group by a.id;");
$bunks = [[]];
if (mysqli_num_rows($res) >= 1) {
  $bunks = mysqli_fetch_all($res);
}

$res = mysqli_execute_query($conn, "SELECT a.*, count(b.bid), c.location FROM bunk_admin AS a LEFT JOIN bunk_slots AS b ON a.id=b.bid LEFT JOIN geolocation as c ON a.id=c.id WHERE a.status=0 group by a.id;");
$inactivebunks = [[]];
if (mysqli_num_rows($res) >= 1) {
  $inactivebunks = mysqli_fetch_all($res);
}

if (isset($_POST['pswd'])) {
  $pswd = $_POST['pswd'];
  $pswd2 = $_POST['pswd2'];
  if ($pswd == $pswd2) {
    $pswd = md5($pswd);
    mysqli_execute_query($conn, "UPDATE admin SET pswd='$pswd' WHERE id=$id");
    echo "<script>alert('Password changed successfull!')</script>";
  } else {
    echo "<script>alert('Mismatching passwords')</script>";
  }
}

$email = mysqli_execute_query($conn, "SELECT uname FROM admin");
$email = mysqli_fetch_row($email)[0];


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>EcoCharge Navigator - Admin</title>
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
          <a role="button" class="nav-link" id='btn1'>
            <i class="mdi mdi-gas-station  menu-icon"></i>
            <span class="menu-title">Charging Slots</span>
          </a>
        </li>
        <li class="nav-item">
          <a role="button" class="nav-link" id="btn2">
            <i class="mdi mdi-view-headline menu-icon"></i>
            <span class="menu-title">Inactive Slots</span>
          </a>
        </li>

        <br />
        <li class="nav-item sidebar-category">
          <p>Settings</p>
          <span></span>
        </li>
        <li class="nav-item">
          <a role="button" class="nav-link" id="btn3">
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

      <div id="div1">
        <div class="container">

          <div class="col-lg-6 mt-4 mx-auto">
            <h3>Active Bunk List</h3>
            <div class="">
              <table border="0">
                <tbody>
                  <?php
                  foreach ($bunks as $bk) {
                    if (isset($bk[1])) {
                      $s1 = $bk[9] == '0' ? 'selected' : '';
                      $s2 = $bk[9] == '1' ? 'selected' : '';
                      echo "<tr>
                    <td>
                    
                    <div class='ls-slot mt-4'> <div class='row'> <div class='col-lg-4'> <br/><h4 align='center'>$bk[1]</h4><img src='images/bunks/b1.png' class='img-fluid'></div>

                    <div class='col-lg-8'>
                    <table border='0' cellpadding='5'> <tr> <td>Email</td> <td>:</td> <td>$bk[2]</td> </tr> <tr> <td>Phone</td> <td>:</td> <td>$bk[4]</td> </tr> <tr> <td>Place</td> <td>:</td> <td>$bk[5]</td> </tr> <tr> <td>District</td> <td>:</td> <td>$bk[6]</td> </tr> <tr> <td>State</td> <td>:</td> <td>$bk[7]</td> </tr> <tr> <td>Pin Code</td> <td>:</td> <td>$bk[8]</td> </tr> <tr> <td>Total Slots</td> <td>:</td> <td>$bk[10] Units</td> </tr> <tr><td>Status</td><td>:</td><td><select onchange='changestatus($bk[0], this.value)'><option value='0' $s1>Pending</option><option value='1' $s2>Approved</option></select></td></tr></table></div><div class='container'> '<a href='$bk[11]' target='_blank' class='btn btn-primary' style='width: 100%' role='button'>VIEW GEO LOCATION</a> </div></div></div>
                    
                    </td>
                    </tr>";
                    } else {
                      echo "No active slots found!";
                    }
                  }

                  ?>
                </tbody>
              </table>

            </div>
          </div>
        </div>
      </div>
      <div id="div2" style='display: none'>
        <div class="container">

          <div class="col-lg-6 mt-4 mx-auto">
            <h3>Inactive Bunk List</h3>
            <div class="">
              <table border="0">
                <tbody>
                  <?php
                  foreach ($inactivebunks as $bk) {
                    if (isset($bk[1])) {
                      $s1 = $bk[9] == '0' ? 'selected' : '';
                      $s2 = $bk[9] == '1' ? 'selected' : '';
                      echo "<tr>
          <td>
          
          <div class='ls-slot mt-4'> <div class='row'> <div class='col-lg-4'> <br/><h4 align='center'>$bk[1]</h4><img src='images/bunks/b3.png' class='img-fluid'></div>

          <div class='col-lg-8'>
          <table border='0' cellpadding='5'> <tr> <td>Email</td> <td>:</td> <td>$bk[2]</td> </tr> <tr> <td>Phone</td> <td>:</td> <td>$bk[4]</td> </tr> <tr> <td>Place</td> <td>:</td> <td>$bk[5]</td> </tr> <tr> <td>District</td> <td>:</td> <td>$bk[6]</td> </tr> <tr> <td>State</td> <td>:</td> <td>$bk[7]</td> </tr> <tr> <td>Pin Code</td> <td>:</td> <td>$bk[8]</td> </tr> <tr> <td>Total Slots</td> <td>:</td> <td>$bk[10] Units</td> </tr> <tr><td>Status</td><td>:</td><td><select onchange='changestatus($bk[0], this.value)'><option value='0' $s1>Pending</option><option value='1' $s2>Approved</option></select></td></tr></table></div><div class='container'> '<a href='$bk[11]' target='_blank' class='btn btn-primary' style='width: 100%' role='button'>VIEW GEO LOCATION</a> </div></div></div>
          
          </td>
          </tr>";
                    } else {
                      echo "No active slots found!";
                    }
                  }

                  ?>
                </tbody>
              </table>

            </div>
          </div>
        </div>
      </div>
      <div id="div3" style="display: none">
        <div class="">
          <div class="container">

            <div class="col-lg-10 mt-4 mx-auto">
              <h3>Profile Settings</h3>
              <form method="POST" action="admin.php">

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


      $('#btn1, #btn2, #btn3, #btn4').click(function() {
        id = this.id
        let dct = {
          btn1: '#div1',
          btn2: '#div2',
          btn3: '#div3'
        };
        for (let tg of Object.values(dct)) {
          // console.log(tg)
          $(tg).slideUp(1000);
        }
        $(dct[id]).slideDown(1000);
      })



      function changestatus(d1, d2) {
        $.post('changestatus2.php', {
          id: d1,
          data: d2,
        }, (data) => {
          alert(data);
          location.reload()
        })

      }
    </script>
</body>

</html>
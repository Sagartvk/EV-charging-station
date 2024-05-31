<?php
session_start();
require 'includes/connection.php';
if (isset($_POST['bname'])) {
  $bname = $_POST['bname'];
  $email = $_POST['email'];
  $address = $_POST['address'];
  $state = $_POST['state'];
  $district = $_POST['district'];
  $phone = $_POST['phone'];
  $pin = $_POST['pin'];
  $url = $_POST['url'];

  $_SESSION['bname'] = $bname;
  $_SESSION['email'] = $email;
  $_SESSION['address'] = $address;
  $_SESSION['state'] = $state;
  $_SESSION['district'] = $district;
  $_SESSION['phone'] = $phone;
  $_SESSION['pin'] = $pin;
  $_SESSION['url'] = $url;
  $_SESSION['pswd'] = $_POST['pswd'];

  $pattern = '/@(-?\d+\.\d+),(-?\d+\.\d+)/';
  preg_match($pattern, $url, $matches);
  if (isset($matches[1]) && isset($matches[2])) {
    $lt = $matches[1];
    $ln = $matches[2];

    $pswd = md5($_POST['pswd']);
    $sql = "INSERT INTO bunk_admin (name, uname, pswd, phone, address, district, state, pin) VALUES ('$bname', '$email', '$pswd', '$phone', '$address', '$district', '$state', '$pin')";
    $sql2 = "INSERT INTO geolocation VALUES ((SELECT id FROM bunk_admin WHERE uname='$email'), '$url', $lt, $ln)";
    if (mysqli_execute_query($conn, $sql)) {
      if (mysqli_execute_query($conn, $sql2)) {
        echo "<script>alert('Registration successfull!')</script>";
        session_unset();
      } else {
        echo "<script>alert('Registration failed!')</script>";
      }
    } else {
      echo "<script>alert('Registration failed!')</script>";
    }

  } else {
    echo "<script>alert('Invalid location url. Location must contains coordinates!')</script>";
  }
}

$bname=$email=$address=$state=$district=$phone=$pin=$url=$pswd='';
if (isset($_SESSION['bname'])){
  $bname = $_SESSION['bname'];
  $email = $_SESSION['email'];
  $address = $_SESSION['address'];
  $state = $_SESSION['state'];
  $district = $_SESSION['district'];
  $phone = $_SESSION['phone'];
  $pin = $_SESSION['pin'];
  $url = $_SESSION['url'];
  $pswd = $_SESSION['pswd'];
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>EcoCharge Navigator - Register</title>
  <!-- base:css -->
  <link rel="stylesheet" href="vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
</head>

<body>
  <div class="container-scroller d-flex">
    <div class="container-fluid page-body-wrapper full-page-wrapper d-flex">
      <div class="content-wrapper">
        <div class="row flex-grow">
          <div class="row">
            <div class="col-lg-7">
              <div class="text-left p-3">
                <h4>New here?</h4>
                <h6 class="font-weight-light">Join us today! It takes only few steps</h6>
                <br />
                <form method="POST" action="registerbunk.php">
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label>Fuel Station Name</label>
                        <div class="input-group">
                          <input type="text" value="<?php echo $bname;?>" placeholder="Station Name" name="bname" class="form-control form-control-sm border-left-0" required>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label>Username</label>
                        <div class="input-group">
                          <input type="text" value="<?php echo $email;?>" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" title="Enter a valid email address" required class="form-control form-control-sm border-left-0" name="email" placeholder="Email">
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label>Password</label>
                        <div class="input-group">
                          <input type="password" value="<?php echo $pswd;?>" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" title="At least 8 characters with 1 uppercase, 1 lowercase, 1 symbol" required class="form-control form-control-sm border-left-0" name="pswd" placeholder="Password">
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Address</label>
                    <div class="input-group">
                      <textarea required value="<?php echo $address;?>" class="form-control form-control-sm border-left-0" pattern="[A-Za-z]{10,}" title="At least 10 characters with alphabetic characters only" required name="address" placeholder="Type your address here..."></textarea>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label>State</label>
                        <div class="input-group">
                          <select required class="form-control form-control-sm border-left-0" name="state">
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
                            <option value="Kerala">Kerala</option>
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
                          <input value="<?php echo $district;?>" type="text" pattern="[A-Za-z]{3,}" title="At least 3 characters with alphabetic characters only" required class="form-control form-control-sm border-left-0" name="district" placeholder="District">
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label>Zip Code</label>
                        <div class="input-group">
                          <input type="tel" value="<?php echo $pin;?>" pattern="[0-9]{6}" title="6 digit numeric numbers only" required class="form-control form-control-sm border-left-0" name="pin" placeholder="XXXXXX">
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label>Phone</label>
                        <div class="input-group">
                          <input type="tel" value="<?php echo $phone;?>" pattern="[0-9]{10}" title="10 digit numeric numbers only" required class="form-control form-control-sm border-left-0" name="phone" placeholder="10 digit number">
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Geo Location</label>
                    <div class="input-group">
                      <input type="url" title="Enter the geo location location from google map" required class="form-control form-control-sm border-left-0" name="url" placeholder="https://www.google.co.in/maps/@9.1493265,76.6066105,15z?entry=ttu">
                    </div>
                  </div>
                  <div class="mt-6" align="center">
                    <input class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" type="submit" value="REGISTER">
                  </div>
                </form>
              </div>

            </div>
            <!-- <div class="col-lg-5 col-xl-5" style="background-image: url('images/cars/bg2.webp');"> -->

          </div>
        </div>
      </div>
    </div>
    <!-- content-wrapper ends -->
  </div>
  <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- base:js -->
  <script src="vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <script src="js/jquery.cookie.js" type="text/javascript"></script>
  <!-- inject:js -->
  <script src="js/off-canvas.js"></script>
  <script src="js/hoverable-collapse.js"></script>
  <script src="js/template.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

  <!-- endinject -->
</body>

</html>
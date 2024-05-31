<?php
require 'includes/connection.php';
if (isset($_POST['fname'])) {
  $fname = $_POST['fname'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $pswd1 = md5($_POST['pswd1']);
  $sql = "INSERT INTO users (name, uname, pswd, phone) VALUES ('$fname', '$email', '$pswd1', '$phone')";
  if (mysqli_execute_query($conn, $sql)) {
    echo "<script>alert('Registration successfull!')</script>";
  } else {
    echo "<script>alert('Registration failed!')</script>";
  }
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
      <div class="content-wrapper d-flex align-items-stretch auth auth-img-bg">
        <div class="row flex-grow">
          <div class="col-lg-6 d-flex align-items-center justify-content-center">
            <div class="auth-form-transparent text-left p-3">

              <h4>New here?</h4>
              <h6 class="font-weight-light">Join us today! It takes only few steps</h6>
              <form class="pt-3" method="POST" action="register.php">
                <div class="form-group">
                  <label>Full Name</label>
                  <div class="input-group">
                    <div class="input-group-prepend bg-transparent">
                      <span class="input-group-text bg-transparent border-right-0">
                        <i class="mdi mdi-account-outline text-primary"></i>
                      </span>
                    </div>
                    <input type="text" name='fname' title="At least 3 characters with alphabetic characters only" required class="form-control form-control-lg border-left-0" placeholder="Full Name">
                  </div>
                </div>
                <div class="form-group">
                  <label>Phone</label>
                  <div class="input-group">
                    <div class="input-group-prepend bg-transparent">
                      <span class="input-group-text bg-transparent border-right-0">
                        <i class="mdi mdi-cellphone-basic text-primary"></i>
                      </span>
                    </div>
                    <input type="tel" name="phone" pattern="[0-9]{10}" title="Enter a valid phone number" required class="form-control form-control-lg border-left-0" placeholder="10 digit phone number">
                  </div>
                </div>
                <div class="form-group">
                  <label>Email</label>
                  <div class="input-group">
                    <div class="input-group-prepend bg-transparent">
                      <span class="input-group-text bg-transparent border-right-0">
                        <i class="mdi mdi-email-outline text-primary"></i>
                      </span>
                    </div>
                    <input type="email" name="email" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" title="Enter a valid email address" required class="form-control form-control-lg border-left-0" placeholder="Email ID">
                  </div>
                </div>
                <div class="form-group">
                  <label>Password</label>
                  <div class="input-group">
                    <div class="input-group-prepend bg-transparent">
                      <span class="input-group-text bg-transparent border-right-0">
                        <i class="mdi mdi-lock-outline text-primary"></i>
                      </span>
                    </div>
                    <input type="password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" title="At least 8 characters with 1 uppercase, 1 lowercase, 1 symbol" required class="form-control form-control-lg border-left-0" name="pswd1" placeholder="Password">
                  </div>
                </div>
                <div class="mt-6" align="center">
                  <input class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" type="submit" value="SIGN UP">
                </div>
                <div class="text-center mt-3 font-weight-light">
                  For bunk owners? <a href="registerbunk.php" class="text-primary">Create</a>
                </div>
                <div class="text-center mt-3 font-weight-light">
                  Already have an account? <a href="index.php" class="text-primary">Login</a>
                </div>
              </form>
            </div>

          </div>
          <div class="col-lg-6 register-half-bg d-none d-lg-flex flex-row">
            <p class="text-white font-weight-medium text-center flex-grow align-self-end">Copyright &copy; 2021 All rights reserved.</p>
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
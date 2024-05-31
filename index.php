<?php
session_start();
require 'includes/connection.php';

if (isset($_POST['email'])) {
  $user = $_POST['user'];
  $email = $_POST['email'];
  $pswd = md5($_POST['pswd']);
  $path = ['users' => 'customer.php', 'admin' => 'admin.php', 'bunk_admin' => 'bunk_admin.php'];
  $sql = "SELECT id, name FROM $user WHERE uname='$email' AND pswd='$pswd'";
  $res = mysqli_execute_query($conn, $sql);
  if (mysqli_num_rows($res) == 1) {

    $rows = mysqli_fetch_row($res);
    $_SESSION['name'] = $rows[1];
    $_SESSION['id'] = $rows[0];
    header("Location: $path[$user]");
  } else {
    echo "<script>alert('Invalid email or password!')</script>";
  }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>EcoCharge Navigator - Login</title>
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
          <div align="center"><img src="images/logo.png" width="150" alt="logo" /></div>
          <br/>
              <h4>Welcome back!</h4>
              <h6 class="font-weight-light">Happy to see you again!</h6>
              <form class="pt-3" method="POST" action="index.php">
                <div class="form-group">
                  <label for="exampleInputEmail">Select User</label>
                  <div class="input-group">
                    <div class="input-group-prepend bg-transparent">
                      <span class="input-group-text bg-transparent border-right-0">
                        <i class="mdi mdi-account-outline text-primary"></i>
                      </span>
                    </div>
                    <select class="form-control form-control-lg border-left-0" name="user">
                      <option value="users">Customer</option>
                      <option value="bunk_admin">Bunk Owner</option>
                      <option value="admin">Admin</option>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label for="exampleInputEmail">Username</label>
                  <div class="input-group">
                    <div class="input-group-prepend bg-transparent">
                      <span class="input-group-text bg-transparent border-right-0">
                        <i class="mdi mdi-account-outline text-primary"></i>
                      </span>
                    </div>
                    <input type="text" class="form-control form-control-lg border-left-0" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" title="Enter a valid email address" required name="email" placeholder="Email ID">
                  </div>
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword">Password</label>
                  <div class="input-group">
                    <div class="input-group-prepend bg-transparent">
                      <span class="input-group-text bg-transparent border-right-0">
                        <i class="mdi mdi-lock-outline text-primary"></i>
                      </span>
                    </div>
                    <input type="password" class="form-control form-control-lg border-left-0" name="pswd" placeholder="Password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" title="At least 8 characters with 1 uppercase, 1 lowercase, 1 symbol" required>
                  </div>
                </div>
                <div align="center">

                  <a href="#" class="auth-link text-black">Forgot password?</a>
                </div>
                <div class="my-3" align="center">
                  <input class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" type="submit" value="LOGIN">
                </div>
                <div class="text-center mt-4 font-weight-light">
                  Don't have an account? <a href="register.php" class="text-primary">Create</a>
                </div>
              </form>
            </div>
          </div>
          <div class="col-lg-6 login-half-bg d-none d-lg-flex flex-row">
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
  <!-- jQuery CDN -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3O6O8pW7hUEi1f4h2pAFqI1/o6h8f1bBt6Uq8A=" crossorigin="anonymous"></script>
  
</body>

</html>
<?php
session_start();
require 'includes/connection.php';

if (isset($_POST['status'])) {
  $status = $_POST['status'];
  $units = $_POST['units'];
  $id = $_POST['id'];


  $sql = "UPDATE booking SET status=$status, unit=$units WHERE id=$id";
  $res = mysqli_execute_query($conn, $sql);
  if ($res) {
    echo 'Operation success!';
  } else {
    echo "Operation failed!";
  }
}


?>
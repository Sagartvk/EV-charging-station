<?php
session_start();
require 'includes/connection.php';

if (isset($_POST['sname'])) {
  $id = $_SESSION['id'];
  $sname = $_POST['sname'];
  $stype = $_POST['stype'];
  $svolt = $_POST['svolt'];
  $sload = $_POST['sload'];
  $scon = $_POST['sconn'];
  $status = $_POST['sstat'];
  $curstat = 'AVAILABLE';
  $time = '0';


  $sql = "INSERT INTO bunk_slots (sname, stype, sload, svolt, scon, status, curstat, time, bid) VALUES ('$sname', '$stype', '$sload', '$svolt', '$scon', '$status', '$curstat', $time, $id)";
  $res = mysqli_execute_query($conn, $sql);
  if ($res) {
    echo $sname.' is added!';
  } else {
    echo "Failed to add ".$sname;
  }
}


?>
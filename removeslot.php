<?php
session_start();
require 'includes/connection.php';


if (isset($_POST['sname'])) {
  $id = $_SESSION['id'];
  $sname = $_POST['sname'];
  $sql = "DELETE FROM bunk_slots WHERE sname='$sname' AND bid=$id";
  $res = mysqli_execute_query($conn, $sql);
  if ($res) {
    echo $sname.' slot is removed!';
  } else {
    echo "Failed to remove slot ".$sname;
  }
}


?>
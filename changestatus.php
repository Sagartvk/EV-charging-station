<?php
session_start();
require 'includes/connection.php';


if (isset($_POST['id'])) {
  $id = $_POST['id'];
  $data = $_POST['data'];
  $type = $_POST['t'];
  $sql = "UPDATE bunk_slots SET $type='$data' WHERE sid=$id";
  $res = mysqli_execute_query($conn, $sql);
  if ($res) {
    echo 'Status changed';
  } else {
    echo "Failed to change status ".$sname;
  }
}


?>
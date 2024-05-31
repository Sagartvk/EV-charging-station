<?php
session_start();
require 'includes/connection.php';


if (isset($_POST['id'])) {
  $id = $_POST['id'];
  $data = $_POST['data'];
  $sql = "UPDATE bunk_admin SET status='$data' WHERE id=$id";
  $res = mysqli_execute_query($conn, $sql);
  if ($res) {
    echo 'Status changed';
  } else {
    echo "Failed to change status ".$sname;
  }
}


?>
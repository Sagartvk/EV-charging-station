<?php
session_start();
require 'includes/connection.php';

if (isset($_POST['id'])) {
  $id = $_POST['id'];
  $res = mysqli_execute_query($conn, "DELETE FROM booking WHERE id=$id");
  if ($res) {
    echo 'Operation success!';
  } else {
    echo "Operation failed!";
  }
}


?>
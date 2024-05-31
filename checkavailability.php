<?php
require 'includes/connection.php';

$sid = $_POST['sid'];
$tslot = $_POST['tslot'];
$date = $_POST['date'];

$res = mysqli_execute_query($conn, "SELECT COUNT(*) FROM booking WHERE sid=$sid AND time_slot='$tslot' AND date='$date' AND status=0");
$count = mysqli_fetch_row($res)[0];
if(intval($count) < 8){
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}


?>
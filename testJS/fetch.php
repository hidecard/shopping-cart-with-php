<?php 

$conn = mysqli_connect("localhost","root","hidecard","testJQ");
$output = '';
$query  =   "SELECT * FROM item order by item_id desc";
$result = mysqli_query($conn,$query);

?>
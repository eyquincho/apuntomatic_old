<?php
session_start();
ob_start();
include("conexionDB.php");
conexionDB();
$tbl_name="ap_users"; // Table name

// Define $nick and $password
$nick=$_POST['nick'];
$password=$_POST['password'];

// To protect MySQL injection (more detail about MySQL injection)
$nick = stripslashes($nick);
$password = MD5(stripslashes($password));
$nick = mysqli_real_escape_string($_SESSION['con'], $nick);
$password = mysqli_real_escape_string($_SESSION['con'], $password);

$sql="SELECT * FROM $tbl_name WHERE user_nick='$nick' and user_pass='$password'";
$result=mysqli_query($_SESSION['con'], $sql);

$sql_id = mysqli_fetch_object($result);
// mysqli_num_row is counting table row
$count=mysqli_num_rows($result);
// If result matched $nick and $password, table row must be 1 row

if($count==1){
// Register $nick, $password and redirect to file "login_success.php"
$_SESSION['nick']= $_POST['nick'];
$_SESSION["id"]= $sql_id->ID;
$_SESSION["admin"]= $sql_id->user_admin;
header("location:../index.php");
}
else {
 header("location:../index.php?log_er=1");
 }

ob_end_flush();
?>
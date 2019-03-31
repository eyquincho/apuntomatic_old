<?php 
session_start();
include("includes/conexionDB.php");
conexionDB();
$id = $_GET['id'];
$id_activo = $_SESSION['id'];
$update_archivo = "UPDATE ap_documentos SET descargas=(descargas + 1) WHERE id='$id'";
mysqli_query($_SESSION['con'], $update_archivo);
$update_user = "UPDATE ap_users SET user_downloads=(user_downloads + 1) WHERE id='$id_activo'";
mysqli_query($_SESSION['con'], $update_user);
echo 'No cargues esta p&aacute;gina';
echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";
// echo '<script type=""text/javascript"">history.back();</script>';
 ?>
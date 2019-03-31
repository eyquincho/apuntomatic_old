<?php @session_start();
	// Control de sesión inciada
	if(!isset($_SESSION['nick'])){
		header("Location: index.php");
		die();
	} else {
include_once("includes/conexionDB.php");
conexionDB();?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8"> 
	<title>Apuntomatic</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap.css" rel="stylesheet" />
    <link href="js/morris/morris-0.4.3.min.css" rel="stylesheet" />
    <link href="css/custom.css" rel="stylesheet" />
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
	<link href="css/apuntomatic.css" rel="stylesheet">
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
	<style>
	#sidebar-stats {
		background-color:#4a9b40!important;
	}
	</style>
  </head>
  <body>
  <?php include_once("includes/analytics.php") ?>
    <div id="wrapper" style="margin-top:20px;">
		<?php 
		include 'header.php';
		$perfil = mysqli_query($_SESSION['con'], "SELECT * FROM ap_users WHERE user_nick='".$_SESSION['nick']."'") or die(mysql_error());
		$row = mysqli_fetch_array($perfil);
		$id_sesion = $row["ID"];
		$nick_sesion = $row["user_nick"];
		$nom_sesion = $row["user_name"];
		$freg_sesion = $row["user_registered"];
		$sub_sesion = $row["user_files"];
		$desc_sesion = $row["user_downloads"];
		//gravatar
		$gravatarMd5 = md5($row["user_email"]);
		?>
           <!-- /. NAV TOP  -->
        <?php include 'sidebar.php'; ?>
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="col-md-6 col-sm-12 col-xs-12">                     
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Bar Chart Example
                        </div>
                        <div class="panel-body">
                            <div id="morris-bar-chart"></div>
                        </div>
                    </div>            
                </div>
             <!-- /. PAGE INNER  -->
            </div>
         <!-- /. PAGE WRAPPER  -->
        </div>
<?php 
include "footer.php";
?>
  </body>
  <!-- jS
    ================================================== -->
    <!-- Se carga al final para aliviar la descarga -->
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.metisMenu.js"></script>
     <script src="js/morris/raphael-2.1.0.min.js"></script>
    <script src="js/morris/morris.js"></script>
    <script src="js/custom.js"></script>
</html>
<?php } ?>   
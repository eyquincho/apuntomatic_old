<?php @session_start();
	if(!isset($_SESSION['nick'])){
		header("Location: index.php");
		die();
	} else {
		include_once("includes/conexionDB.php");
		conexionDB();// comprobamos que la sesión está iniciada	
		}
?>
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
	#sidebar-ajustes {
		background-color:#4a9b40!important;
	}
	
	</style>
  </head>
  <body>
  <?php include_once("includes/analytics.php");	?>
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
		<?php include 'sidebar.php'; ?>
		<div id="page-wrapper" >
            <div id="page-inner">
				<?php 
				if(isset($_POST['enviar'])) {
				if($_POST['usuario_clave'] != $_POST['usuario_clave_conf']) {
					echo "Las contraseñas ingresadas no coinciden. <a href='javascript:history.back();'>Reintentar</a>";
				}else {
					$usuario_nombre = $_SESSION['nick'];
					$antigua_clave = mysqli_real_escape_string($_SESSION['con'], $_POST["antigua_clave"]);
					$antigua_clave = md5($antigua_clave); // encriptamos la nueva contraseña con md5
					$usuario_clave = mysqli_real_escape_string($_SESSION['con'], $_POST["usuario_clave"]);
					$usuario_clave = md5($usuario_clave); // encriptamos la nueva contraseña con md5
					$pet_oldkey = mysqli_query($_SESSION['con'], "SELECT user_pass FROM `ap_users` WHERE `user_nick` = '". $_SESSION['nick'] . "'");
					$oldkey = mysqli_fetch_object($pet_oldkey);
					if ($oldkey->user_pass != $antigua_clave) {
						echo "<div class=\"alert alert-warning\" role=\"alert\">Tu contreña actual no es correcta</div>";
					}else { 
						$sql = mysqli_query($_SESSION['con'], "UPDATE ap_users SET user_pass='".$usuario_clave."' WHERE user_nick='".$usuario_nombre."'");
						if($sql) {
							echo "<div class=\"alert alert-success\" role=\"alert\">Contraseña cambiada</div>";
						}else {
							echo "<div class=\"alert alert-warning\" role=\"alert\">Ha ocurrido un error al tratar de modificar tu contraseña</div>";
						}
						}
				} 
			} 
			else {}
			?>
				<div class="col-md-6 col-sm-6 col-xs-9">
				<h2 style="color:#5b5b5f">Cambiar contraseña</h2>
				<form class="form center-block" action="<?=$_SERVER['PHP_SELF']?>" method="post">
					<div class="form-group">
					  <input type="password" class="form-control input-lg" name="antigua_clave"  placeholder="Contraseña actual">
					</div>
					<div class="form-group">
					  <input type="password" class="form-control input-lg" name="usuario_clave"  placeholder="Nueva contraseña">
					</div>
					<div class="form-group">
					  <input type="password" class="form-control input-lg" name="usuario_clave_conf"  placeholder="Confirmar">
					</div>
						<input type="submit" name="enviar" value="Enviar" class="btn btn-primary btn-lg btn-block"/>
				</form> 
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
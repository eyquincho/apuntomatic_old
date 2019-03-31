<?php
session_start();
include_once("includes/conexionDB.php");
conexionDB();

function mostrar_cosas() {
	if(isset($_SESSION['nick'])){
	include ("header_on.php");
	}
	else {
	include ("header_off.php");}
}
?>
<div class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">			
            <a class="navbar-left" href="index.php"><img class="logo"  src="img/logo.png" style="height:40px; margin-top:7px; margin-right:10px;" ></a>
		</div>
            <div class="navbar-right">	<?php mostrar_cosas(); ?></div>
    </div>
</div>
<?php if (isset($_GET['log_er'])) {
	echo "<div class=\"alert alert-warning\" role=\"alert\" style=\"margin-top:20px;\">Datos de inicio de sesi&oacute;n err&oacute;neos. <a href='javascript:history.back();'>Reintentar</a> o <a data-toggle=\"modal\" data-target=\"#recuperar\">Recupera tu contraseña</a></div>";
        }
	else {}
	if (isset($_GET['rec_er'])) {
		$i=$_GET['rec_er'];
		switch ($i) {
			case 1:
				echo "<div class=\"alert alert-warning\" role=\"alert\">El email ".$_POST['correo']." no pertenece a ningún usuario. <a data-toggle=\"modal\" data-target=\"#recuperar\">Reintentar</a></div>";
				break;
			case 2:
				echo "<div class=\"alert alert-warning\" role=\"alert\">No se ha podido enviar el email. <a data-toggle=\"modal\" data-target=\"#recuperar\">Reintentar</a></div>";
				break;
			case 3:
				echo "<div class=\"alert alert-success\" role=\"alert\">La nueva contraseña ha sido enviada al email asociado al usuario ".$usuario_nombre.". Si no aparece en tu bandeja de entrada, revisa la bandeja de Spam.</div>";
				break;
		}
	}
	else {}
?>
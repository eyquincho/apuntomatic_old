<?php @session_start();
	// Control de sesión inciada
	if(!isset($_SESSION['nick'])){
		header("Location: index.php");
		die();
	} else {
include_once("includes/conexionDB.php");
conexionDB();
mysqli_set_charset($_SESSION['con'], 'utf8');
?>
<?php
function mostrar_lista() {
	$docs_tabla = mysqli_query($_SESSION['con'], "SELECT id, usuario_id, asignatura_id, file, size, nombre, descripcion, tipo, descargas, anonimo FROM `ap_documentos` WHERE `relacion`=0 ORDER BY `id` DESC LIMIT 10");
	while ($seleccionada = mysqli_fetch_object($docs_tabla)) {
		$petnomuser = mysqli_query($_SESSION['con'], "SELECT user_nick FROM `ap_users` WHERE `ID` = ". $seleccionada->usuario_id . "");
		$consnomuser = mysqli_fetch_object($petnomuser);
		$sql_asignatura = mysqli_query($_SESSION['con'], "SELECT opcion FROM `ap_asignaturas` WHERE `ID` = ". $seleccionada->asignatura_id . "");
		$pet_asignatura = mysqli_fetch_object($sql_asignatura);
		if(isset($seleccionada->tipo))
		{
		$logo_tipo = "text";
		}  
		else {
		$logo_tipo = "pdf";
		}
		if(isset($seleccionada->anonimo) && $seleccionada->anonimo == '1')
		{
		$uploader = "Anónimo";
		}  
		else {
		$uploader = $consnomuser->user_nick;
		}
		echo '<tr>';
		echo '<td><center><i class="fa fa-file-' . $logo_tipo . '-o fa-2x"></i></center></td>';
		echo '<td><center>' . $uploader . '</center></td>';
		echo '<td><center>' . urldecode($seleccionada->nombre) . '</center></td>';
		echo '<td><center>' . $pet_asignatura->opcion . '</center></td>';
		echo '<td><center><a href="'. $seleccionada->file .'" onclick="window.open(\'descargar.php?id='. $seleccionada->id .'\')" target="_blank"><i class="fa fa-chevron-circle-down fa-2x"></i></a></center></td>';
		echo '</tr>';  
	}}
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta http-equiv="Content-Type" content="text/html;  charset=UTF-8"> 
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
	#sidebar-principal {
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
                <div class="row">
                    <div class="col-md-12">
                     <h2 style="color:#5b5b5f">Hola, <?php echo $nom_sesion; ?></h2>   
                        <h5>¿Cómo vas a colaborar hoy?</h5>
                    </div>
                </div>
                 <!-- /. ROW  -->
                <hr />
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="panel back-dash">
                               <i class="fa fa-bolt fa-3x"></i><strong> &nbsp; NOVEDADES</strong>
                             <p class="text-muted">Esta es la última actividad que ha habido en Apuntomatic, si te parece que está todo muy tranquilo, te animamos a que subas o edites el documento
							de alguien, todos cometemos errores, y a todos nos gusta corregir a los demás.</p>
                        </div>
                    </div> 
				</div>
                 <!-- /. ROW  -->
                <div class="row" >
                    <div class="col-md-9 col-sm-12 col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
											<th>Doc</th>
											<th>Usuario</th>
                                            <th>Documento</th>
											<th>Asignatura</th>
											<th>Enlace</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php mostrar_lista();	?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    </div>
					<div class="col-md-3 col-sm-12 col-xs-12">
                        <div class="panel back-dash">
                            <i class="fa fa-hand-o-down fa-3x"></i><strong> &nbsp; PUBLICIDAD</strong>                             
							<a href="#" target="_blank"><img src="img/square/nope.jpg" style="width:100%;"/></a>
							<p class="text-muted">Envia un email a hola@apuntomatic.com y haz que todo el mundo lo vea.</p>
                        </div>
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